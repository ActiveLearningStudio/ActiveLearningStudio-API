package org.curriki.api.enus.vertx;

import org.apache.commons.lang3.StringUtils;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import io.vertx.config.ConfigRetriever;
import io.vertx.config.ConfigRetrieverOptions;
import io.vertx.config.ConfigStoreOptions;
import io.vertx.core.AbstractVerticle;
import io.vertx.core.Future;
import io.vertx.core.Handler;
import io.vertx.core.Promise;
import io.vertx.core.eventbus.Message;
import io.vertx.core.json.JsonObject;
import io.vertx.ext.mail.MailClient;
import io.vertx.ext.mail.MailConfig;
import io.vertx.ext.mail.MailMessage;

/**
 * Keyword: classSimpleNameMailVerticle
 */ 
public class MailVerticle extends AbstractVerticle {
	private static final Logger LOG = LoggerFactory.getLogger(MailVerticle.class);

	public static final String MAIL_EVENTBUS_ADDRESS = "mail.outbox";
	public static final String MAIL_HEADER_FROM = "mail.from";
	public static final String MAIL_HEADER_TO = "mail.to";
	public static final String MAIL_HEADER_SUBJECT = "mail.subject";
	private MailClient mailClient;
	private String fallbackMailFrom;
	private String fallbackMailTo;
	private String fallbackMailSubject;

	@Override
	public void start(Promise<Void> startPromise) throws Exception {
		ConfigRetriever retriever = getConfigRetriever();
		Future<JsonObject> futureConfig = retriever.getConfig();
		futureConfig.onSuccess(config -> {
			this.configureEmail(config);
			startPromise.complete();
		}).onFailure(ex -> {
			startPromise.fail(ex);
		});
		vertx.eventBus().consumer(MAIL_EVENTBUS_ADDRESS).handler(mailSender());
	}

	private Handler<Message<Object>> mailSender() {
		return new Handler<Message<Object>>() {

			@Override
			public void handle(Message<Object> event) {

				String mailFrom = event.headers().get(MAIL_HEADER_FROM);
				if(StringUtils.isBlank(mailFrom)) {
					mailFrom = fallbackMailFrom;
				}

				String mailTo = event.headers().get(MAIL_HEADER_TO);
				if(StringUtils.isBlank(mailTo)) {
					mailTo = fallbackMailTo;
				}

				String mailSubject = event.headers().get(MAIL_HEADER_SUBJECT);
				if(StringUtils.isBlank(mailSubject)) {
					mailSubject = fallbackMailSubject;
				}

				String mailBody = String.valueOf(event.body());
				LOG.debug("Sending mail from={}, to={}, subject={}", mailFrom, mailTo, mailSubject);
				MailMessage message = new MailMessage();
				message.setFrom(mailFrom);
				message.setTo(mailTo);
				message.setSubject(mailSubject);
				message.setText(mailBody);
				mailClient.sendMail(message, result -> {
					if(result.succeeded()) {
						LOG.info(result.result().toString());
					} else {
						LOG.error("sendMail failed. ", result.cause());
					}
				});
			}
		};
	}

	private void configureEmail(JsonObject config) {
		try {
			MailConfig mailConfig = new MailConfig();
			mailConfig.setHostname(config.getString("emailHost"));
			mailConfig.setPort(config.getInteger("emailPort"));
			mailConfig.setSsl(config.getBoolean("emailSsl"));
			mailConfig.setUsername(config.getString("emailUsername"));
			mailConfig.setPassword(config.getString("emailPassword"));
			this.fallbackMailFrom = config.getString("emailFrom");
			this.fallbackMailTo = config.getString("emailTo");
			this.fallbackMailSubject = config.getString("emailSubject");
			this.mailClient = MailClient.createShared(vertx, mailConfig);
			LOG.info("The email was configured successfully. ");
		} catch(Exception ex) {
			LOG.error("The email was not configured successfully. ", ex);
		}
	}

	private ConfigRetriever getConfigRetriever() {
		ConfigRetrieverOptions retrieverOptions = new ConfigRetrieverOptions();

		retrieverOptions.setScanPeriod(0);

		ConfigStoreOptions storeApplicationProperties = new ConfigStoreOptions().setType("file").setFormat("properties").setConfig(new JsonObject().put("path", "application.properties"));
		retrieverOptions.addStore(storeApplicationProperties);

		String configPath = System.getenv("configPath");
		if(StringUtils.isNotBlank(configPath)) {
			ConfigStoreOptions storeIni = new ConfigStoreOptions().setType("file").setFormat("properties").setConfig(new JsonObject().put("path", configPath));
			retrieverOptions.addStore(storeIni);
		}

		ConfigStoreOptions storeEnv = new ConfigStoreOptions().setType("env");
		retrieverOptions.addStore(storeEnv);

		return ConfigRetriever.create(vertx, retrieverOptions);
	}
}
