package org.curriki.api.enus.quarkus;

import java.util.ArrayList;
import java.util.List;
import java.util.Optional;
import java.util.concurrent.TimeUnit;

import javax.enterprise.context.ApplicationScoped;
import javax.enterprise.event.Observes;
import javax.inject.Inject;

import org.apache.commons.lang3.StringUtils;
import org.apache.commons.lang3.exception.ExceptionUtils;
import org.apache.curator.RetryPolicy;
import org.apache.curator.framework.CuratorFramework;
import org.apache.curator.framework.CuratorFrameworkFactory;
import org.apache.curator.retry.ExponentialBackoffRetry;
import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.vertx.MainVerticle;
import org.curriki.api.enus.vertx.WorkerVerticle;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import io.netty.util.internal.shaded.org.jctools.queues.MessagePassingQueue.Consumer;
import io.quarkus.runtime.Quarkus;
import io.quarkus.runtime.StartupEvent;
import io.quarkus.runtime.annotations.QuarkusMain;
import io.vertx.config.ConfigRetriever;
import io.vertx.config.ConfigRetrieverOptions;
import io.vertx.config.ConfigStoreOptions;
import io.vertx.core.CompositeFuture;
import io.vertx.core.DeploymentOptions;
import io.vertx.core.Future;
import io.vertx.core.Promise;
import io.vertx.core.Vertx;
import io.vertx.core.VertxOptions;
import io.vertx.core.eventbus.EventBusOptions;
import io.vertx.core.json.JsonObject;
import io.vertx.core.spi.cluster.ClusterManager;
import io.vertx.pgclient.PgConnectOptions;
import io.vertx.pgclient.PgPool;
import io.vertx.spi.cluster.zookeeper.ZookeeperClusterManager;
import io.vertx.sqlclient.PoolOptions;

@QuarkusMain
@ApplicationScoped
public class QuarkusApp extends QuarkusAppGen<Object> {  
	private static final Logger LOG = LoggerFactory.getLogger(MainVerticle.class);

	@Inject
	Vertx vertx;

	public static void main(String...args) {
		Quarkus.run(args);
	}

	public void init(@Observes StartupEvent ev) {
		JsonObject zkConfig = new JsonObject();
		String zookeeperHostName = System.getenv(ConfigKeys.ZOOKEEPER_HOST_NAME);
		Integer zookeeperPort = Integer.parseInt(Optional.ofNullable(System.getenv(ConfigKeys.ZOOKEEPER_PORT)).orElse("2181"));
		String zookeeperHosts = Optional.ofNullable(System.getenv(ConfigKeys.ZOOKEEPER_HOSTS)).orElse(zookeeperHostName + ":" + zookeeperPort);
		RetryPolicy retryPolicy = new ExponentialBackoffRetry(1000, 3);
		CuratorFramework curatorFramework = CuratorFrameworkFactory.newClient(zookeeperHosts, retryPolicy);
		curatorFramework.start();
		Integer clusterPort = Optional.ofNullable(System.getenv(ConfigKeys.CLUSTER_PORT)).map(s -> Integer.parseInt(s)).orElse(null);
		String clusterHostName = System.getenv(ConfigKeys.CLUSTER_HOST_NAME);
		Integer clusterPublicPort = Optional.ofNullable(System.getenv(ConfigKeys.CLUSTER_PUBLIC_PORT)).map(s -> Integer.parseInt(s)).orElse(null);
		Integer siteInstances = Optional.ofNullable(System.getenv(ConfigKeys.SITE_INSTANCES)).map(s -> Integer.parseInt(s)).orElse(1);
		Long vertxWarningExceptionSeconds = Optional.ofNullable(System.getenv(ConfigKeys.VERTX_WARNING_EXCEPTION_SECONDS)).map(s -> Long.parseLong(s)).orElse(10L);
		String clusterPublicHostName = System.getenv(ConfigKeys.CLUSTER_PUBLIC_HOST_NAME);
		zkConfig.put("zookeeperHosts", zookeeperHosts);
		zkConfig.put("sessionTimeout", 500000);
		zkConfig.put("connectTimeout", 3000);
		zkConfig.put("rootPath", "ActiveLearningStudio-API");
		zkConfig.put("retry", new JsonObject() {
			{
				put("initialSleepTime", 3000);
				put("intervalTimes", 10000);
				put("maxTimes", 3);
			}
		});
		ClusterManager clusterManager = new ZookeeperClusterManager(zkConfig);
		VertxOptions vertxOptions = new VertxOptions();
		// For OpenShift
		EventBusOptions eventBusOptions = new EventBusOptions();
		String hostname = System.getenv(ConfigKeys.HOSTNAME);
		String openshiftService = System.getenv(ConfigKeys.OPENSHIFT_SERVICE);
		if(clusterHostName == null) {
			clusterHostName = hostname;
		}
		if(clusterPublicHostName == null) {
			if(hostname != null && openshiftService != null) {
				clusterPublicHostName = hostname + "." + openshiftService;
			}
		}
		if(clusterHostName != null) {
			LOG.info(String.format("%s: %s", ConfigKeys.CLUSTER_HOST_NAME, clusterHostName));
			eventBusOptions.setHost(clusterHostName);
		}
		if(clusterPort != null) {
			LOG.info(String.format("%s: %s", ConfigKeys.CLUSTER_PORT, clusterPort));
			eventBusOptions.setPort(clusterPort);
		}
		if(clusterPublicHostName != null) {
			LOG.info(String.format("%s: %s", ConfigKeys.CLUSTER_PUBLIC_HOST_NAME, clusterPublicHostName));
			eventBusOptions.setClusterPublicHost(clusterPublicHostName);
		}
		if(clusterPublicPort != null) {
			LOG.info(String.format("%s: %s", ConfigKeys.CLUSTER_PUBLIC_PORT, clusterPublicPort));
			eventBusOptions.setClusterPublicPort(clusterPublicPort);
		}
		vertxOptions.setEventBusOptions(eventBusOptions);
		vertxOptions.setClusterManager(clusterManager);
		vertxOptions.setWarningExceptionTime(vertxWarningExceptionSeconds);
		vertxOptions.setWarningExceptionTimeUnit(TimeUnit.SECONDS);
		vertxOptions.setWorkerPoolSize(System.getenv(ConfigKeys.WORKER_POOL_SIZE) == null ? 5 : Integer.parseInt(System.getenv(ConfigKeys.WORKER_POOL_SIZE)));
		Consumer<Vertx> runner = vertx -> {
			configureConfig(vertx).onSuccess(config -> {
				configureData(vertx, config).onSuccess(pgPool -> {
					try {
						List<Future> futures = new ArrayList<>();
	
						DeploymentOptions deploymentOptions = new DeploymentOptions();
						deploymentOptions.setConfig(config);
			
						DeploymentOptions mailVerticleDeploymentOptions = new DeploymentOptions();
						mailVerticleDeploymentOptions.setConfig(config);
						mailVerticleDeploymentOptions.setWorker(true);
			
						DeploymentOptions workerVerticleDeploymentOptions = new DeploymentOptions();
						workerVerticleDeploymentOptions.setConfig(config);
						workerVerticleDeploymentOptions.setInstances(1);
			
						for(Integer i = 0; i < siteInstances; i++) {
							MainVerticle mainVerticle = new MainVerticle();
							mainVerticle.setPgPool(pgPool);
							futures.add(vertx.deployVerticle(mainVerticle, deploymentOptions));
						}
						CompositeFuture.all(futures).onSuccess(a -> {
							LOG.info("Started main verticle. ");
							vertx.deployVerticle(WorkerVerticle.class, workerVerticleDeploymentOptions).onSuccess(b -> {
								LOG.info("Started worker verticle. ");
							}).onFailure(ex -> {
								LOG.error("Failed to start worker verticle. ", ex);
							});
						}).onFailure(ex -> {
							LOG.error("Failed to start main verticle. ", ex);
						});
					} catch (Throwable ex) {
						LOG.error("Creating clustered Vertx failed. ", ex);
						ExceptionUtils.rethrow(ex);
					}
				}).onFailure(ex -> {
					LOG.error("Creating clustered Vertx failed. ", ex);
					ExceptionUtils.rethrow(ex);
				});
			}).onFailure(ex -> {
				LOG.error("Creating clustered Vertx failed. ", ex);
				ExceptionUtils.rethrow(ex);
			});
		};

		Vertx.clusteredVertx(vertxOptions).onSuccess(vertx -> {
			runner.accept(vertx);
		}).onFailure(ex -> {
			LOG.error("Creating clustered Vertx failed. ", ex);
			ExceptionUtils.rethrow(ex);
		});
	}

	/**	
	 * Val.Complete.enUS:The config was configured successfully. 
	 * Val.Fail.enUS:Could not configure the config(). 
	 **/
	public Future<JsonObject> configureConfig(Vertx vertx) {
		Promise<JsonObject> promise = Promise.promise();

		try {
			ConfigRetrieverOptions retrieverOptions = new ConfigRetrieverOptions();

			retrieverOptions.addStore(new ConfigStoreOptions().setType("file").setFormat("properties").setConfig(new JsonObject().put("path", "application.properties")));

			String configPath = System.getenv(ConfigKeys.CONFIG_PATH);
			if(StringUtils.isNotBlank(configPath)) {
				ConfigStoreOptions configIni = new ConfigStoreOptions().setType("file").setFormat("yaml").setConfig(new JsonObject().put("path", configPath));
				retrieverOptions.addStore(configIni);
			}

			ConfigStoreOptions storeEnv = new ConfigStoreOptions().setType("env");
			retrieverOptions.addStore(storeEnv);

			ConfigRetriever configRetriever = ConfigRetriever.create(vertx, retrieverOptions);
			configRetriever.getConfig().onSuccess(config -> {
				LOG.info("The config was configured successfully. ");
				promise.complete(config);
			}).onFailure(ex -> {
				LOG.error("Unable to configure site context. ", ex);
				promise.fail(ex);
			});
		} catch(Exception ex) {
			LOG.error("Unable to configure site context. ", ex);
			promise.fail(ex);
		}

		return promise.future();
	}

	/**	
	 * 
	 * Val.ConnectionError.enUS:Could not open the database client connection. 
	 * Val.ConnectionSuccess.enUS:The database client connection was successful. 
	 * 
	 * Val.InitError.enUS:Could not initialize the database tables. 
	 * Val.InitSuccess.enUS:The database tables were created successfully. 
	 * 
	 *	Configure shared database connections across the cluster for massive scaling of the application. 
	 *	Return a promise that configures a shared database client connection. 
	 *	Load the database configuration into a shared io.vertx.ext.jdbc.JDBCClient for a scalable, clustered datasource connection pool. 
	 *	Initialize the database tables if not already created for the first time. 
	 **/
	private static Future<PgPool> configureData(Vertx vertx, JsonObject config) {
		Promise<PgPool> promise = Promise.promise();
		try {
			PgConnectOptions pgOptions = new PgConnectOptions();
			pgOptions.setPort(config.getInteger(ConfigKeys.JDBC_PORT));
			pgOptions.setHost(config.getString(ConfigKeys.JDBC_HOST));
			pgOptions.setDatabase(config.getString(ConfigKeys.JDBC_DATABASE));
			pgOptions.setUser(config.getString(ConfigKeys.JDBC_USERNAME));
			pgOptions.setPassword(config.getString(ConfigKeys.JDBC_PASSWORD));
			Optional.ofNullable(config.getInteger(ConfigKeys.JDBC_MAX_IDLE_TIME)).ifPresent(idleTimeout -> pgOptions.setIdleTimeout(idleTimeout));
			pgOptions.setIdleTimeoutUnit(TimeUnit.SECONDS);
			Optional.ofNullable(config.getInteger(ConfigKeys.JDBC_CONNECT_TIMEOUT)).ifPresent(connectTimeout -> pgOptions.setConnectTimeout(connectTimeout));

			PoolOptions poolOptions = new PoolOptions();
			Integer jdbcMaxPoolSize = config.getInteger(ConfigKeys.JDBC_MAX_POOL_SIZE);
			Integer jdbcMaxWaitQueueSize = config.getInteger(ConfigKeys.JDBC_MAX_WAIT_QUEUE_SIZE);
			poolOptions.setMaxSize(jdbcMaxPoolSize);
			poolOptions.setMaxWaitQueueSize(jdbcMaxWaitQueueSize);

			PgPool pgPool = PgPool.pool(vertx, pgOptions, poolOptions);

			LOG.info(configureDataInitSuccess);
			promise.complete(pgPool);
		} catch (Exception ex) {
			LOG.error(configureDataInitError, ex);
			promise.fail(ex);
		}

		return promise.future();
	}
}
