package org.curriki.api.enus.request;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.Arrays;
import com.fasterxml.jackson.databind.ser.std.ToStringSerializer;
import org.curriki.api.enus.base.BaseModel;
import io.vertx.ext.web.client.WebClient;
import org.curriki.api.enus.request.api.ApiRequest;
import org.slf4j.LoggerFactory;
import io.vertx.core.MultiMap;
import java.util.HashMap;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.apache.commons.lang3.StringUtils;
import java.text.NumberFormat;
import java.util.ArrayList;
import org.curriki.api.enus.wrap.Wrap;
import org.curriki.api.enus.java.ZonedDateTimeDeserializer;
import io.vertx.sqlclient.SqlConnection;
import org.apache.commons.collections.CollectionUtils;
import java.lang.Long;
import com.fasterxml.jackson.databind.annotation.JsonSerialize;
import java.util.Map;
import com.fasterxml.jackson.annotation.JsonIgnore;
import java.lang.Boolean;
import io.vertx.core.json.JsonObject;
import org.curriki.api.enus.java.ZonedDateTimeSerializer;
import java.lang.String;
import java.math.RoundingMode;
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
import org.apache.solr.common.SolrDocument;
import java.util.List;
import io.vertx.ext.auth.User;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import java.lang.Object;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * Map.hackathonLabels: Java
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class SiteRequestEnUSGen<DEV> extends Object {
	protected static final Logger LOG = LoggerFactory.getLogger(SiteRequestEnUS.class);

	////////////
	// config //
	////////////

	/**	 The entity config
	 *	 is defined as null before being initialized. 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonObject config;

	/**	<br/> The entity config
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:config">Find the entity config in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _config(Wrap<JsonObject> c);

	public JsonObject getConfig() {
		return config;
	}

	public void setConfig(JsonObject config) {
		this.config = config;
	}
	public static JsonObject staticSetConfig(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS configInit() {
		Wrap<JsonObject> configWrap = new Wrap<JsonObject>().var("config");
		if(config == null) {
			_config(configWrap);
			setConfig(configWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	//////////////////
	// siteRequest_ //
	//////////////////

	/**	 The entity siteRequest_
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SiteRequestEnUS siteRequest_;

	/**	<br/> The entity siteRequest_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteRequest_">Find the entity siteRequest_ in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _siteRequest_(Wrap<SiteRequestEnUS> c);

	public SiteRequestEnUS getSiteRequest_() {
		return siteRequest_;
	}

	public void setSiteRequest_(SiteRequestEnUS siteRequest_) {
		this.siteRequest_ = siteRequest_;
	}
	public static SiteRequestEnUS staticSetSiteRequest_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS siteRequest_Init() {
		Wrap<SiteRequestEnUS> siteRequest_Wrap = new Wrap<SiteRequestEnUS>().var("siteRequest_");
		if(siteRequest_ == null) {
			_siteRequest_(siteRequest_Wrap);
			setSiteRequest_(siteRequest_Wrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	///////////////
	// webClient //
	///////////////

	/**	 The entity webClient
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected WebClient webClient;

	/**	<br/> The entity webClient
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:webClient">Find the entity webClient in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _webClient(Wrap<WebClient> c);

	public WebClient getWebClient() {
		return webClient;
	}

	public void setWebClient(WebClient webClient) {
		this.webClient = webClient;
	}
	public static WebClient staticSetWebClient(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS webClientInit() {
		Wrap<WebClient> webClientWrap = new Wrap<WebClient>().var("webClient");
		if(webClient == null) {
			_webClient(webClientWrap);
			setWebClient(webClientWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	/////////////////
	// apiRequest_ //
	/////////////////

	/**	 The entity apiRequest_
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected ApiRequest apiRequest_;

	/**	<br/> The entity apiRequest_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:apiRequest_">Find the entity apiRequest_ in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _apiRequest_(Wrap<ApiRequest> c);

	public ApiRequest getApiRequest_() {
		return apiRequest_;
	}

	public void setApiRequest_(ApiRequest apiRequest_) {
		this.apiRequest_ = apiRequest_;
	}
	public static ApiRequest staticSetApiRequest_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS apiRequest_Init() {
		Wrap<ApiRequest> apiRequest_Wrap = new Wrap<ApiRequest>().var("apiRequest_");
		if(apiRequest_ == null) {
			_apiRequest_(apiRequest_Wrap);
			setApiRequest_(apiRequest_Wrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	////////////////
	// jsonObject //
	////////////////

	/**	 The entity jsonObject
	 *	 is defined as null before being initialized. 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonObject jsonObject;

	/**	<br/> The entity jsonObject
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:jsonObject">Find the entity jsonObject in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _jsonObject(Wrap<JsonObject> c);

	public JsonObject getJsonObject() {
		return jsonObject;
	}

	public void setJsonObject(JsonObject jsonObject) {
		this.jsonObject = jsonObject;
	}
	public static JsonObject staticSetJsonObject(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS jsonObjectInit() {
		Wrap<JsonObject> jsonObjectWrap = new Wrap<JsonObject>().var("jsonObject");
		if(jsonObject == null) {
			_jsonObject(jsonObjectWrap);
			setJsonObject(jsonObjectWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	///////////////
	// solrQuery //
	///////////////

	/**	 The entity solrQuery
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SolrQuery solrQuery;

	/**	<br/> The entity solrQuery
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:solrQuery">Find the entity solrQuery in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _solrQuery(Wrap<SolrQuery> c);

	public SolrQuery getSolrQuery() {
		return solrQuery;
	}

	public void setSolrQuery(SolrQuery solrQuery) {
		this.solrQuery = solrQuery;
	}
	public static SolrQuery staticSetSolrQuery(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS solrQueryInit() {
		Wrap<SolrQuery> solrQueryWrap = new Wrap<SolrQuery>().var("solrQuery");
		if(solrQuery == null) {
			_solrQuery(solrQueryWrap);
			setSolrQuery(solrQueryWrap.o);
		}
		return (SiteRequestEnUS)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:serviceRequest">Find the entity serviceRequest in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _serviceRequest(Wrap<ServiceRequest> c);

	public ServiceRequest getServiceRequest() {
		return serviceRequest;
	}

	public void setServiceRequest(ServiceRequest serviceRequest) {
		this.serviceRequest = serviceRequest;
	}
	public static ServiceRequest staticSetServiceRequest(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS serviceRequestInit() {
		Wrap<ServiceRequest> serviceRequestWrap = new Wrap<ServiceRequest>().var("serviceRequest");
		if(serviceRequest == null) {
			_serviceRequest(serviceRequestWrap);
			setServiceRequest(serviceRequestWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	//////////
	// user //
	//////////

	/**	 The entity user
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected User user;

	/**	<br/> The entity user
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:user">Find the entity user in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _user(Wrap<User> c);

	public User getUser() {
		return user;
	}

	public void setUser(User user) {
		this.user = user;
	}
	public static User staticSetUser(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS userInit() {
		Wrap<User> userWrap = new Wrap<User>().var("user");
		if(user == null) {
			_user(userWrap);
			setUser(userWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	////////////
	// userId //
	////////////

	/**	 The entity userId
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String userId;

	/**	<br/> The entity userId
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userId">Find the entity userId in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userId(Wrap<String> c);

	public String getUserId() {
		return userId;
	}
	public void setUserId(String o) {
		this.userId = SiteRequestEnUS.staticSetUserId(siteRequest_, o);
	}
	public static String staticSetUserId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS userIdInit() {
		Wrap<String> userIdWrap = new Wrap<String>().var("userId");
		if(userId == null) {
			_userId(userIdWrap);
			setUserId(userIdWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserId(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserId(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserId(siteRequest_, SiteRequestEnUS.staticSolrUserId(siteRequest_, SiteRequestEnUS.staticSetUserId(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userKey">Find the entity userKey in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userKey(Wrap<Long> c);

	public Long getUserKey() {
		return userKey;
	}

	public void setUserKey(Long userKey) {
		this.userKey = userKey;
	}
	@JsonIgnore
	public void setUserKey(String o) {
		this.userKey = SiteRequestEnUS.staticSetUserKey(siteRequest_, o);
	}
	public static Long staticSetUserKey(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	protected SiteRequestEnUS userKeyInit() {
		Wrap<Long> userKeyWrap = new Wrap<Long>().var("userKey");
		if(userKey == null) {
			_userKey(userKeyWrap);
			setUserKey(userKeyWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static Long staticSolrUserKey(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrUserKey(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserKey(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserKey(siteRequest_, SiteRequestEnUS.staticSolrUserKey(siteRequest_, SiteRequestEnUS.staticSetUserKey(siteRequest_, o)));
	}

	///////////////
	// sessionId //
	///////////////

	/**	 The entity sessionId
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String sessionId;

	/**	<br/> The entity sessionId
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:sessionId">Find the entity sessionId in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _sessionId(Wrap<String> c);

	public String getSessionId() {
		return sessionId;
	}
	public void setSessionId(String o) {
		this.sessionId = SiteRequestEnUS.staticSetSessionId(siteRequest_, o);
	}
	public static String staticSetSessionId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS sessionIdInit() {
		Wrap<String> sessionIdWrap = new Wrap<String>().var("sessionId");
		if(sessionId == null) {
			_sessionId(sessionIdWrap);
			setSessionId(sessionIdWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrSessionId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrSessionId(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqSessionId(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrSessionId(siteRequest_, SiteRequestEnUS.staticSolrSessionId(siteRequest_, SiteRequestEnUS.staticSetSessionId(siteRequest_, o)));
	}

	/////////////////////
	// sessionIdBefore //
	/////////////////////

	/**	 The entity sessionIdBefore
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String sessionIdBefore;

	/**	<br/> The entity sessionIdBefore
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:sessionIdBefore">Find the entity sessionIdBefore in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _sessionIdBefore(Wrap<String> c);

	public String getSessionIdBefore() {
		return sessionIdBefore;
	}
	public void setSessionIdBefore(String o) {
		this.sessionIdBefore = SiteRequestEnUS.staticSetSessionIdBefore(siteRequest_, o);
	}
	public static String staticSetSessionIdBefore(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS sessionIdBeforeInit() {
		Wrap<String> sessionIdBeforeWrap = new Wrap<String>().var("sessionIdBefore");
		if(sessionIdBefore == null) {
			_sessionIdBefore(sessionIdBeforeWrap);
			setSessionIdBefore(sessionIdBeforeWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrSessionIdBefore(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrSessionIdBefore(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqSessionIdBefore(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrSessionIdBefore(siteRequest_, SiteRequestEnUS.staticSolrSessionIdBefore(siteRequest_, SiteRequestEnUS.staticSetSessionIdBefore(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userName">Find the entity userName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userName(Wrap<String> c);

	public String getUserName() {
		return userName;
	}
	public void setUserName(String o) {
		this.userName = SiteRequestEnUS.staticSetUserName(siteRequest_, o);
	}
	public static String staticSetUserName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS userNameInit() {
		Wrap<String> userNameWrap = new Wrap<String>().var("userName");
		if(userName == null) {
			_userName(userNameWrap);
			setUserName(userNameWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserName(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserName(siteRequest_, SiteRequestEnUS.staticSolrUserName(siteRequest_, SiteRequestEnUS.staticSetUserName(siteRequest_, o)));
	}

	//////////////////
	// userLastName //
	//////////////////

	/**	 The entity userLastName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String userLastName;

	/**	<br/> The entity userLastName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userLastName">Find the entity userLastName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userLastName(Wrap<String> c);

	public String getUserLastName() {
		return userLastName;
	}
	public void setUserLastName(String o) {
		this.userLastName = SiteRequestEnUS.staticSetUserLastName(siteRequest_, o);
	}
	public static String staticSetUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS userLastNameInit() {
		Wrap<String> userLastNameWrap = new Wrap<String>().var("userLastName");
		if(userLastName == null) {
			_userLastName(userLastNameWrap);
			setUserLastName(userLastNameWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserLastName(siteRequest_, SiteRequestEnUS.staticSolrUserLastName(siteRequest_, SiteRequestEnUS.staticSetUserLastName(siteRequest_, o)));
	}

	///////////////////
	// userFirstName //
	///////////////////

	/**	 The entity userFirstName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String userFirstName;

	/**	<br/> The entity userFirstName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userFirstName">Find the entity userFirstName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userFirstName(Wrap<String> c);

	public String getUserFirstName() {
		return userFirstName;
	}
	public void setUserFirstName(String o) {
		this.userFirstName = SiteRequestEnUS.staticSetUserFirstName(siteRequest_, o);
	}
	public static String staticSetUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS userFirstNameInit() {
		Wrap<String> userFirstNameWrap = new Wrap<String>().var("userFirstName");
		if(userFirstName == null) {
			_userFirstName(userFirstNameWrap);
			setUserFirstName(userFirstNameWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserFirstName(siteRequest_, SiteRequestEnUS.staticSolrUserFirstName(siteRequest_, SiteRequestEnUS.staticSetUserFirstName(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userFullName">Find the entity userFullName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userFullName(Wrap<String> c);

	public String getUserFullName() {
		return userFullName;
	}
	public void setUserFullName(String o) {
		this.userFullName = SiteRequestEnUS.staticSetUserFullName(siteRequest_, o);
	}
	public static String staticSetUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS userFullNameInit() {
		Wrap<String> userFullNameWrap = new Wrap<String>().var("userFullName");
		if(userFullName == null) {
			_userFullName(userFullNameWrap);
			setUserFullName(userFullNameWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserFullName(siteRequest_, SiteRequestEnUS.staticSolrUserFullName(siteRequest_, SiteRequestEnUS.staticSetUserFullName(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userEmail">Find the entity userEmail in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userEmail(Wrap<String> c);

	public String getUserEmail() {
		return userEmail;
	}
	public void setUserEmail(String o) {
		this.userEmail = SiteRequestEnUS.staticSetUserEmail(siteRequest_, o);
	}
	public static String staticSetUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS userEmailInit() {
		Wrap<String> userEmailWrap = new Wrap<String>().var("userEmail");
		if(userEmail == null) {
			_userEmail(userEmailWrap);
			setUserEmail(userEmailWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserEmail(siteRequest_, SiteRequestEnUS.staticSolrUserEmail(siteRequest_, SiteRequestEnUS.staticSetUserEmail(siteRequest_, o)));
	}

	////////////////////
	// userRealmRoles //
	////////////////////

	/**	 The entity userRealmRoles
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<String>(). 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> userRealmRoles = new ArrayList<String>();

	/**	<br/> The entity userRealmRoles
	 *  It is constructed before being initialized with the constructor by default List<String>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userRealmRoles">Find the entity userRealmRoles in Solr</a>
	 * <br/>
	 * @param userRealmRoles is the entity already constructed. 
	 **/
	protected abstract void _userRealmRoles(List<String> o);

	public List<String> getUserRealmRoles() {
		return userRealmRoles;
	}

	public void setUserRealmRoles(List<String> userRealmRoles) {
		this.userRealmRoles = userRealmRoles;
	}
	public static String staticSetUserRealmRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public SiteRequestEnUS addUserRealmRoles(String...objets) {
		for(String o : objets) {
			addUserRealmRoles(o);
		}
		return (SiteRequestEnUS)this;
	}
	public SiteRequestEnUS addUserRealmRoles(String o) {
		if(o != null)
			this.userRealmRoles.add(o);
		return (SiteRequestEnUS)this;
	}
	@JsonIgnore
	public void setUserRealmRoles(JsonArray objets) {
		userRealmRoles.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addUserRealmRoles(o);
		}
	}
	protected SiteRequestEnUS userRealmRolesInit() {
		_userRealmRoles(userRealmRoles);
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserRealmRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserRealmRoles(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserRealmRoles(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserRealmRoles(siteRequest_, SiteRequestEnUS.staticSolrUserRealmRoles(siteRequest_, SiteRequestEnUS.staticSetUserRealmRoles(siteRequest_, o)));
	}

	//////////////////
	// userResource //
	//////////////////

	/**	 The entity userResource
	 *	 is defined as null before being initialized. 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonObject userResource;

	/**	<br/> The entity userResource
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userResource">Find the entity userResource in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userResource(Wrap<JsonObject> c);

	public JsonObject getUserResource() {
		return userResource;
	}

	public void setUserResource(JsonObject userResource) {
		this.userResource = userResource;
	}
	public static JsonObject staticSetUserResource(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS userResourceInit() {
		Wrap<JsonObject> userResourceWrap = new Wrap<JsonObject>().var("userResource");
		if(userResource == null) {
			_userResource(userResourceWrap);
			setUserResource(userResourceWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	///////////////////////
	// userResourceRoles //
	///////////////////////

	/**	 The entity userResourceRoles
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<String>(). 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> userResourceRoles = new ArrayList<String>();

	/**	<br/> The entity userResourceRoles
	 *  It is constructed before being initialized with the constructor by default List<String>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userResourceRoles">Find the entity userResourceRoles in Solr</a>
	 * <br/>
	 * @param userResourceRoles is the entity already constructed. 
	 **/
	protected abstract void _userResourceRoles(List<String> o);

	public List<String> getUserResourceRoles() {
		return userResourceRoles;
	}

	public void setUserResourceRoles(List<String> userResourceRoles) {
		this.userResourceRoles = userResourceRoles;
	}
	public static String staticSetUserResourceRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public SiteRequestEnUS addUserResourceRoles(String...objets) {
		for(String o : objets) {
			addUserResourceRoles(o);
		}
		return (SiteRequestEnUS)this;
	}
	public SiteRequestEnUS addUserResourceRoles(String o) {
		if(o != null)
			this.userResourceRoles.add(o);
		return (SiteRequestEnUS)this;
	}
	@JsonIgnore
	public void setUserResourceRoles(JsonArray objets) {
		userResourceRoles.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addUserResourceRoles(o);
		}
	}
	protected SiteRequestEnUS userResourceRolesInit() {
		_userResourceRoles(userResourceRoles);
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrUserResourceRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserResourceRoles(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserResourceRoles(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrUserResourceRoles(siteRequest_, SiteRequestEnUS.staticSolrUserResourceRoles(siteRequest_, SiteRequestEnUS.staticSetUserResourceRoles(siteRequest_, o)));
	}

	//////////////////
	// solrDocument //
	//////////////////

	/**	 The entity solrDocument
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SolrDocument solrDocument;

	/**	<br/> The entity solrDocument
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:solrDocument">Find the entity solrDocument in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _solrDocument(Wrap<SolrDocument> c);

	public SolrDocument getSolrDocument() {
		return solrDocument;
	}

	public void setSolrDocument(SolrDocument solrDocument) {
		this.solrDocument = solrDocument;
	}
	public static SolrDocument staticSetSolrDocument(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS solrDocumentInit() {
		Wrap<SolrDocument> solrDocumentWrap = new Wrap<SolrDocument>().var("solrDocument");
		if(solrDocument == null) {
			_solrDocument(solrDocumentWrap);
			setSolrDocument(solrDocumentWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	///////////////
	// pageAdmin //
	///////////////

	/**	 The entity pageAdmin
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean pageAdmin;

	/**	<br/> The entity pageAdmin
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pageAdmin">Find the entity pageAdmin in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _pageAdmin(Wrap<Boolean> c);

	public Boolean getPageAdmin() {
		return pageAdmin;
	}

	public void setPageAdmin(Boolean pageAdmin) {
		this.pageAdmin = pageAdmin;
	}
	@JsonIgnore
	public void setPageAdmin(String o) {
		this.pageAdmin = SiteRequestEnUS.staticSetPageAdmin(siteRequest_, o);
	}
	public static Boolean staticSetPageAdmin(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected SiteRequestEnUS pageAdminInit() {
		Wrap<Boolean> pageAdminWrap = new Wrap<Boolean>().var("pageAdmin");
		if(pageAdmin == null) {
			_pageAdmin(pageAdminWrap);
			setPageAdmin(pageAdminWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static Boolean staticSolrPageAdmin(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrPageAdmin(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPageAdmin(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrPageAdmin(siteRequest_, SiteRequestEnUS.staticSolrPageAdmin(siteRequest_, SiteRequestEnUS.staticSetPageAdmin(siteRequest_, o)));
	}

	///////////////
	// requestPk //
	///////////////

	/**	 The entity requestPk
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Long requestPk;

	/**	<br/> The entity requestPk
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:requestPk">Find the entity requestPk in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _requestPk(Wrap<Long> c);

	public Long getRequestPk() {
		return requestPk;
	}

	public void setRequestPk(Long requestPk) {
		this.requestPk = requestPk;
	}
	@JsonIgnore
	public void setRequestPk(String o) {
		this.requestPk = SiteRequestEnUS.staticSetRequestPk(siteRequest_, o);
	}
	public static Long staticSetRequestPk(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	protected SiteRequestEnUS requestPkInit() {
		Wrap<Long> requestPkWrap = new Wrap<Long>().var("requestPk");
		if(requestPk == null) {
			_requestPk(requestPkWrap);
			setRequestPk(requestPkWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static Long staticSolrRequestPk(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrRequestPk(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqRequestPk(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrRequestPk(siteRequest_, SiteRequestEnUS.staticSolrRequestPk(siteRequest_, SiteRequestEnUS.staticSetRequestPk(siteRequest_, o)));
	}

	////////////////
	// requestUri //
	////////////////

	/**	 The entity requestUri
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String requestUri;

	/**	<br/> The entity requestUri
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:requestUri">Find the entity requestUri in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _requestUri(Wrap<String> c);

	public String getRequestUri() {
		return requestUri;
	}
	public void setRequestUri(String o) {
		this.requestUri = SiteRequestEnUS.staticSetRequestUri(siteRequest_, o);
	}
	public static String staticSetRequestUri(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS requestUriInit() {
		Wrap<String> requestUriWrap = new Wrap<String>().var("requestUri");
		if(requestUri == null) {
			_requestUri(requestUriWrap);
			setRequestUri(requestUriWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrRequestUri(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrRequestUri(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqRequestUri(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrRequestUri(siteRequest_, SiteRequestEnUS.staticSolrRequestUri(siteRequest_, SiteRequestEnUS.staticSetRequestUri(siteRequest_, o)));
	}

	///////////////////
	// requestMethod //
	///////////////////

	/**	 The entity requestMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String requestMethod;

	/**	<br/> The entity requestMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:requestMethod">Find the entity requestMethod in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _requestMethod(Wrap<String> c);

	public String getRequestMethod() {
		return requestMethod;
	}
	public void setRequestMethod(String o) {
		this.requestMethod = SiteRequestEnUS.staticSetRequestMethod(siteRequest_, o);
	}
	public static String staticSetRequestMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteRequestEnUS requestMethodInit() {
		Wrap<String> requestMethodWrap = new Wrap<String>().var("requestMethod");
		if(requestMethod == null) {
			_requestMethod(requestMethodWrap);
			setRequestMethod(requestMethodWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	public static String staticSolrRequestMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrRequestMethod(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqRequestMethod(SiteRequestEnUS siteRequest_, String o) {
		return SiteRequestEnUS.staticSolrStrRequestMethod(siteRequest_, SiteRequestEnUS.staticSolrRequestMethod(siteRequest_, SiteRequestEnUS.staticSetRequestMethod(siteRequest_, o)));
	}

	///////////////////
	// sqlConnection //
	///////////////////

	/**	 The entity sqlConnection
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SqlConnection sqlConnection;

	/**	<br/> The entity sqlConnection
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:sqlConnection">Find the entity sqlConnection in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _sqlConnection(Wrap<SqlConnection> c);

	public SqlConnection getSqlConnection() {
		return sqlConnection;
	}

	public void setSqlConnection(SqlConnection sqlConnection) {
		this.sqlConnection = sqlConnection;
	}
	public static SqlConnection staticSetSqlConnection(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS sqlConnectionInit() {
		Wrap<SqlConnection> sqlConnectionWrap = new Wrap<SqlConnection>().var("sqlConnection");
		if(sqlConnection == null) {
			_sqlConnection(sqlConnectionWrap);
			setSqlConnection(sqlConnectionWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	////////////////////
	// requestHeaders //
	////////////////////

	/**	 The entity requestHeaders
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected MultiMap requestHeaders;

	/**	<br/> The entity requestHeaders
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:requestHeaders">Find the entity requestHeaders in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _requestHeaders(Wrap<MultiMap> c);

	public MultiMap getRequestHeaders() {
		return requestHeaders;
	}

	public void setRequestHeaders(MultiMap requestHeaders) {
		this.requestHeaders = requestHeaders;
	}
	public static MultiMap staticSetRequestHeaders(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS requestHeadersInit() {
		Wrap<MultiMap> requestHeadersWrap = new Wrap<MultiMap>().var("requestHeaders");
		if(requestHeaders == null) {
			_requestHeaders(requestHeadersWrap);
			setRequestHeaders(requestHeadersWrap.o);
		}
		return (SiteRequestEnUS)this;
	}

	/////////////////
	// requestVars //
	/////////////////

	/**	 The entity requestVars
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut Map<String, String>(). 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Map<String, String> requestVars = new HashMap<String, String>();

	/**	<br/> The entity requestVars
	 *  It is constructed before being initialized with the constructor by default Map<String, String>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.request.SiteRequestEnUS&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:requestVars">Find the entity requestVars in Solr</a>
	 * <br/>
	 * @param requestVars is the entity already constructed. 
	 **/
	protected abstract void _requestVars(Map<String, String> m);

	public Map<String, String> getRequestVars() {
		return requestVars;
	}

	public void setRequestVars(Map<String, String> requestVars) {
		this.requestVars = requestVars;
	}
	public static Map<String, String> staticSetRequestVars(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteRequestEnUS requestVarsInit() {
		_requestVars(requestVars);
		return (SiteRequestEnUS)this;
	}

	//////////////
	// initDeep //
	//////////////

	public SiteRequestEnUS initDeepSiteRequestEnUS(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		initDeepSiteRequestEnUS();
		return (SiteRequestEnUS)this;
	}

	public void initDeepSiteRequestEnUS() {
		initSiteRequestEnUS();
	}

	public void initSiteRequestEnUS() {
				configInit();
				siteRequest_Init();
				webClientInit();
				apiRequest_Init();
				jsonObjectInit();
				solrQueryInit();
				serviceRequestInit();
				userInit();
				userIdInit();
				userKeyInit();
				sessionIdInit();
				sessionIdBeforeInit();
				userNameInit();
				userLastNameInit();
				userFirstNameInit();
				userFullNameInit();
				userEmailInit();
				userRealmRolesInit();
				userResourceInit();
				userResourceRolesInit();
				solrDocumentInit();
				pageAdminInit();
				requestPkInit();
				requestUriInit();
				requestMethodInit();
				sqlConnectionInit();
				requestHeadersInit();
				requestVarsInit();
	}

	public void initDeepForClass(SiteRequestEnUS siteRequest_) {
		initDeepSiteRequestEnUS(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestSiteRequestEnUS(SiteRequestEnUS siteRequest_) {
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestSiteRequestEnUS(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainSiteRequestEnUS(v);
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
	public Object obtainSiteRequestEnUS(String var) {
		SiteRequestEnUS oSiteRequestEnUS = (SiteRequestEnUS)this;
		switch(var) {
			case "config":
				return oSiteRequestEnUS.config;
			case "siteRequest_":
				return oSiteRequestEnUS.siteRequest_;
			case "webClient":
				return oSiteRequestEnUS.webClient;
			case "apiRequest_":
				return oSiteRequestEnUS.apiRequest_;
			case "jsonObject":
				return oSiteRequestEnUS.jsonObject;
			case "solrQuery":
				return oSiteRequestEnUS.solrQuery;
			case "serviceRequest":
				return oSiteRequestEnUS.serviceRequest;
			case "user":
				return oSiteRequestEnUS.user;
			case "userId":
				return oSiteRequestEnUS.userId;
			case "userKey":
				return oSiteRequestEnUS.userKey;
			case "sessionId":
				return oSiteRequestEnUS.sessionId;
			case "sessionIdBefore":
				return oSiteRequestEnUS.sessionIdBefore;
			case "userName":
				return oSiteRequestEnUS.userName;
			case "userLastName":
				return oSiteRequestEnUS.userLastName;
			case "userFirstName":
				return oSiteRequestEnUS.userFirstName;
			case "userFullName":
				return oSiteRequestEnUS.userFullName;
			case "userEmail":
				return oSiteRequestEnUS.userEmail;
			case "userRealmRoles":
				return oSiteRequestEnUS.userRealmRoles;
			case "userResource":
				return oSiteRequestEnUS.userResource;
			case "userResourceRoles":
				return oSiteRequestEnUS.userResourceRoles;
			case "solrDocument":
				return oSiteRequestEnUS.solrDocument;
			case "pageAdmin":
				return oSiteRequestEnUS.pageAdmin;
			case "requestPk":
				return oSiteRequestEnUS.requestPk;
			case "requestUri":
				return oSiteRequestEnUS.requestUri;
			case "requestMethod":
				return oSiteRequestEnUS.requestMethod;
			case "sqlConnection":
				return oSiteRequestEnUS.sqlConnection;
			case "requestHeaders":
				return oSiteRequestEnUS.requestHeaders;
			case "requestVars":
				return oSiteRequestEnUS.requestVars;
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
				o = relateSiteRequestEnUS(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relateSiteRequestEnUS(String var, Object val) {
		SiteRequestEnUS oSiteRequestEnUS = (SiteRequestEnUS)this;
		switch(var) {
			default:
				return null;
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetSiteRequestEnUS(entityVar,  siteRequest_, o);
	}
	public static Object staticSetSiteRequestEnUS(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "userId":
			return SiteRequestEnUS.staticSetUserId(siteRequest_, o);
		case "userKey":
			return SiteRequestEnUS.staticSetUserKey(siteRequest_, o);
		case "sessionId":
			return SiteRequestEnUS.staticSetSessionId(siteRequest_, o);
		case "sessionIdBefore":
			return SiteRequestEnUS.staticSetSessionIdBefore(siteRequest_, o);
		case "userName":
			return SiteRequestEnUS.staticSetUserName(siteRequest_, o);
		case "userLastName":
			return SiteRequestEnUS.staticSetUserLastName(siteRequest_, o);
		case "userFirstName":
			return SiteRequestEnUS.staticSetUserFirstName(siteRequest_, o);
		case "userFullName":
			return SiteRequestEnUS.staticSetUserFullName(siteRequest_, o);
		case "userEmail":
			return SiteRequestEnUS.staticSetUserEmail(siteRequest_, o);
		case "userRealmRoles":
			return SiteRequestEnUS.staticSetUserRealmRoles(siteRequest_, o);
		case "userResourceRoles":
			return SiteRequestEnUS.staticSetUserResourceRoles(siteRequest_, o);
		case "pageAdmin":
			return SiteRequestEnUS.staticSetPageAdmin(siteRequest_, o);
		case "requestPk":
			return SiteRequestEnUS.staticSetRequestPk(siteRequest_, o);
		case "requestUri":
			return SiteRequestEnUS.staticSetRequestUri(siteRequest_, o);
		case "requestMethod":
			return SiteRequestEnUS.staticSetRequestMethod(siteRequest_, o);
			default:
				return null;
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrSiteRequestEnUS(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrSiteRequestEnUS(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "userId":
			return SiteRequestEnUS.staticSolrUserId(siteRequest_, (String)o);
		case "userKey":
			return SiteRequestEnUS.staticSolrUserKey(siteRequest_, (Long)o);
		case "sessionId":
			return SiteRequestEnUS.staticSolrSessionId(siteRequest_, (String)o);
		case "sessionIdBefore":
			return SiteRequestEnUS.staticSolrSessionIdBefore(siteRequest_, (String)o);
		case "userName":
			return SiteRequestEnUS.staticSolrUserName(siteRequest_, (String)o);
		case "userLastName":
			return SiteRequestEnUS.staticSolrUserLastName(siteRequest_, (String)o);
		case "userFirstName":
			return SiteRequestEnUS.staticSolrUserFirstName(siteRequest_, (String)o);
		case "userFullName":
			return SiteRequestEnUS.staticSolrUserFullName(siteRequest_, (String)o);
		case "userEmail":
			return SiteRequestEnUS.staticSolrUserEmail(siteRequest_, (String)o);
		case "userRealmRoles":
			return SiteRequestEnUS.staticSolrUserRealmRoles(siteRequest_, (String)o);
		case "userResourceRoles":
			return SiteRequestEnUS.staticSolrUserResourceRoles(siteRequest_, (String)o);
		case "pageAdmin":
			return SiteRequestEnUS.staticSolrPageAdmin(siteRequest_, (Boolean)o);
		case "requestPk":
			return SiteRequestEnUS.staticSolrRequestPk(siteRequest_, (Long)o);
		case "requestUri":
			return SiteRequestEnUS.staticSolrRequestUri(siteRequest_, (String)o);
		case "requestMethod":
			return SiteRequestEnUS.staticSolrRequestMethod(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrSiteRequestEnUS(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrSiteRequestEnUS(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "userId":
			return SiteRequestEnUS.staticSolrStrUserId(siteRequest_, (String)o);
		case "userKey":
			return SiteRequestEnUS.staticSolrStrUserKey(siteRequest_, (Long)o);
		case "sessionId":
			return SiteRequestEnUS.staticSolrStrSessionId(siteRequest_, (String)o);
		case "sessionIdBefore":
			return SiteRequestEnUS.staticSolrStrSessionIdBefore(siteRequest_, (String)o);
		case "userName":
			return SiteRequestEnUS.staticSolrStrUserName(siteRequest_, (String)o);
		case "userLastName":
			return SiteRequestEnUS.staticSolrStrUserLastName(siteRequest_, (String)o);
		case "userFirstName":
			return SiteRequestEnUS.staticSolrStrUserFirstName(siteRequest_, (String)o);
		case "userFullName":
			return SiteRequestEnUS.staticSolrStrUserFullName(siteRequest_, (String)o);
		case "userEmail":
			return SiteRequestEnUS.staticSolrStrUserEmail(siteRequest_, (String)o);
		case "userRealmRoles":
			return SiteRequestEnUS.staticSolrStrUserRealmRoles(siteRequest_, (String)o);
		case "userResourceRoles":
			return SiteRequestEnUS.staticSolrStrUserResourceRoles(siteRequest_, (String)o);
		case "pageAdmin":
			return SiteRequestEnUS.staticSolrStrPageAdmin(siteRequest_, (Boolean)o);
		case "requestPk":
			return SiteRequestEnUS.staticSolrStrRequestPk(siteRequest_, (Long)o);
		case "requestUri":
			return SiteRequestEnUS.staticSolrStrRequestUri(siteRequest_, (String)o);
		case "requestMethod":
			return SiteRequestEnUS.staticSolrStrRequestMethod(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqSiteRequestEnUS(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqSiteRequestEnUS(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "userId":
			return SiteRequestEnUS.staticSolrFqUserId(siteRequest_, o);
		case "userKey":
			return SiteRequestEnUS.staticSolrFqUserKey(siteRequest_, o);
		case "sessionId":
			return SiteRequestEnUS.staticSolrFqSessionId(siteRequest_, o);
		case "sessionIdBefore":
			return SiteRequestEnUS.staticSolrFqSessionIdBefore(siteRequest_, o);
		case "userName":
			return SiteRequestEnUS.staticSolrFqUserName(siteRequest_, o);
		case "userLastName":
			return SiteRequestEnUS.staticSolrFqUserLastName(siteRequest_, o);
		case "userFirstName":
			return SiteRequestEnUS.staticSolrFqUserFirstName(siteRequest_, o);
		case "userFullName":
			return SiteRequestEnUS.staticSolrFqUserFullName(siteRequest_, o);
		case "userEmail":
			return SiteRequestEnUS.staticSolrFqUserEmail(siteRequest_, o);
		case "userRealmRoles":
			return SiteRequestEnUS.staticSolrFqUserRealmRoles(siteRequest_, o);
		case "userResourceRoles":
			return SiteRequestEnUS.staticSolrFqUserResourceRoles(siteRequest_, o);
		case "pageAdmin":
			return SiteRequestEnUS.staticSolrFqPageAdmin(siteRequest_, o);
		case "requestPk":
			return SiteRequestEnUS.staticSolrFqRequestPk(siteRequest_, o);
		case "requestUri":
			return SiteRequestEnUS.staticSolrFqRequestUri(siteRequest_, o);
		case "requestMethod":
			return SiteRequestEnUS.staticSolrFqRequestMethod(siteRequest_, o);
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
					o = defineSiteRequestEnUS(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineSiteRequestEnUS(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestSiteRequestEnUS() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof SiteRequestEnUS) {
			SiteRequestEnUS original = (SiteRequestEnUS)o;
		}
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		return sb.toString();
	}

	public static final String VAR_config = "config";
	public static final String VAR_siteRequest_ = "siteRequest_";
	public static final String VAR_webClient = "webClient";
	public static final String VAR_apiRequest_ = "apiRequest_";
	public static final String VAR_jsonObject = "jsonObject";
	public static final String VAR_solrQuery = "solrQuery";
	public static final String VAR_serviceRequest = "serviceRequest";
	public static final String VAR_user = "user";
	public static final String VAR_userId = "userId";
	public static final String VAR_userKey = "userKey";
	public static final String VAR_sessionId = "sessionId";
	public static final String VAR_sessionIdBefore = "sessionIdBefore";
	public static final String VAR_userName = "userName";
	public static final String VAR_userLastName = "userLastName";
	public static final String VAR_userFirstName = "userFirstName";
	public static final String VAR_userFullName = "userFullName";
	public static final String VAR_userEmail = "userEmail";
	public static final String VAR_userRealmRoles = "userRealmRoles";
	public static final String VAR_userResource = "userResource";
	public static final String VAR_userResourceRoles = "userResourceRoles";
	public static final String VAR_solrDocument = "solrDocument";
	public static final String VAR_pageAdmin = "pageAdmin";
	public static final String VAR_requestPk = "requestPk";
	public static final String VAR_requestUri = "requestUri";
	public static final String VAR_requestMethod = "requestMethod";
	public static final String VAR_sqlConnection = "sqlConnection";
	public static final String VAR_requestHeaders = "requestHeaders";
	public static final String VAR_requestVars = "requestVars";
}
