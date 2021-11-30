package org.curriki.api.enus.user;

import org.curriki.api.enus.request.SiteRequestEnUS;

import io.vertx.core.WorkerExecutor;
import io.vertx.core.eventbus.EventBus;
import io.vertx.core.json.JsonObject;
import io.vertx.ext.auth.authorization.AuthorizationProvider;
import io.vertx.ext.auth.oauth2.OAuth2Auth;
import io.vertx.ext.web.client.WebClient;
import io.vertx.ext.web.templ.handlebars.HandlebarsTemplateEngine;
import io.vertx.pgclient.PgPool;

/**
 * Map.hackathonMission: to create a new Java API implementation class to extend and override any generated API functionality about site users in a database and a search engine. 
 * Map.hackathonColumn: Develop SiteUser API
 * Translate: false
 **/
public class SiteUserEnUSApiServiceImpl extends SiteUserEnUSGenApiServiceImpl {

	public SiteUserEnUSApiServiceImpl(EventBus eventBus, JsonObject config, WorkerExecutor workerExecutor, PgPool pgPool, WebClient webClient, OAuth2Auth oauth2AuthenticationProvider, AuthorizationProvider authorizationProvider, HandlebarsTemplateEngine templateEngine) {
		super(eventBus, config, workerExecutor, pgPool, webClient, oauth2AuthenticationProvider, authorizationProvider, templateEngine);
	}

	@Override
	public Boolean userDefine(SiteRequestEnUS siteRequest, JsonObject jsonObject, Boolean patch) {
		if("/user".equals(siteRequest.getRequestUri()))
			return true;
		else
			return super.userDefine(siteRequest, jsonObject, patch);
	}
}
