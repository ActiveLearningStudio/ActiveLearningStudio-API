package org.curriki.api.enus.page;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.Arrays;
import com.fasterxml.jackson.databind.ser.std.ToStringSerializer;
import org.curriki.api.enus.base.BaseModel;
import org.curriki.api.enus.request.api.ApiRequest;
import org.slf4j.LoggerFactory;
import java.util.HashMap;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.apache.commons.lang3.StringUtils;
import java.lang.Integer;
import java.text.NumberFormat;
import java.util.ArrayList;
import org.curriki.api.enus.wrap.Wrap;
import org.curriki.api.enus.java.ZonedDateTimeDeserializer;
import org.apache.commons.collections.CollectionUtils;
import java.lang.Long;
import com.fasterxml.jackson.databind.annotation.JsonSerialize;
import java.util.Map;
import com.fasterxml.jackson.annotation.JsonIgnore;
import io.vertx.core.json.JsonObject;
import org.curriki.api.enus.java.ZonedDateTimeSerializer;
import java.lang.String;
import java.math.RoundingMode;
import java.lang.Void;
import org.slf4j.Logger;
import java.math.MathContext;
import io.vertx.core.Promise;
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import io.vertx.core.Future;
import io.vertx.ext.web.api.service.ServiceRequest;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import java.util.List;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import java.lang.Object;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class PageLayoutGen<DEV> extends Object {
	protected static final Logger LOG = LoggerFactory.getLogger(PageLayout.class);

	//////////////////
	// siteRequest_ //
	//////////////////

	/**	 The entity siteRequest_
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected SiteRequestEnUS siteRequest_;

	/**	<br/> The entity siteRequest_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteRequest_">Find the entity siteRequest_ in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _siteRequest_(Wrap<SiteRequestEnUS> w);

	public SiteRequestEnUS getSiteRequest_() {
		return siteRequest_;
	}

	public void setSiteRequest_(SiteRequestEnUS siteRequest_) {
		this.siteRequest_ = siteRequest_;
	}
	public static SiteRequestEnUS staticSetSiteRequest_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected PageLayout siteRequest_Init() {
		Wrap<SiteRequestEnUS> siteRequest_Wrap = new Wrap<SiteRequestEnUS>().var("siteRequest_");
		if(siteRequest_ == null) {
			_siteRequest_(siteRequest_Wrap);
			setSiteRequest_(siteRequest_Wrap.o);
		}
		return (PageLayout)this;
	}

	////////////////////
	// serviceRequest //
	////////////////////

	/**	 The entity serviceRequest
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected ServiceRequest serviceRequest;

	/**	<br/> The entity serviceRequest
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:serviceRequest">Find the entity serviceRequest in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _serviceRequest(Wrap<ServiceRequest> w);

	public ServiceRequest getServiceRequest() {
		return serviceRequest;
	}

	public void setServiceRequest(ServiceRequest serviceRequest) {
		this.serviceRequest = serviceRequest;
	}
	public static ServiceRequest staticSetServiceRequest(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected PageLayout serviceRequestInit() {
		Wrap<ServiceRequest> serviceRequestWrap = new Wrap<ServiceRequest>().var("serviceRequest");
		if(serviceRequest == null) {
			_serviceRequest(serviceRequestWrap);
			setServiceRequest(serviceRequestWrap.o);
		}
		return (PageLayout)this;
	}

	///////////////////
	// requestZoneId //
	///////////////////

	/**	 The entity requestZoneId
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String requestZoneId;

	/**	<br/> The entity requestZoneId
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:requestZoneId">Find the entity requestZoneId in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _requestZoneId(Wrap<String> w);

	public String getRequestZoneId() {
		return requestZoneId;
	}
	public void setRequestZoneId(String o) {
		this.requestZoneId = PageLayout.staticSetRequestZoneId(siteRequest_, o);
	}
	public static String staticSetRequestZoneId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout requestZoneIdInit() {
		Wrap<String> requestZoneIdWrap = new Wrap<String>().var("requestZoneId");
		if(requestZoneId == null) {
			_requestZoneId(requestZoneIdWrap);
			setRequestZoneId(requestZoneIdWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrRequestZoneId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrRequestZoneId(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqRequestZoneId(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrRequestZoneId(siteRequest_, PageLayout.staticSolrRequestZoneId(siteRequest_, PageLayout.staticSetRequestZoneId(siteRequest_, o)));
	}

	/////////////////////
	// requestLocaleId //
	/////////////////////

	/**	 The entity requestLocaleId
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String requestLocaleId;

	/**	<br/> The entity requestLocaleId
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:requestLocaleId">Find the entity requestLocaleId in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _requestLocaleId(Wrap<String> w);

	public String getRequestLocaleId() {
		return requestLocaleId;
	}
	public void setRequestLocaleId(String o) {
		this.requestLocaleId = PageLayout.staticSetRequestLocaleId(siteRequest_, o);
	}
	public static String staticSetRequestLocaleId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout requestLocaleIdInit() {
		Wrap<String> requestLocaleIdWrap = new Wrap<String>().var("requestLocaleId");
		if(requestLocaleId == null) {
			_requestLocaleId(requestLocaleIdWrap);
			setRequestLocaleId(requestLocaleIdWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrRequestLocaleId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrRequestLocaleId(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqRequestLocaleId(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrRequestLocaleId(siteRequest_, PageLayout.staticSolrRequestLocaleId(siteRequest_, PageLayout.staticSetRequestLocaleId(siteRequest_, o)));
	}

	///////////////////
	// staticBaseUrl //
	///////////////////

	/**	 The entity staticBaseUrl
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String staticBaseUrl;

	/**	<br/> The entity staticBaseUrl
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:staticBaseUrl">Find the entity staticBaseUrl in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _staticBaseUrl(Wrap<String> w);

	public String getStaticBaseUrl() {
		return staticBaseUrl;
	}
	public void setStaticBaseUrl(String o) {
		this.staticBaseUrl = PageLayout.staticSetStaticBaseUrl(siteRequest_, o);
	}
	public static String staticSetStaticBaseUrl(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout staticBaseUrlInit() {
		Wrap<String> staticBaseUrlWrap = new Wrap<String>().var("staticBaseUrl");
		if(staticBaseUrl == null) {
			_staticBaseUrl(staticBaseUrlWrap);
			setStaticBaseUrl(staticBaseUrlWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrStaticBaseUrl(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrStaticBaseUrl(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqStaticBaseUrl(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrStaticBaseUrl(siteRequest_, PageLayout.staticSolrStaticBaseUrl(siteRequest_, PageLayout.staticSetStaticBaseUrl(siteRequest_, o)));
	}

	/////////////
	// authUrl //
	/////////////

	/**	 The entity authUrl
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String authUrl;

	/**	<br/> The entity authUrl
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:authUrl">Find the entity authUrl in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _authUrl(Wrap<String> w);

	public String getAuthUrl() {
		return authUrl;
	}
	public void setAuthUrl(String o) {
		this.authUrl = PageLayout.staticSetAuthUrl(siteRequest_, o);
	}
	public static String staticSetAuthUrl(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout authUrlInit() {
		Wrap<String> authUrlWrap = new Wrap<String>().var("authUrl");
		if(authUrl == null) {
			_authUrl(authUrlWrap);
			setAuthUrl(authUrlWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrAuthUrl(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrAuthUrl(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqAuthUrl(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrAuthUrl(siteRequest_, PageLayout.staticSolrAuthUrl(siteRequest_, PageLayout.staticSetAuthUrl(siteRequest_, o)));
	}

	///////////////
	// authRealm //
	///////////////

	/**	 The entity authRealm
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String authRealm;

	/**	<br/> The entity authRealm
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:authRealm">Find the entity authRealm in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _authRealm(Wrap<String> w);

	public String getAuthRealm() {
		return authRealm;
	}
	public void setAuthRealm(String o) {
		this.authRealm = PageLayout.staticSetAuthRealm(siteRequest_, o);
	}
	public static String staticSetAuthRealm(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout authRealmInit() {
		Wrap<String> authRealmWrap = new Wrap<String>().var("authRealm");
		if(authRealm == null) {
			_authRealm(authRealmWrap);
			setAuthRealm(authRealmWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrAuthRealm(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrAuthRealm(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqAuthRealm(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrAuthRealm(siteRequest_, PageLayout.staticSolrAuthRealm(siteRequest_, PageLayout.staticSetAuthRealm(siteRequest_, o)));
	}

	/////////////
	// pageUri //
	/////////////

	/**	 The entity pageUri
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String pageUri;

	/**	<br/> The entity pageUri
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pageUri">Find the entity pageUri in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _pageUri(Wrap<String> w);

	public String getPageUri() {
		return pageUri;
	}
	public void setPageUri(String o) {
		this.pageUri = PageLayout.staticSetPageUri(siteRequest_, o);
	}
	public static String staticSetPageUri(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout pageUriInit() {
		Wrap<String> pageUriWrap = new Wrap<String>().var("pageUri");
		if(pageUri == null) {
			_pageUri(pageUriWrap);
			setPageUri(pageUriWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrPageUri(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrPageUri(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPageUri(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrPageUri(siteRequest_, PageLayout.staticSolrPageUri(siteRequest_, PageLayout.staticSetPageUri(siteRequest_, o)));
	}

	////////////////
	// pageMethod //
	////////////////

	/**	 The entity pageMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String pageMethod;

	/**	<br/> The entity pageMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pageMethod">Find the entity pageMethod in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _pageMethod(Wrap<String> w);

	public String getPageMethod() {
		return pageMethod;
	}
	public void setPageMethod(String o) {
		this.pageMethod = PageLayout.staticSetPageMethod(siteRequest_, o);
	}
	public static String staticSetPageMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout pageMethodInit() {
		Wrap<String> pageMethodWrap = new Wrap<String>().var("pageMethod");
		if(pageMethod == null) {
			_pageMethod(pageMethodWrap);
			setPageMethod(pageMethodWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrPageMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrPageMethod(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPageMethod(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrPageMethod(siteRequest_, PageLayout.staticSolrPageMethod(siteRequest_, PageLayout.staticSetPageMethod(siteRequest_, o)));
	}

	////////////
	// params //
	////////////

	/**	 The entity params
	 *	 is defined as null before being initialized. 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonObject params;

	/**	<br/> The entity params
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:params">Find the entity params in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _params(Wrap<JsonObject> w);

	public JsonObject getParams() {
		return params;
	}

	public void setParams(JsonObject params) {
		this.params = params;
	}
	public static JsonObject staticSetParams(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected PageLayout paramsInit() {
		Wrap<JsonObject> paramsWrap = new Wrap<JsonObject>().var("params");
		if(params == null) {
			_params(paramsWrap);
			setParams(paramsWrap.o);
		}
		return (PageLayout)this;
	}

	/////////////
	// userKey //
	/////////////

	/**	 The entity userKey
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Long userKey;

	/**	<br/> The entity userKey
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userKey">Find the entity userKey in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userKey(Wrap<Long> w);

	public Long getUserKey() {
		return userKey;
	}

	public void setUserKey(Long userKey) {
		this.userKey = userKey;
	}
	@JsonIgnore
	public void setUserKey(String o) {
		this.userKey = PageLayout.staticSetUserKey(siteRequest_, o);
	}
	public static Long staticSetUserKey(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	protected PageLayout userKeyInit() {
		Wrap<Long> userKeyWrap = new Wrap<Long>().var("userKey");
		if(userKey == null) {
			_userKey(userKeyWrap);
			setUserKey(userKeyWrap.o);
		}
		return (PageLayout)this;
	}

	public static Long staticSolrUserKey(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrUserKey(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserKey(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrUserKey(siteRequest_, PageLayout.staticSolrUserKey(siteRequest_, PageLayout.staticSetUserKey(siteRequest_, o)));
	}

	//////////////////
	// userFullName //
	//////////////////

	/**	 The entity userFullName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String userFullName;

	/**	<br/> The entity userFullName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userFullName">Find the entity userFullName in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userFullName(Wrap<String> w);

	public String getUserFullName() {
		return userFullName;
	}
	public void setUserFullName(String o) {
		this.userFullName = PageLayout.staticSetUserFullName(siteRequest_, o);
	}
	public static String staticSetUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout userFullNameInit() {
		Wrap<String> userFullNameWrap = new Wrap<String>().var("userFullName");
		if(userFullName == null) {
			_userFullName(userFullNameWrap);
			setUserFullName(userFullNameWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrUserFullName(siteRequest_, PageLayout.staticSolrUserFullName(siteRequest_, PageLayout.staticSetUserFullName(siteRequest_, o)));
	}

	//////////////
	// userName //
	//////////////

	/**	 The entity userName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String userName;

	/**	<br/> The entity userName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userName">Find the entity userName in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userName(Wrap<String> w);

	public String getUserName() {
		return userName;
	}
	public void setUserName(String o) {
		this.userName = PageLayout.staticSetUserName(siteRequest_, o);
	}
	public static String staticSetUserName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout userNameInit() {
		Wrap<String> userNameWrap = new Wrap<String>().var("userName");
		if(userName == null) {
			_userName(userNameWrap);
			setUserName(userNameWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrUserName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserName(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrUserName(siteRequest_, PageLayout.staticSolrUserName(siteRequest_, PageLayout.staticSetUserName(siteRequest_, o)));
	}

	///////////////
	// userEmail //
	///////////////

	/**	 The entity userEmail
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String userEmail;

	/**	<br/> The entity userEmail
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userEmail">Find the entity userEmail in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userEmail(Wrap<String> w);

	public String getUserEmail() {
		return userEmail;
	}
	public void setUserEmail(String o) {
		this.userEmail = PageLayout.staticSetUserEmail(siteRequest_, o);
	}
	public static String staticSetUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout userEmailInit() {
		Wrap<String> userEmailWrap = new Wrap<String>().var("userEmail");
		if(userEmail == null) {
			_userEmail(userEmailWrap);
			setUserEmail(userEmailWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrUserEmail(siteRequest_, PageLayout.staticSolrUserEmail(siteRequest_, PageLayout.staticSetUserEmail(siteRequest_, o)));
	}

	///////////////
	// logoutUrl //
	///////////////

	/**	 The entity logoutUrl
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String logoutUrl;

	/**	<br/> The entity logoutUrl
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:logoutUrl">Find the entity logoutUrl in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _logoutUrl(Wrap<String> w);

	public String getLogoutUrl() {
		return logoutUrl;
	}
	public void setLogoutUrl(String o) {
		this.logoutUrl = PageLayout.staticSetLogoutUrl(siteRequest_, o);
	}
	public static String staticSetLogoutUrl(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout logoutUrlInit() {
		Wrap<String> logoutUrlWrap = new Wrap<String>().var("logoutUrl");
		if(logoutUrl == null) {
			_logoutUrl(logoutUrlWrap);
			setLogoutUrl(logoutUrlWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrLogoutUrl(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrLogoutUrl(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqLogoutUrl(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrLogoutUrl(siteRequest_, PageLayout.staticSolrLogoutUrl(siteRequest_, PageLayout.staticSetLogoutUrl(siteRequest_, o)));
	}

	///////////
	// long0 //
	///////////

	/**	 The entity long0
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Long long0;

	/**	<br/> The entity long0
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:long0">Find the entity long0 in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _long0(Wrap<Long> w);

	public Long getLong0() {
		return long0;
	}

	public void setLong0(Long long0) {
		this.long0 = long0;
	}
	@JsonIgnore
	public void setLong0(String o) {
		this.long0 = PageLayout.staticSetLong0(siteRequest_, o);
	}
	public static Long staticSetLong0(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	protected PageLayout long0Init() {
		Wrap<Long> long0Wrap = new Wrap<Long>().var("long0");
		if(long0 == null) {
			_long0(long0Wrap);
			setLong0(long0Wrap.o);
		}
		return (PageLayout)this;
	}

	public static Long staticSolrLong0(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrLong0(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqLong0(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrLong0(siteRequest_, PageLayout.staticSolrLong0(siteRequest_, PageLayout.staticSetLong0(siteRequest_, o)));
	}

	///////////
	// long1 //
	///////////

	/**	 The entity long1
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Long long1;

	/**	<br/> The entity long1
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:long1">Find the entity long1 in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _long1(Wrap<Long> w);

	public Long getLong1() {
		return long1;
	}

	public void setLong1(Long long1) {
		this.long1 = long1;
	}
	@JsonIgnore
	public void setLong1(String o) {
		this.long1 = PageLayout.staticSetLong1(siteRequest_, o);
	}
	public static Long staticSetLong1(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	protected PageLayout long1Init() {
		Wrap<Long> long1Wrap = new Wrap<Long>().var("long1");
		if(long1 == null) {
			_long1(long1Wrap);
			setLong1(long1Wrap.o);
		}
		return (PageLayout)this;
	}

	public static Long staticSolrLong1(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrLong1(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqLong1(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrLong1(siteRequest_, PageLayout.staticSolrLong1(siteRequest_, PageLayout.staticSetLong1(siteRequest_, o)));
	}

	//////////
	// int0 //
	//////////

	/**	 The entity int0
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer int0;

	/**	<br/> The entity int0
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:int0">Find the entity int0 in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _int0(Wrap<Integer> w);

	public Integer getInt0() {
		return int0;
	}

	public void setInt0(Integer int0) {
		this.int0 = int0;
	}
	@JsonIgnore
	public void setInt0(String o) {
		this.int0 = PageLayout.staticSetInt0(siteRequest_, o);
	}
	public static Integer staticSetInt0(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected PageLayout int0Init() {
		Wrap<Integer> int0Wrap = new Wrap<Integer>().var("int0");
		if(int0 == null) {
			_int0(int0Wrap);
			setInt0(int0Wrap.o);
		}
		return (PageLayout)this;
	}

	public static Integer staticSolrInt0(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrInt0(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqInt0(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrInt0(siteRequest_, PageLayout.staticSolrInt0(siteRequest_, PageLayout.staticSetInt0(siteRequest_, o)));
	}

	//////////
	// int1 //
	//////////

	/**	 The entity int1
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer int1;

	/**	<br/> The entity int1
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:int1">Find the entity int1 in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _int1(Wrap<Integer> w);

	public Integer getInt1() {
		return int1;
	}

	public void setInt1(Integer int1) {
		this.int1 = int1;
	}
	@JsonIgnore
	public void setInt1(String o) {
		this.int1 = PageLayout.staticSetInt1(siteRequest_, o);
	}
	public static Integer staticSetInt1(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected PageLayout int1Init() {
		Wrap<Integer> int1Wrap = new Wrap<Integer>().var("int1");
		if(int1 == null) {
			_int1(int1Wrap);
			setInt1(int1Wrap.o);
		}
		return (PageLayout)this;
	}

	public static Integer staticSolrInt1(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrInt1(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqInt1(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrInt1(siteRequest_, PageLayout.staticSolrInt1(siteRequest_, PageLayout.staticSetInt1(siteRequest_, o)));
	}

	///////////////////
	// promiseBefore //
	///////////////////

	/**	 The entity promiseBefore
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected Void promiseBefore;

	/**	<br/> The entity promiseBefore
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:promiseBefore">Find the entity promiseBefore in Solr</a>
	 * <br/>
	 * @param promise is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _promiseBefore(Promise<Void> promise);

	public Void getPromiseBefore() {
		return promiseBefore;
	}

	public void setPromiseBefore(Void promiseBefore) {
		this.promiseBefore = promiseBefore;
	}
	public static Void staticSetPromiseBefore(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected Future<Void> promiseBeforePromise() {
		Promise<Void> promise = Promise.promise();
		Promise<Void> promise2 = Promise.promise();
		_promiseBefore(promise2);
		promise2.future().onSuccess(o -> {
			setPromiseBefore(o);
			promise.complete(o);
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	/////////////////////
	// classSimpleName //
	/////////////////////

	/**	 The entity classSimpleName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classSimpleName;

	/**	<br/> The entity classSimpleName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classSimpleName">Find the entity classSimpleName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classSimpleName(Wrap<String> c);

	public String getClassSimpleName() {
		return classSimpleName;
	}
	public void setClassSimpleName(String o) {
		this.classSimpleName = PageLayout.staticSetClassSimpleName(siteRequest_, o);
	}
	public static String staticSetClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout classSimpleNameInit() {
		Wrap<String> classSimpleNameWrap = new Wrap<String>().var("classSimpleName");
		if(classSimpleName == null) {
			_classSimpleName(classSimpleNameWrap);
			setClassSimpleName(classSimpleNameWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrClassSimpleName(siteRequest_, PageLayout.staticSolrClassSimpleName(siteRequest_, PageLayout.staticSetClassSimpleName(siteRequest_, o)));
	}

	///////////////
	// pageTitle //
	///////////////

	/**	 The entity pageTitle
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String pageTitle;

	/**	<br/> The entity pageTitle
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pageTitle">Find the entity pageTitle in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _pageTitle(Wrap<String> c);

	public String getPageTitle() {
		return pageTitle;
	}
	public void setPageTitle(String o) {
		this.pageTitle = PageLayout.staticSetPageTitle(siteRequest_, o);
	}
	public static String staticSetPageTitle(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout pageTitleInit() {
		Wrap<String> pageTitleWrap = new Wrap<String>().var("pageTitle");
		if(pageTitle == null) {
			_pageTitle(pageTitleWrap);
			setPageTitle(pageTitleWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrPageTitle(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrPageTitle(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPageTitle(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrPageTitle(siteRequest_, PageLayout.staticSolrPageTitle(siteRequest_, PageLayout.staticSetPageTitle(siteRequest_, o)));
	}

	///////////
	// roles //
	///////////

	/**	 The entity roles
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<String>(). 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> roles = new ArrayList<String>();

	/**	<br/> The entity roles
	 *  It is constructed before being initialized with the constructor by default List<String>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:roles">Find the entity roles in Solr</a>
	 * <br/>
	 * @param roles is the entity already constructed. 
	 **/
	protected abstract void _roles(List<String> l);

	public List<String> getRoles() {
		return roles;
	}

	public void setRoles(List<String> roles) {
		this.roles = roles;
	}
	public static String staticSetRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public PageLayout addRoles(String...objets) {
		for(String o : objets) {
			addRoles(o);
		}
		return (PageLayout)this;
	}
	public PageLayout addRoles(String o) {
		if(o != null)
			this.roles.add(o);
		return (PageLayout)this;
	}
	@JsonIgnore
	public void setRoles(JsonArray objets) {
		roles.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addRoles(o);
		}
	}
	protected PageLayout rolesInit() {
		_roles(roles);
		return (PageLayout)this;
	}

	public static String staticSolrRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrRoles(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqRoles(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrRoles(siteRequest_, PageLayout.staticSolrRoles(siteRequest_, PageLayout.staticSetRoles(siteRequest_, o)));
	}

	///////////////////
	// rolesRequired //
	///////////////////

	/**	 The entity rolesRequired
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<String>(). 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> rolesRequired = new ArrayList<String>();

	/**	<br/> The entity rolesRequired
	 *  It is constructed before being initialized with the constructor by default List<String>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:rolesRequired">Find the entity rolesRequired in Solr</a>
	 * <br/>
	 * @param rolesRequired is the entity already constructed. 
	 **/
	protected abstract void _rolesRequired(List<String> l);

	public List<String> getRolesRequired() {
		return rolesRequired;
	}

	public void setRolesRequired(List<String> rolesRequired) {
		this.rolesRequired = rolesRequired;
	}
	public static String staticSetRolesRequired(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public PageLayout addRolesRequired(String...objets) {
		for(String o : objets) {
			addRolesRequired(o);
		}
		return (PageLayout)this;
	}
	public PageLayout addRolesRequired(String o) {
		if(o != null)
			this.rolesRequired.add(o);
		return (PageLayout)this;
	}
	@JsonIgnore
	public void setRolesRequired(JsonArray objets) {
		rolesRequired.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addRolesRequired(o);
		}
	}
	protected PageLayout rolesRequiredInit() {
		_rolesRequired(rolesRequired);
		return (PageLayout)this;
	}

	public static String staticSolrRolesRequired(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrRolesRequired(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqRolesRequired(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrRolesRequired(siteRequest_, PageLayout.staticSolrRolesRequired(siteRequest_, PageLayout.staticSetRolesRequired(siteRequest_, o)));
	}

	////////////////////
	// authRolesAdmin //
	////////////////////

	/**	 The entity authRolesAdmin
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<String>(). 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> authRolesAdmin = new ArrayList<String>();

	/**	<br/> The entity authRolesAdmin
	 *  It is constructed before being initialized with the constructor by default List<String>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:authRolesAdmin">Find the entity authRolesAdmin in Solr</a>
	 * <br/>
	 * @param authRolesAdmin is the entity already constructed. 
	 **/
	protected abstract void _authRolesAdmin(List<String> l);

	public List<String> getAuthRolesAdmin() {
		return authRolesAdmin;
	}

	public void setAuthRolesAdmin(List<String> authRolesAdmin) {
		this.authRolesAdmin = authRolesAdmin;
	}
	public static String staticSetAuthRolesAdmin(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public PageLayout addAuthRolesAdmin(String...objets) {
		for(String o : objets) {
			addAuthRolesAdmin(o);
		}
		return (PageLayout)this;
	}
	public PageLayout addAuthRolesAdmin(String o) {
		if(o != null)
			this.authRolesAdmin.add(o);
		return (PageLayout)this;
	}
	@JsonIgnore
	public void setAuthRolesAdmin(JsonArray objets) {
		authRolesAdmin.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addAuthRolesAdmin(o);
		}
	}
	protected PageLayout authRolesAdminInit() {
		_authRolesAdmin(authRolesAdmin);
		return (PageLayout)this;
	}

	public static String staticSolrAuthRolesAdmin(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrAuthRolesAdmin(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqAuthRolesAdmin(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrAuthRolesAdmin(siteRequest_, PageLayout.staticSolrAuthRolesAdmin(siteRequest_, PageLayout.staticSetAuthRolesAdmin(siteRequest_, o)));
	}

	////////////////
	// pagination //
	////////////////

	/**	 The entity pagination
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut JsonObject(). 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonObject pagination = new JsonObject();

	/**	<br/> The entity pagination
	 *  It is constructed before being initialized with the constructor by default JsonObject(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pagination">Find the entity pagination in Solr</a>
	 * <br/>
	 * @param pagination is the entity already constructed. 
	 **/
	protected abstract void _pagination(JsonObject pagination);

	public JsonObject getPagination() {
		return pagination;
	}

	public void setPagination(JsonObject pagination) {
		this.pagination = pagination;
	}
	public static JsonObject staticSetPagination(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected PageLayout paginationInit() {
		_pagination(pagination);
		return (PageLayout)this;
	}

	///////////
	// query //
	///////////

	/**	 The entity query
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut JsonObject(). 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonObject query = new JsonObject();

	/**	<br/> The entity query
	 *  It is constructed before being initialized with the constructor by default JsonObject(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:query">Find the entity query in Solr</a>
	 * <br/>
	 * @param query is the entity already constructed. 
	 **/
	protected abstract void _query(JsonObject query);

	public JsonObject getQuery() {
		return query;
	}

	public void setQuery(JsonObject query) {
		this.query = query;
	}
	public static JsonObject staticSetQuery(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected PageLayout queryInit() {
		_query(query);
		return (PageLayout)this;
	}

	//////////////////
	// promiseAfter //
	//////////////////

	/**	 The entity promiseAfter
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected Void promiseAfter;

	/**	<br/> The entity promiseAfter
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:promiseAfter">Find the entity promiseAfter in Solr</a>
	 * <br/>
	 * @param promise is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _promiseAfter(Promise<Void> promise);

	public Void getPromiseAfter() {
		return promiseAfter;
	}

	public void setPromiseAfter(Void promiseAfter) {
		this.promiseAfter = promiseAfter;
	}
	public static Void staticSetPromiseAfter(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected Future<Void> promiseAfterPromise() {
		Promise<Void> promise = Promise.promise();
		Promise<Void> promise2 = Promise.promise();
		_promiseAfter(promise2);
		promise2.future().onSuccess(o -> {
			setPromiseAfter(o);
			promise.complete(o);
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	//////////////////
	// pageImageUri //
	//////////////////

	/**	 The entity pageImageUri
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String pageImageUri;

	/**	<br/> The entity pageImageUri
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pageImageUri">Find the entity pageImageUri in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _pageImageUri(Wrap<String> c);

	public String getPageImageUri() {
		return pageImageUri;
	}
	public void setPageImageUri(String o) {
		this.pageImageUri = PageLayout.staticSetPageImageUri(siteRequest_, o);
	}
	public static String staticSetPageImageUri(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout pageImageUriInit() {
		Wrap<String> pageImageUriWrap = new Wrap<String>().var("pageImageUri");
		if(pageImageUri == null) {
			_pageImageUri(pageImageUriWrap);
			setPageImageUri(pageImageUriWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrPageImageUri(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrPageImageUri(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPageImageUri(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrPageImageUri(siteRequest_, PageLayout.staticSolrPageImageUri(siteRequest_, PageLayout.staticSetPageImageUri(siteRequest_, o)));
	}

	//////////////////////
	// contextIconGroup //
	//////////////////////

	/**	 The entity contextIconGroup
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String contextIconGroup;

	/**	<br/> The entity contextIconGroup
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:contextIconGroup">Find the entity contextIconGroup in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _contextIconGroup(Wrap<String> c);

	public String getContextIconGroup() {
		return contextIconGroup;
	}
	public void setContextIconGroup(String o) {
		this.contextIconGroup = PageLayout.staticSetContextIconGroup(siteRequest_, o);
	}
	public static String staticSetContextIconGroup(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout contextIconGroupInit() {
		Wrap<String> contextIconGroupWrap = new Wrap<String>().var("contextIconGroup");
		if(contextIconGroup == null) {
			_contextIconGroup(contextIconGroupWrap);
			setContextIconGroup(contextIconGroupWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrContextIconGroup(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrContextIconGroup(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqContextIconGroup(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrContextIconGroup(siteRequest_, PageLayout.staticSolrContextIconGroup(siteRequest_, PageLayout.staticSetContextIconGroup(siteRequest_, o)));
	}

	/////////////////////
	// contextIconName //
	/////////////////////

	/**	 The entity contextIconName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String contextIconName;

	/**	<br/> The entity contextIconName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.page.PageLayout&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:contextIconName">Find the entity contextIconName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _contextIconName(Wrap<String> c);

	public String getContextIconName() {
		return contextIconName;
	}
	public void setContextIconName(String o) {
		this.contextIconName = PageLayout.staticSetContextIconName(siteRequest_, o);
	}
	public static String staticSetContextIconName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected PageLayout contextIconNameInit() {
		Wrap<String> contextIconNameWrap = new Wrap<String>().var("contextIconName");
		if(contextIconName == null) {
			_contextIconName(contextIconNameWrap);
			setContextIconName(contextIconNameWrap.o);
		}
		return (PageLayout)this;
	}

	public static String staticSolrContextIconName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrContextIconName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqContextIconName(SiteRequestEnUS siteRequest_, String o) {
		return PageLayout.staticSolrStrContextIconName(siteRequest_, PageLayout.staticSolrContextIconName(siteRequest_, PageLayout.staticSetContextIconName(siteRequest_, o)));
	}

	//////////////
	// initDeep //
	//////////////

	public Future<Void> promiseDeepPageLayout(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		return promiseDeepPageLayout();
	}

	public Future<Void> promiseDeepPageLayout() {
		Promise<Void> promise = Promise.promise();
		Promise<Void> promise2 = Promise.promise();
		promisePageLayout(promise2);
		promise2.future().onSuccess(a -> {
			promise.complete();
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	public Future<Void> promisePageLayout(Promise<Void> promise) {
		Future.future(a -> a.complete()).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				siteRequest_Init();
				serviceRequestInit();
				requestZoneIdInit();
				requestLocaleIdInit();
				staticBaseUrlInit();
				authUrlInit();
				authRealmInit();
				pageUriInit();
				pageMethodInit();
				paramsInit();
				userKeyInit();
				userFullNameInit();
				userNameInit();
				userEmailInit();
				logoutUrlInit();
				long0Init();
				long1Init();
				int0Init();
				int1Init();
				promise2.complete();
			} catch(Exception ex) {
				promise2.fail(ex);
			}
			return promise2.future();
		}).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			promiseBeforePromise().onSuccess(promiseBefore -> {
				promise2.complete();
			}).onFailure(ex -> {
				promise2.fail(ex);
			});
			return promise2.future();
		}).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				classSimpleNameInit();
				pageTitleInit();
				rolesInit();
				rolesRequiredInit();
				authRolesAdminInit();
				paginationInit();
				queryInit();
				promise2.complete();
			} catch(Exception ex) {
				promise2.fail(ex);
			}
			return promise2.future();
		}).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			promiseAfterPromise().onSuccess(promiseAfter -> {
				promise2.complete();
			}).onFailure(ex -> {
				promise2.fail(ex);
			});
			return promise2.future();
		}).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				pageImageUriInit();
				contextIconGroupInit();
				contextIconNameInit();
				promise2.complete();
			} catch(Exception ex) {
				promise2.fail(ex);
			}
			return promise2.future();
		}).onSuccess(a -> {
			promise.complete();
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	public Future<Void> promiseDeepForClass(SiteRequestEnUS siteRequest_) {
		return promiseDeepPageLayout(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestPageLayout(SiteRequestEnUS siteRequest_) {
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestPageLayout(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainPageLayout(v);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.obtainForClass(v);
			}
			else if(o instanceof Map) {
				Map<?, ?> map = (Map<?, ?>)o;
				o = map.get(v);
			}
		}
		return o;
	}
	public Object obtainPageLayout(String var) {
		PageLayout oPageLayout = (PageLayout)this;
		switch(var) {
			case "siteRequest_":
				return oPageLayout.siteRequest_;
			case "serviceRequest":
				return oPageLayout.serviceRequest;
			case "requestZoneId":
				return oPageLayout.requestZoneId;
			case "requestLocaleId":
				return oPageLayout.requestLocaleId;
			case "staticBaseUrl":
				return oPageLayout.staticBaseUrl;
			case "authUrl":
				return oPageLayout.authUrl;
			case "authRealm":
				return oPageLayout.authRealm;
			case "pageUri":
				return oPageLayout.pageUri;
			case "pageMethod":
				return oPageLayout.pageMethod;
			case "params":
				return oPageLayout.params;
			case "userKey":
				return oPageLayout.userKey;
			case "userFullName":
				return oPageLayout.userFullName;
			case "userName":
				return oPageLayout.userName;
			case "userEmail":
				return oPageLayout.userEmail;
			case "logoutUrl":
				return oPageLayout.logoutUrl;
			case "long0":
				return oPageLayout.long0;
			case "long1":
				return oPageLayout.long1;
			case "int0":
				return oPageLayout.int0;
			case "int1":
				return oPageLayout.int1;
			case "promiseBefore":
				return oPageLayout.promiseBefore;
			case "classSimpleName":
				return oPageLayout.classSimpleName;
			case "pageTitle":
				return oPageLayout.pageTitle;
			case "roles":
				return oPageLayout.roles;
			case "rolesRequired":
				return oPageLayout.rolesRequired;
			case "authRolesAdmin":
				return oPageLayout.authRolesAdmin;
			case "pagination":
				return oPageLayout.pagination;
			case "query":
				return oPageLayout.query;
			case "promiseAfter":
				return oPageLayout.promiseAfter;
			case "pageImageUri":
				return oPageLayout.pageImageUri;
			case "contextIconGroup":
				return oPageLayout.contextIconGroup;
			case "contextIconName":
				return oPageLayout.contextIconName;
			default:
				return null;
		}
	}

	///////////////
	// relate //
	///////////////

	public boolean relateForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = relatePageLayout(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relatePageLayout(String var, Object val) {
		PageLayout oPageLayout = (PageLayout)this;
		switch(var) {
			default:
				return null;
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetPageLayout(entityVar,  siteRequest_, o);
	}
	public static Object staticSetPageLayout(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "requestZoneId":
			return PageLayout.staticSetRequestZoneId(siteRequest_, o);
		case "requestLocaleId":
			return PageLayout.staticSetRequestLocaleId(siteRequest_, o);
		case "staticBaseUrl":
			return PageLayout.staticSetStaticBaseUrl(siteRequest_, o);
		case "authUrl":
			return PageLayout.staticSetAuthUrl(siteRequest_, o);
		case "authRealm":
			return PageLayout.staticSetAuthRealm(siteRequest_, o);
		case "pageUri":
			return PageLayout.staticSetPageUri(siteRequest_, o);
		case "pageMethod":
			return PageLayout.staticSetPageMethod(siteRequest_, o);
		case "userKey":
			return PageLayout.staticSetUserKey(siteRequest_, o);
		case "userFullName":
			return PageLayout.staticSetUserFullName(siteRequest_, o);
		case "userName":
			return PageLayout.staticSetUserName(siteRequest_, o);
		case "userEmail":
			return PageLayout.staticSetUserEmail(siteRequest_, o);
		case "logoutUrl":
			return PageLayout.staticSetLogoutUrl(siteRequest_, o);
		case "long0":
			return PageLayout.staticSetLong0(siteRequest_, o);
		case "long1":
			return PageLayout.staticSetLong1(siteRequest_, o);
		case "int0":
			return PageLayout.staticSetInt0(siteRequest_, o);
		case "int1":
			return PageLayout.staticSetInt1(siteRequest_, o);
		case "classSimpleName":
			return PageLayout.staticSetClassSimpleName(siteRequest_, o);
		case "pageTitle":
			return PageLayout.staticSetPageTitle(siteRequest_, o);
		case "roles":
			return PageLayout.staticSetRoles(siteRequest_, o);
		case "rolesRequired":
			return PageLayout.staticSetRolesRequired(siteRequest_, o);
		case "authRolesAdmin":
			return PageLayout.staticSetAuthRolesAdmin(siteRequest_, o);
		case "pageImageUri":
			return PageLayout.staticSetPageImageUri(siteRequest_, o);
		case "contextIconGroup":
			return PageLayout.staticSetContextIconGroup(siteRequest_, o);
		case "contextIconName":
			return PageLayout.staticSetContextIconName(siteRequest_, o);
			default:
				return null;
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrPageLayout(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrPageLayout(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "requestZoneId":
			return PageLayout.staticSolrRequestZoneId(siteRequest_, (String)o);
		case "requestLocaleId":
			return PageLayout.staticSolrRequestLocaleId(siteRequest_, (String)o);
		case "staticBaseUrl":
			return PageLayout.staticSolrStaticBaseUrl(siteRequest_, (String)o);
		case "authUrl":
			return PageLayout.staticSolrAuthUrl(siteRequest_, (String)o);
		case "authRealm":
			return PageLayout.staticSolrAuthRealm(siteRequest_, (String)o);
		case "pageUri":
			return PageLayout.staticSolrPageUri(siteRequest_, (String)o);
		case "pageMethod":
			return PageLayout.staticSolrPageMethod(siteRequest_, (String)o);
		case "userKey":
			return PageLayout.staticSolrUserKey(siteRequest_, (Long)o);
		case "userFullName":
			return PageLayout.staticSolrUserFullName(siteRequest_, (String)o);
		case "userName":
			return PageLayout.staticSolrUserName(siteRequest_, (String)o);
		case "userEmail":
			return PageLayout.staticSolrUserEmail(siteRequest_, (String)o);
		case "logoutUrl":
			return PageLayout.staticSolrLogoutUrl(siteRequest_, (String)o);
		case "long0":
			return PageLayout.staticSolrLong0(siteRequest_, (Long)o);
		case "long1":
			return PageLayout.staticSolrLong1(siteRequest_, (Long)o);
		case "int0":
			return PageLayout.staticSolrInt0(siteRequest_, (Integer)o);
		case "int1":
			return PageLayout.staticSolrInt1(siteRequest_, (Integer)o);
		case "classSimpleName":
			return PageLayout.staticSolrClassSimpleName(siteRequest_, (String)o);
		case "pageTitle":
			return PageLayout.staticSolrPageTitle(siteRequest_, (String)o);
		case "roles":
			return PageLayout.staticSolrRoles(siteRequest_, (String)o);
		case "rolesRequired":
			return PageLayout.staticSolrRolesRequired(siteRequest_, (String)o);
		case "authRolesAdmin":
			return PageLayout.staticSolrAuthRolesAdmin(siteRequest_, (String)o);
		case "pageImageUri":
			return PageLayout.staticSolrPageImageUri(siteRequest_, (String)o);
		case "contextIconGroup":
			return PageLayout.staticSolrContextIconGroup(siteRequest_, (String)o);
		case "contextIconName":
			return PageLayout.staticSolrContextIconName(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrPageLayout(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrPageLayout(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "requestZoneId":
			return PageLayout.staticSolrStrRequestZoneId(siteRequest_, (String)o);
		case "requestLocaleId":
			return PageLayout.staticSolrStrRequestLocaleId(siteRequest_, (String)o);
		case "staticBaseUrl":
			return PageLayout.staticSolrStrStaticBaseUrl(siteRequest_, (String)o);
		case "authUrl":
			return PageLayout.staticSolrStrAuthUrl(siteRequest_, (String)o);
		case "authRealm":
			return PageLayout.staticSolrStrAuthRealm(siteRequest_, (String)o);
		case "pageUri":
			return PageLayout.staticSolrStrPageUri(siteRequest_, (String)o);
		case "pageMethod":
			return PageLayout.staticSolrStrPageMethod(siteRequest_, (String)o);
		case "userKey":
			return PageLayout.staticSolrStrUserKey(siteRequest_, (Long)o);
		case "userFullName":
			return PageLayout.staticSolrStrUserFullName(siteRequest_, (String)o);
		case "userName":
			return PageLayout.staticSolrStrUserName(siteRequest_, (String)o);
		case "userEmail":
			return PageLayout.staticSolrStrUserEmail(siteRequest_, (String)o);
		case "logoutUrl":
			return PageLayout.staticSolrStrLogoutUrl(siteRequest_, (String)o);
		case "long0":
			return PageLayout.staticSolrStrLong0(siteRequest_, (Long)o);
		case "long1":
			return PageLayout.staticSolrStrLong1(siteRequest_, (Long)o);
		case "int0":
			return PageLayout.staticSolrStrInt0(siteRequest_, (Integer)o);
		case "int1":
			return PageLayout.staticSolrStrInt1(siteRequest_, (Integer)o);
		case "classSimpleName":
			return PageLayout.staticSolrStrClassSimpleName(siteRequest_, (String)o);
		case "pageTitle":
			return PageLayout.staticSolrStrPageTitle(siteRequest_, (String)o);
		case "roles":
			return PageLayout.staticSolrStrRoles(siteRequest_, (String)o);
		case "rolesRequired":
			return PageLayout.staticSolrStrRolesRequired(siteRequest_, (String)o);
		case "authRolesAdmin":
			return PageLayout.staticSolrStrAuthRolesAdmin(siteRequest_, (String)o);
		case "pageImageUri":
			return PageLayout.staticSolrStrPageImageUri(siteRequest_, (String)o);
		case "contextIconGroup":
			return PageLayout.staticSolrStrContextIconGroup(siteRequest_, (String)o);
		case "contextIconName":
			return PageLayout.staticSolrStrContextIconName(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqPageLayout(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqPageLayout(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "requestZoneId":
			return PageLayout.staticSolrFqRequestZoneId(siteRequest_, o);
		case "requestLocaleId":
			return PageLayout.staticSolrFqRequestLocaleId(siteRequest_, o);
		case "staticBaseUrl":
			return PageLayout.staticSolrFqStaticBaseUrl(siteRequest_, o);
		case "authUrl":
			return PageLayout.staticSolrFqAuthUrl(siteRequest_, o);
		case "authRealm":
			return PageLayout.staticSolrFqAuthRealm(siteRequest_, o);
		case "pageUri":
			return PageLayout.staticSolrFqPageUri(siteRequest_, o);
		case "pageMethod":
			return PageLayout.staticSolrFqPageMethod(siteRequest_, o);
		case "userKey":
			return PageLayout.staticSolrFqUserKey(siteRequest_, o);
		case "userFullName":
			return PageLayout.staticSolrFqUserFullName(siteRequest_, o);
		case "userName":
			return PageLayout.staticSolrFqUserName(siteRequest_, o);
		case "userEmail":
			return PageLayout.staticSolrFqUserEmail(siteRequest_, o);
		case "logoutUrl":
			return PageLayout.staticSolrFqLogoutUrl(siteRequest_, o);
		case "long0":
			return PageLayout.staticSolrFqLong0(siteRequest_, o);
		case "long1":
			return PageLayout.staticSolrFqLong1(siteRequest_, o);
		case "int0":
			return PageLayout.staticSolrFqInt0(siteRequest_, o);
		case "int1":
			return PageLayout.staticSolrFqInt1(siteRequest_, o);
		case "classSimpleName":
			return PageLayout.staticSolrFqClassSimpleName(siteRequest_, o);
		case "pageTitle":
			return PageLayout.staticSolrFqPageTitle(siteRequest_, o);
		case "roles":
			return PageLayout.staticSolrFqRoles(siteRequest_, o);
		case "rolesRequired":
			return PageLayout.staticSolrFqRolesRequired(siteRequest_, o);
		case "authRolesAdmin":
			return PageLayout.staticSolrFqAuthRolesAdmin(siteRequest_, o);
		case "pageImageUri":
			return PageLayout.staticSolrFqPageImageUri(siteRequest_, o);
		case "contextIconGroup":
			return PageLayout.staticSolrFqContextIconGroup(siteRequest_, o);
		case "contextIconName":
			return PageLayout.staticSolrFqContextIconName(siteRequest_, o);
			default:
				return null;
		}
	}

	/////////////
	// define //
	/////////////

	public boolean defineForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		if(val != null) {
			for(String v : vars) {
				if(o == null)
					o = definePageLayout(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object definePageLayout(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestPageLayout() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof PageLayout) {
			PageLayout original = (PageLayout)o;
		}
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		return sb.toString();
	}

	public static final String VAR_siteRequest_ = "siteRequest_";
	public static final String VAR_serviceRequest = "serviceRequest";
	public static final String VAR_requestZoneId = "requestZoneId";
	public static final String VAR_requestLocaleId = "requestLocaleId";
	public static final String VAR_staticBaseUrl = "staticBaseUrl";
	public static final String VAR_authUrl = "authUrl";
	public static final String VAR_authRealm = "authRealm";
	public static final String VAR_pageUri = "pageUri";
	public static final String VAR_pageMethod = "pageMethod";
	public static final String VAR_params = "params";
	public static final String VAR_userKey = "userKey";
	public static final String VAR_userFullName = "userFullName";
	public static final String VAR_userName = "userName";
	public static final String VAR_userEmail = "userEmail";
	public static final String VAR_logoutUrl = "logoutUrl";
	public static final String VAR_long0 = "long0";
	public static final String VAR_long1 = "long1";
	public static final String VAR_int0 = "int0";
	public static final String VAR_int1 = "int1";
	public static final String VAR_promiseBefore = "promiseBefore";
	public static final String VAR_classSimpleName = "classSimpleName";
	public static final String VAR_pageTitle = "pageTitle";
	public static final String VAR_roles = "roles";
	public static final String VAR_rolesRequired = "rolesRequired";
	public static final String VAR_authRolesAdmin = "authRolesAdmin";
	public static final String VAR_pagination = "pagination";
	public static final String VAR_query = "query";
	public static final String VAR_promiseAfter = "promiseAfter";
	public static final String VAR_pageImageUri = "pageImageUri";
	public static final String VAR_contextIconGroup = "contextIconGroup";
	public static final String VAR_contextIconName = "contextIconName";
}
