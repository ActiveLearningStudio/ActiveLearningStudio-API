package org.curriki.api.enus.page;  

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;
import java.util.stream.Stream;

import org.apache.commons.lang3.StringUtils;
import org.apache.commons.lang3.exception.ExceptionUtils;
import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;

import io.vertx.core.Promise;
import io.vertx.core.json.JsonObject;
import io.vertx.ext.web.api.service.ServiceRequest;

/**
 * Keyword: classSimpleNamePageLayout
 **/
public class PageLayout extends PageLayoutGen<Object> {

	/**
	 * Ignore: true
	**/
	protected void _siteRequest_(Wrap<SiteRequestEnUS> w) {
	}

	protected void _serviceRequest(Wrap<ServiceRequest> w) {
		w.o(siteRequest_.getServiceRequest());
	}

	protected void _requestZoneId(Wrap<String> w) {
		w.o(Optional.ofNullable(siteRequest_.getRequestVars().get(VAR_requestZoneId)).orElse(siteRequest_.getConfig().getString(ConfigKeys.SITE_ZONE)));
	}

	protected void _requestLocaleId(Wrap<String> w) {
		w.o(Optional.ofNullable(siteRequest_.getRequestHeaders().get("Accept-Language")).map(acceptLanguage -> StringUtils.substringBefore(acceptLanguage, ",")).orElse(siteRequest_.getConfig().getString(ConfigKeys.SITE_LOCALE)));
	}

	protected void _staticBaseUrl(Wrap<String> w) {
		w.o(siteRequest_.getConfig().getString(ConfigKeys.STATIC_BASE_URL));
	}

	protected void _authUrl(Wrap<String> w) {
		w.o(siteRequest_.getConfig().getString(ConfigKeys.AUTH_URL));
	}

	protected void _authRealm(Wrap<String> w) {
		w.o(siteRequest_.getConfig().getString(ConfigKeys.AUTH_REALM));
	}

	protected void _pageUri(Wrap<String> w) {
		w.o(serviceRequest.getExtra().getString("uri"));
	}

	protected void _pageMethod(Wrap<String> w) {
		w.o(serviceRequest.getExtra().getString("method"));
	}

	protected void _params(Wrap<JsonObject> w) {
		w.o(serviceRequest.getParams());
	}

	protected void _userKey(Wrap<Long> w) {
		w.o(siteRequest_.getUserKey());
	}

	protected void _userFullName(Wrap<String> w) {
		w.o(siteRequest_.getUserFullName());
	}

	protected void _userName(Wrap<String> w) {
		w.o(siteRequest_.getUserFullName());
	}

	protected void _userEmail(Wrap<String> w) {
		w.o(siteRequest_.getUserEmail());
	}

	protected void _logoutUrl(Wrap<String> w) {
		JsonObject config = siteRequest_.getConfig();
		try {
			w.o(config.getString(ConfigKeys.AUTH_URL) + "/realms/" + config.getString(ConfigKeys.AUTH_REALM) + "/protocol/openid-connect/logout?redirect_uri=" + URLEncoder.encode(config.getString(ConfigKeys.SITE_BASE_URL) + "/logout", "UTF-8"));
		} catch (UnsupportedEncodingException ex) {
			ExceptionUtils.rethrow(ex);
		}
	}

	protected void _long0(Wrap<Long> w) {
		w.o(0L);
	}

	protected void _long1(Wrap<Long> w) {
		w.o(1L);
	}

	protected void _int0(Wrap<Integer> w) {
		w.o(0);
	}

	protected void _int1(Wrap<Integer> w) {
		w.o(1);
	}

	/**
	 * Ignore: true
	**/
	protected void _promiseBefore(Promise<Void> promise) {
		promise.complete();
	}

	protected void _classSimpleName(Wrap<String> c) {
	}

	protected void _pageTitle(Wrap<String> c) {
	}

	protected void _roles(List<String> l) {
		if(siteRequest_ != null) {
			l.addAll(Stream.concat(siteRequest_.getUserResourceRoles().stream(), siteRequest_.getUserResourceRoles().stream()).collect(Collectors.toList()));
		}
	}

	protected void _rolesRequired(List<String> l) {
		l.addAll(siteRequest_.getConfig().getJsonArray(ConfigKeys.AUTH_ROLES_ADMIN).stream().map(o -> o.toString()).collect(Collectors.toList()));
	}

	protected void _authRolesAdmin(List<String> l) {
		l.addAll(siteRequest_.getConfig().getJsonArray(ConfigKeys.AUTH_ROLES_ADMIN).stream().map(o -> o.toString()).collect(Collectors.toList()));
	}

	protected void _pagination(JsonObject pagination) {
	}

	protected void _query(JsonObject query) {
	}

	/**
	 * Ignore: true
	**/
	protected void _promiseAfter(Promise<Void> promise) {
		promise.complete();
	}

	protected void _pageImageUri(Wrap<String> c) {
	}

	protected void _contextIconGroup(Wrap<String> c) {
	}

	protected void _contextIconName(Wrap<String> c) {
	}
}
