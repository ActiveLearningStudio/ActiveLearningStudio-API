package org.curriki.api.enus.vertx;

import java.math.BigDecimal;
import java.time.Duration;
import java.time.Instant;
import java.time.ZoneId;
import java.time.ZonedDateTime;
import java.time.format.DateTimeFormatter;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

import org.apache.commons.lang3.StringUtils;
import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.java.TimeTool;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.request.api.ApiRequest;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import io.vertx.core.AbstractVerticle;
import io.vertx.core.CompositeFuture;
import io.vertx.core.Future;
import io.vertx.core.MultiMap;
import io.vertx.core.Promise;
import io.vertx.core.WorkerExecutor;
import io.vertx.core.eventbus.DeliveryOptions;
import io.vertx.core.json.JsonArray;
import io.vertx.core.json.JsonObject;
import io.vertx.ext.auth.authentication.TokenCredentials;
import io.vertx.ext.auth.authentication.UsernamePasswordCredentials;
import io.vertx.ext.jdbc.JDBCClient;
import io.vertx.ext.mail.MailClient;
import io.vertx.ext.mail.MailConfig;
import io.vertx.ext.web.client.WebClient;
import io.vertx.ext.web.client.predicate.ResponsePredicate;
import io.vertx.pgclient.PgPool;
import io.vertx.sqlclient.Row;
import io.vertx.sqlclient.RowStream;

/**
 * Map.hackathonMission: to create a new Java class to run Vert.x verticle as a background worker to perform tasks in the background. 
 * Map.hackathonColumn: Quarkus app development
 */
public class WorkerVerticle extends WorkerVerticleGen<AbstractVerticle> {
	private static final Logger LOG = LoggerFactory.getLogger(WorkerVerticle.class);

	public static final Integer FACET_LIMIT = 100;

	public final DateTimeFormatter TIME_FORMAT = DateTimeFormatter.ofPattern("HH:mm:ss VV");

	/**
	 * A io.vertx.ext.jdbc.JDBCClient for connecting to the relational database PostgreSQL. 
	 **/
	private PgPool pgPool;

	private WebClient webClient;

	WorkerExecutor workerExecutor;

	JDBCClient jdbcClient;

	/**	
	 *	This is called by Vert.x when the verticle instance is deployed. 
	 *	Initialize a new site context object for storing information about the entire site in English. 
	 *	Setup the startPromise to handle the configuration steps and starting the server. 
	 **/
	@Override()
	public void  start(Promise<Void> startPromise) throws Exception, Exception {

		try {
			configureWebClient().compose(a -> 
				configureSharedWorkerExecutor().compose(c -> 
					configureEmail().compose(d -> 
						importData().compose(e -> 
							syncDbToSolr().compose(f -> 
								refreshAllData()
							)
						)
					)
				)
			).onComplete(startPromise);
		} catch (Exception ex) {
			LOG.error("Couldn't start verticle. ", ex);
		}
	}

	/**	
	 **/
	private Future<Void> configureWebClient() {
		Promise<Void> promise = Promise.promise();

		try {
			webClient = WebClient.create(vertx);
			promise.complete();
		} catch(Exception ex) {
			LOG.error("Unable to configure site context. ", ex);
			promise.fail(ex);
		}

		return promise.future();
	}

	/**	
	 * Val.Fail.enUS:Could not configure the shared worker executor. 
	 * Val.Complete.enUS:The shared worker executor "{}" was configured successfully. 
	 * 
	 *	Configure a shared worker executor for running blocking tasks in the background. 
	 *	Return a promise that configures the shared worker executor. 
	 **/
	private Future<Void> configureSharedWorkerExecutor() {
		Promise<Void> promise = Promise.promise();
		try {
			String name = "WorkerVerticle-WorkerExecutor";
			Integer workerPoolSize = System.getenv(ConfigKeys.WORKER_POOL_SIZE) == null ? 5 : Integer.parseInt(System.getenv(ConfigKeys.WORKER_POOL_SIZE));
			workerExecutor = vertx.createSharedWorkerExecutor(name, workerPoolSize);
			LOG.info(configureSharedWorkerExecutorComplete, name);
			promise.complete();
		} catch (Exception ex) {
			LOG.error(configureSharedWorkerExecutorFail, ex);
			promise.fail(ex);
		}
		return promise.future();
	}

	/**	
	 * Configure sending email. 
	 * Val.Complete.enUS:Configure sending email succeeded. 
	 * Val.Fail.enUS:Configure sending email failed. 
	 **/
	private Future<Void> configureEmail() {
		Promise<Void> promise = Promise.promise();
		try {
			String emailHost = config().getString(ConfigKeys.EMAIL_HOST);
			if(StringUtils.isNotBlank(emailHost)) {
				MailConfig mailConfig = new MailConfig();
				mailConfig.setHostname(emailHost);
				mailConfig.setPort(config().getInteger(ConfigKeys.EMAIL_PORT));
				mailConfig.setSsl(config().getBoolean(ConfigKeys.EMAIL_SSL));
				mailConfig.setUsername(config().getString(ConfigKeys.EMAIL_USERNAME));
				mailConfig.setPassword(config().getString(ConfigKeys.EMAIL_PASSWORD));
				MailClient.createShared(vertx, mailConfig);
				LOG.info(configureEmailComplete);
				promise.complete();
			} else {
				LOG.info(configureEmailComplete);
				promise.complete();
			}
		} catch (Exception ex) {
			LOG.error(configureEmailFail, ex);
			promise.fail(ex);
		}
		return promise.future();
	}

	/**
	 * Val.Scheduling.enUS:Scheduling the %s import at %s
	 * Val.Skip.enUS:Skip importing %s data. 
	 */
	private void importTimer(String classSimpleName) {
		if(config().getBoolean(String.format("%s_%s", ConfigKeys.ENABLE_IMPORT_DATA, classSimpleName), true)) {
			// Load the import start time and period configuration. 
			String importStartTime = config().getString(String.format("%s_%s", ConfigKeys.IMPORT_DATA_START_TIME, classSimpleName));
			String importPeriod = config().getString(String.format("%s_%s", ConfigKeys.IMPORT_DATA_PERIOD, classSimpleName));
			// Get the duration of the import period. 
			Duration duration = TimeTool.parseNextDuration(importPeriod);
			// Calculate the next start time, or the next start time after that, if the start time is in less than a minute, 
			// to give the following code enough time to complete it's calculations to ensure the import starts correctly. 
			ZonedDateTime nextStartTime = Optional.of(TimeTool.parseNextZonedTime(importStartTime))
					.map(t -> Duration.between(Instant.now(), t).toMinutes() < 1L ? t.plus(duration) : t).get();
			// Get the time now for the import start time zone. 
			ZonedDateTime now = ZonedDateTime.now(nextStartTime.getZone());
			BigDecimal[] divideAndRemainder = BigDecimal.valueOf(Duration.between(now, nextStartTime).toMillis())
					.divideAndRemainder(BigDecimal.valueOf(duration.toMillis()));
			Duration nextStartDuration = Duration.between(now, nextStartTime);
			if(divideAndRemainder[0].compareTo(BigDecimal.ONE) >= 0) {
				nextStartDuration = Duration.ofMillis(divideAndRemainder[1].longValueExact());
				nextStartTime = now.plus(nextStartDuration);
			}
			LOG.info(String.format(importTimerScheduling, classSimpleName, nextStartTime.format(TIME_FORMAT)));
			ZonedDateTime nextStartTime2 = nextStartTime;
			vertx.setTimer(nextStartDuration.toMillis(), a -> {
				importData(classSimpleName, nextStartTime2);
			});
		} else {
			LOG.info(String.format(importTimerSkip, classSimpleName));
		}
	}

	/**
	 * Val.Complete.enUS:Skip The Moonshot database was configured successfully. 
	 * Val.Fail.enUS:Skip The Moonshot database configuration failed. 
	 */
	private Future<Void> configureMoonshotsData() {
		Promise<Void> promise = Promise.promise();

		try {
			JsonObject jdbcOptions = new JsonObject();
			jdbcOptions.put("driver_class", config().getString(ConfigKeys.MOONSHOTS_DRIVER_CLASS));
			jdbcOptions.put("url", String.format("jdbc:mysql://%s:%s@%s:%s/%s?useSSL=false", 
					config().getString(ConfigKeys.MOONSHOTS_USERNAME)
					, config().getString(ConfigKeys.MOONSHOTS_PASSWORD)
					, config().getString(ConfigKeys.MOONSHOTS_HOST)
					, config().getString(ConfigKeys.MOONSHOTS_PORT)
					, config().getString(ConfigKeys.MOONSHOTS_DATABASE)
					));
			jdbcOptions.put("max_pool_size", config().getInteger(ConfigKeys.MOONSHOTS_MAX_POOL_SIZE));
			jdbcOptions.put("min_pool_size", config().getInteger(ConfigKeys.MOONSHOTS_MIN_POOL_SIZE));
			jdbcOptions.put("max_idle_time", config().getInteger(ConfigKeys.MOONSHOTS_MAX_IDLE_TIME));
			jdbcOptions.put("max_statements", config().getInteger(ConfigKeys.MOONSHOTS_MAX_STATEMENTS));
			jdbcOptions.put("max_statements_per_connection", config().getInteger(ConfigKeys.MOONSHOTS_MAX_STATEMENTS_PER_CONNECTION));

			jdbcClient = JDBCClient.createShared(vertx, jdbcOptions);
			LOG.info(configureMoonshotsDataComplete);
			promise.complete();
		} catch(Exception ex) {
			LOG.error(configureMoonshotsDataFail, ex);
			promise.fail(ex);
		}
		return promise.future();
	}

	private void importData(String classSimpleName, ZonedDateTime startDateTime) {
		if("CurrkiResource".equals(classSimpleName)) {
			importDataCurrikiResource().onComplete(a -> {
				String importPeriod = config().getString(String.format("%s_%s", ConfigKeys.IMPORT_DATA_PERIOD, classSimpleName));
				Duration duration = TimeTool.parseNextDuration(importPeriod);
				ZonedDateTime nextStartTime = startDateTime.plus(duration);
				LOG.info(String.format(importTimerScheduling, nextStartTime.format(TIME_FORMAT)));
				Duration nextStartDuration = Duration.between(Instant.now(), nextStartTime);
				vertx.setTimer(nextStartDuration.toMillis(), b -> {
					importData(classSimpleName, nextStartTime);
				});
			});
		}
	}

	/**	
	 * Import initial data
	 * Val.Complete.enUS:Importing initial data completed. 
	 * Val.Fail.enUS:Importing initial data failed. 
	 * Val.Skip.enUS:Skip importing data. 
	 **/
	private Future<Void> importData() {
		Promise<Void> promise = Promise.promise();
		importTimer("ChoiceDonor");
		importTimer("ChoiceImage");
		return promise.future();
	}

	private Future<Void> importDataCurrikiResource() {
		Promise<Void> promise = Promise.promise();
		return promise.future();
	}

	/**	
	 * Val.Complete.enUS:Syncing database to Solr completed. 
	 * Val.Fail.enUS:Syncing database to Solr failed. 
	 * Val.Skip.enUS:Skip syncing database to Solr. 
	 **/
	private Future<Void> syncDbToSolr() {
		Promise<Void> promise = Promise.promise();
		if(config().getBoolean(ConfigKeys.ENABLE_DB_SOLR_SYNC, false)) {
			Long millis = 1000L * config().getInteger(ConfigKeys.TIMER_DB_SOLR_SYNC_IN_SECONDS, 10);
			vertx.setTimer(millis, a -> {
				LOG.info(syncDbToSolrComplete);
				promise.complete();
			});
		} else {
			LOG.info(syncDbToSolrSkip);
			promise.complete();
		}
		return promise.future();
	}

	/**	
	 * Sync %s data from the database to Solr. 
	 * Val.Complete.enUS:%s data sync completed. 
	 * Val.Fail.enUS:%s data sync failed. 
	 * Val.CounterResetFail.enUS:%s data sync failed to reset counter. 
	 * Val.Skip.enUS:%s data sync skipped. 
	 * Val.Started.enUS:%s data sync started. 
	 **/
	private Future<Void> syncData(String tableName) {
		Promise<Void> promise = Promise.promise();
		try {
			if(config().getBoolean(String.format("%s_%s", ConfigKeys.ENABLE_DB_SOLR_SYNC, tableName), true)) {

				LOG.info(String.format(syncDataStarted, tableName));
				pgPool.withTransaction(sqlConnection -> {
					Promise<Void> promise1 = Promise.promise();
					sqlConnection.query(String.format("SELECT count(pk) FROM %s", tableName)).execute().onSuccess(countRowSet -> {
						try {
							Optional<Long> rowCountOptional = Optional.ofNullable(countRowSet.iterator().next()).map(row -> row.getLong(0));
							if(rowCountOptional.isPresent()) {
								Long apiCounterResume = config().getLong(ConfigKeys.API_COUNTER_RESUME);
								Integer apiCounterFetch = config().getInteger(ConfigKeys.API_COUNTER_FETCH);
								ApiCounter apiCounter = new ApiCounter();
	
								SiteRequestEnUS siteRequest = new SiteRequestEnUS();
								siteRequest.setConfig(config());
								siteRequest.initDeepSiteRequestEnUS(siteRequest);
		
								ApiRequest apiRequest = new ApiRequest();
								apiRequest.setRows(apiCounterFetch.intValue());
								apiRequest.setNumFound(rowCountOptional.get());
								apiRequest.setNumPATCH(apiCounter.getQueueNum());
								apiRequest.setCreated(ZonedDateTime.now(ZoneId.of(config().getString(ConfigKeys.SITE_ZONE))));
								apiRequest.initDeepApiRequest(siteRequest);
								vertx.eventBus().publish(String.format("websocket%s", tableName), JsonObject.mapFrom(apiRequest));
		
								sqlConnection.prepare(String.format("SELECT pk FROM %s", tableName)).onSuccess(preparedStatement -> {
									apiCounter.setQueueNum(0L);
									apiCounter.setTotalNum(0L);
									try {
										RowStream<Row> stream = preparedStatement.createStream(apiCounterFetch);
										stream.pause();
										stream.fetch(apiCounterFetch);
										stream.exceptionHandler(ex -> {
											LOG.error(String.format(syncDataFail, tableName), new RuntimeException(ex));
											promise1.fail(ex);
										});
										stream.endHandler(v -> {
											LOG.info(String.format(syncDataComplete, tableName));
											promise1.complete();
										});
										stream.handler(row -> {
											apiCounter.incrementQueueNum();
											try {
												vertx.eventBus().request(
														String.format("ActiveLearningStudio-API-enUS-%s", tableName)
														, new JsonObject().put(
																"context"
																, new JsonObject().put(
																		"params"
																		, new JsonObject()
																				.put("body", new JsonObject().put("pk", row.getLong(0).toString()))
																				.put("path", new JsonObject())
																				.put("cookie", new JsonObject())
																				.put("query", new JsonObject().put("q", "*:*").put("fq", new JsonArray().add("pk:" + row.getLong(0))).put("var", new JsonArray().add("refresh:false")))
																)
														)
														, new DeliveryOptions().addHeader("action", String.format("patch%sFuture", tableName))).onSuccess(a -> {
													apiCounter.incrementTotalNum();
													apiCounter.decrementQueueNum();
													if(apiCounter.getQueueNum().compareTo(apiCounterResume) == 0) {
														stream.fetch(apiCounterFetch);
														apiRequest.setNumPATCH(apiCounter.getTotalNum());
														apiRequest.setTimeRemaining(apiRequest.calculateTimeRemaining());
														vertx.eventBus().publish(String.format("websocket%s", tableName), JsonObject.mapFrom(apiRequest));
													}
												}).onFailure(ex -> {
													LOG.error(String.format(syncDataFail, tableName), ex);
													promise1.fail(ex);
												});
											} catch (Exception ex) {
												LOG.error(String.format(syncDataFail, tableName), ex);
												promise1.fail(ex);
											}
										});
									} catch (Exception ex) {
										LOG.error(String.format(syncDataFail, tableName), ex);
										promise1.fail(ex);
									}
								}).onFailure(ex -> {
									LOG.error(String.format(syncDataFail, tableName), ex);
									promise1.fail(ex);
								});
							} else {
								promise1.complete();
							}
						} catch (Exception ex) {
							LOG.error(String.format(syncDataFail, tableName), ex);
							promise1.fail(ex);
						}
					}).onFailure(ex -> {
						LOG.error(String.format(syncDataFail, tableName), ex);
						promise1.fail(ex);
					});
					return promise1.future();
				}).onSuccess(a -> {
					promise.complete();
				}).onFailure(ex -> {
					LOG.error(String.format(syncDataFail, tableName), ex);
					promise.fail(ex);
				});
			} else {
				LOG.info(String.format(syncDataSkip, tableName));
				promise.complete();
			}
		} catch (Exception ex) {
			LOG.error(String.format(syncDataFail, tableName), ex);
			promise.fail(ex);
		}
		return promise.future();
	}

	/**	
	 * Val.Complete.enUS:Refresh all data completed. 
	 * Val.Fail.enUS:Refresh all data failed. 
	 * Val.Skip.enUS:Refresh all data skipped. 
	 **/
	private Future<Void> refreshAllData() {
		Promise<Void> promise = Promise.promise();
		vertx.setTimer(1000 * 10, a -> {
			if(config().getBoolean(ConfigKeys.ENABLE_REFRESH_DATA, false)) {
				refreshData("SiteUser").onSuccess(b -> {
					LOG.info(refreshAllDataComplete);
					promise.complete();
				}).onFailure(ex -> {
					LOG.error(refreshAllDataFail, ex);
					promise.fail(ex);
				});
			} else {
				LOG.info(refreshAllDataSkip);
				promise.complete();
			}
		});
		return promise.future();
	}

	/**	
	 * Refresh %s data from the database to Solr. 
	 * Val.Complete.enUS:%s refresh completed. 
	 * Val.Fail.enUS:%s refresh failed. 
	 * Val.Skip.enUS:%s refresh skipped. 
	 **/
	private Future<Void> refreshData(String tableName) {
		Promise<Void> promise = Promise.promise();
		try {
			if(config().getBoolean(String.format("%s_%s", ConfigKeys.ENABLE_REFRESH_DATA, tableName), true)) {
				webClient.post(config().getInteger(ConfigKeys.AUTH_PORT), config().getString(ConfigKeys.AUTH_HOST_NAME), config().getString(ConfigKeys.AUTH_TOKEN_URI))
						.expect(ResponsePredicate.SC_OK)
						.ssl(config().getBoolean(ConfigKeys.AUTH_SSL))
						.authentication(new UsernamePasswordCredentials(config().getString(ConfigKeys.AUTH_RESOURCE), config().getString(ConfigKeys.AUTH_SECRET)))
						.putHeader("Content-Type", "application/x-www-form-urlencoded")
						.sendForm(MultiMap.caseInsensitiveMultiMap().set("grant_type", "client_credentials"))
						.onSuccess(tokenResponse -> {
					JsonObject token = tokenResponse.bodyAsJsonObject();
					JsonObject params = new JsonObject();
					params.put("body", new JsonObject());
					params.put("path", new JsonObject());
					params.put("cookie", new JsonObject());
					params.put("query", new JsonObject().put("q", "*:*").put("var", new JsonArray().add("refresh:false")));
					JsonObject context = new JsonObject().put("params", params).put("user", token);
					JsonObject json = new JsonObject().put("context", context);
					vertx.eventBus().request(String.format("ActiveLearningStudio-API-enUS-%s", tableName), json, new DeliveryOptions().addHeader("action", String.format("patch%s", tableName))).onSuccess(a -> {
						LOG.info(String.format(refreshDataComplete, tableName));
						promise.complete();
					}).onFailure(ex -> {
						LOG.error(String.format(refreshDataFail, tableName), ex);
						promise.fail(ex);
					});
				}).onFailure(ex -> {
					LOG.error(String.format(refreshDataFail, tableName), ex);
					promise.fail(ex);
				});
			} else {
				LOG.info(String.format(refreshDataSkip, tableName));
				promise.complete();
			}
		} catch (Exception ex) {
			LOG.error(String.format(refreshDataFail, tableName), ex);
			promise.fail(ex);
		}
		return promise.future();
	}
}
