package org.curriki.api.enus.user;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.Arrays;
import com.fasterxml.jackson.databind.ser.std.ToStringSerializer;
import org.curriki.api.enus.base.BaseModel;
import java.util.Date;
import org.curriki.api.enus.request.api.ApiRequest;
import org.slf4j.LoggerFactory;
import java.util.HashMap;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.apache.commons.lang3.StringUtils;
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
import org.slf4j.Logger;
import java.math.MathContext;
import io.vertx.core.Promise;
import org.apache.solr.client.solrj.response.QueryResponse;
import java.util.Set;
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import io.vertx.core.Future;
import org.apache.solr.client.solrj.SolrClient;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import org.apache.solr.common.SolrDocument;
import java.util.List;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import org.apache.solr.client.solrj.util.ClientUtils;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;
import org.apache.solr.common.SolrInputDocument;
import org.apache.commons.lang3.exception.ExceptionUtils;

/**	
 * Map.hackathonMission: to create a generated Java class that can be extended and override these methods to store information about site users in the system. 
 * Map.hackathonColumn: Develop SiteUser API
 * Map.hackathonLabels: Java
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class SiteUserGen<DEV> extends BaseModel {
	protected static final Logger LOG = LoggerFactory.getLogger(SiteUser.class);

	public static final String SiteUser_AName = "a site user";
	public static final String SiteUser_This = "this ";
	public static final String SiteUser_ThisName = "this site user";
	public static final String SiteUser_A = "a ";
	public static final String SiteUser_TheName = "the site user";
	public static final String SiteUser_NameSingular = "site user";
	public static final String SiteUser_NamePlural = "site users";
	public static final String SiteUser_NameActual = "current site user";
	public static final String SiteUser_AllName = "all the site users";
	public static final String SiteUser_SearchAllNameBy = "search site users by ";
	public static final String SiteUser_Title = "site users";
	public static final String SiteUser_ThePluralName = "the site users";
	public static final String SiteUser_NoNameFound = "no site user found";
	public static final String SiteUser_NameVar = "siteUser";
	public static final String SiteUser_OfName = "of site user";
	public static final String SiteUser_ANameAdjective = "a site user";
	public static final String SiteUser_NameAdjectiveSingular = "site user";
	public static final String SiteUser_NameAdjectivePlural = "site users";
	public static final String SiteUser_Color = "gray";
	public static final String SiteUser_IconGroup = "regular";
	public static final String SiteUser_IconName = "user-cog";

	//////////////
	// userKeys //
	//////////////

	/**	 The entity userKeys
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<Long>(). 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonSerialize(contentUsing = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected List<Long> userKeys = new ArrayList<Long>();

	/**	<br/> The entity userKeys
	 *  It is constructed before being initialized with the constructor by default List<Long>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userKeys">Find the entity userKeys in Solr</a>
	 * <br/>
	 * @param userKeys is the entity already constructed. 
	 **/
	protected abstract void _userKeys(List<Long> l);

	public List<Long> getUserKeys() {
		return userKeys;
	}

	public void setUserKeys(List<Long> userKeys) {
		this.userKeys = userKeys;
	}
	@JsonIgnore
	public void setUserKeys(String o) {
		Long l = SiteUser.staticSetUserKeys(siteRequest_, o);
		if(l != null)
			addUserKeys(l);
	}
	public static Long staticSetUserKeys(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	public SiteUser addUserKeys(Long...objets) {
		for(Long o : objets) {
			addUserKeys(o);
		}
		return (SiteUser)this;
	}
	public SiteUser addUserKeys(Long o) {
		if(o != null)
			this.userKeys.add(o);
		return (SiteUser)this;
	}
	@JsonIgnore
	public void setUserKeys(JsonArray objets) {
		userKeys.clear();
		for(int i = 0; i < objets.size(); i++) {
			Long o = objets.getLong(i);
			addUserKeys(o);
		}
	}
	public SiteUser addUserKeys(String o) {
		if(NumberUtils.isParsable(o)) {
			Long p = Long.parseLong(o);
			addUserKeys(p);
		}
		return (SiteUser)this;
	}
	protected SiteUser userKeysInit() {
		_userKeys(userKeys);
		return (SiteUser)this;
	}

	public static Long staticSolrUserKeys(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrUserKeys(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserKeys(SiteRequestEnUS siteRequest_, String o) {
		return SiteUser.staticSolrStrUserKeys(siteRequest_, SiteUser.staticSolrUserKeys(siteRequest_, SiteUser.staticSetUserKeys(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userId">Find the entity userId in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userId(Wrap<String> c);

	public String getUserId() {
		return userId;
	}
	public void setUserId(String o) {
		this.userId = SiteUser.staticSetUserId(siteRequest_, o);
	}
	public static String staticSetUserId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteUser userIdInit() {
		Wrap<String> userIdWrap = new Wrap<String>().var("userId");
		if(userId == null) {
			_userId(userIdWrap);
			setUserId(userIdWrap.o);
		}
		return (SiteUser)this;
	}

	public static String staticSolrUserId(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserId(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserId(SiteRequestEnUS siteRequest_, String o) {
		return SiteUser.staticSolrStrUserId(siteRequest_, SiteUser.staticSolrUserId(siteRequest_, SiteUser.staticSetUserId(siteRequest_, o)));
	}

	public String sqlUserId() {
		return userId;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userName">Find the entity userName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userName(Wrap<String> c);

	public String getUserName() {
		return userName;
	}
	public void setUserName(String o) {
		this.userName = SiteUser.staticSetUserName(siteRequest_, o);
	}
	public static String staticSetUserName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteUser userNameInit() {
		Wrap<String> userNameWrap = new Wrap<String>().var("userName");
		if(userName == null) {
			_userName(userNameWrap);
			setUserName(userNameWrap.o);
		}
		return (SiteUser)this;
	}

	public static String staticSolrUserName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserName(SiteRequestEnUS siteRequest_, String o) {
		return SiteUser.staticSolrStrUserName(siteRequest_, SiteUser.staticSolrUserName(siteRequest_, SiteUser.staticSetUserName(siteRequest_, o)));
	}

	public String sqlUserName() {
		return userName;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userEmail">Find the entity userEmail in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userEmail(Wrap<String> c);

	public String getUserEmail() {
		return userEmail;
	}
	public void setUserEmail(String o) {
		this.userEmail = SiteUser.staticSetUserEmail(siteRequest_, o);
	}
	public static String staticSetUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteUser userEmailInit() {
		Wrap<String> userEmailWrap = new Wrap<String>().var("userEmail");
		if(userEmail == null) {
			_userEmail(userEmailWrap);
			setUserEmail(userEmailWrap.o);
		}
		return (SiteUser)this;
	}

	public static String staticSolrUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserEmail(SiteRequestEnUS siteRequest_, String o) {
		return SiteUser.staticSolrStrUserEmail(siteRequest_, SiteUser.staticSolrUserEmail(siteRequest_, SiteUser.staticSetUserEmail(siteRequest_, o)));
	}

	public String sqlUserEmail() {
		return userEmail;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userFirstName">Find the entity userFirstName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userFirstName(Wrap<String> c);

	public String getUserFirstName() {
		return userFirstName;
	}
	public void setUserFirstName(String o) {
		this.userFirstName = SiteUser.staticSetUserFirstName(siteRequest_, o);
	}
	public static String staticSetUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteUser userFirstNameInit() {
		Wrap<String> userFirstNameWrap = new Wrap<String>().var("userFirstName");
		if(userFirstName == null) {
			_userFirstName(userFirstNameWrap);
			setUserFirstName(userFirstNameWrap.o);
		}
		return (SiteUser)this;
	}

	public static String staticSolrUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserFirstName(SiteRequestEnUS siteRequest_, String o) {
		return SiteUser.staticSolrStrUserFirstName(siteRequest_, SiteUser.staticSolrUserFirstName(siteRequest_, SiteUser.staticSetUserFirstName(siteRequest_, o)));
	}

	public String sqlUserFirstName() {
		return userFirstName;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userLastName">Find the entity userLastName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userLastName(Wrap<String> c);

	public String getUserLastName() {
		return userLastName;
	}
	public void setUserLastName(String o) {
		this.userLastName = SiteUser.staticSetUserLastName(siteRequest_, o);
	}
	public static String staticSetUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteUser userLastNameInit() {
		Wrap<String> userLastNameWrap = new Wrap<String>().var("userLastName");
		if(userLastName == null) {
			_userLastName(userLastNameWrap);
			setUserLastName(userLastNameWrap.o);
		}
		return (SiteUser)this;
	}

	public static String staticSolrUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserLastName(SiteRequestEnUS siteRequest_, String o) {
		return SiteUser.staticSolrStrUserLastName(siteRequest_, SiteUser.staticSolrUserLastName(siteRequest_, SiteUser.staticSetUserLastName(siteRequest_, o)));
	}

	public String sqlUserLastName() {
		return userLastName;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUser&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:userFullName">Find the entity userFullName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _userFullName(Wrap<String> c);

	public String getUserFullName() {
		return userFullName;
	}
	public void setUserFullName(String o) {
		this.userFullName = SiteUser.staticSetUserFullName(siteRequest_, o);
	}
	public static String staticSetUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected SiteUser userFullNameInit() {
		Wrap<String> userFullNameWrap = new Wrap<String>().var("userFullName");
		if(userFullName == null) {
			_userFullName(userFullNameWrap);
			setUserFullName(userFullNameWrap.o);
		}
		return (SiteUser)this;
	}

	public static String staticSolrUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqUserFullName(SiteRequestEnUS siteRequest_, String o) {
		return SiteUser.staticSolrStrUserFullName(siteRequest_, SiteUser.staticSolrUserFullName(siteRequest_, SiteUser.staticSetUserFullName(siteRequest_, o)));
	}

	public String sqlUserFullName() {
		return userFullName;
	}

	//////////////
	// initDeep //
	//////////////

	public Future<Void> promiseDeepSiteUser(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		return promiseDeepSiteUser();
	}

	public Future<Void> promiseDeepSiteUser() {
		Promise<Void> promise = Promise.promise();
		Promise<Void> promise2 = Promise.promise();
		promiseSiteUser(promise2);
		promise2.future().onSuccess(a -> {
			super.promiseDeepBaseModel(siteRequest_).onSuccess(b -> {
				promise.complete();
			}).onFailure(ex -> {
				promise.fail(ex);
			});
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	public Future<Void> promiseSiteUser(Promise<Void> promise) {
		Future.future(a -> a.complete()).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				userKeysInit();
				userIdInit();
				userNameInit();
				userEmailInit();
				userFirstNameInit();
				userLastNameInit();
				userFullNameInit();
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

	@Override public Future<Void> promiseDeepForClass(SiteRequestEnUS siteRequest_) {
		return promiseDeepSiteUser(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestSiteUser(SiteRequestEnUS siteRequest_) {
			super.siteRequestBaseModel(siteRequest_);
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestSiteUser(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	@Override public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainSiteUser(v);
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
	public Object obtainSiteUser(String var) {
		SiteUser oSiteUser = (SiteUser)this;
		switch(var) {
			case "userKeys":
				return oSiteUser.userKeys;
			case "userId":
				return oSiteUser.userId;
			case "userName":
				return oSiteUser.userName;
			case "userEmail":
				return oSiteUser.userEmail;
			case "userFirstName":
				return oSiteUser.userFirstName;
			case "userLastName":
				return oSiteUser.userLastName;
			case "userFullName":
				return oSiteUser.userFullName;
			default:
				return super.obtainBaseModel(var);
		}
	}

	///////////////
	// relate //
	///////////////

	@Override public boolean relateForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = relateSiteUser(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relateSiteUser(String var, Object val) {
		SiteUser oSiteUser = (SiteUser)this;
		switch(var) {
			default:
				return super.relateBaseModel(var, val);
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetSiteUser(entityVar,  siteRequest_, o);
	}
	public static Object staticSetSiteUser(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "userKeys":
			return SiteUser.staticSetUserKeys(siteRequest_, o);
		case "userId":
			return SiteUser.staticSetUserId(siteRequest_, o);
		case "userName":
			return SiteUser.staticSetUserName(siteRequest_, o);
		case "userEmail":
			return SiteUser.staticSetUserEmail(siteRequest_, o);
		case "userFirstName":
			return SiteUser.staticSetUserFirstName(siteRequest_, o);
		case "userLastName":
			return SiteUser.staticSetUserLastName(siteRequest_, o);
		case "userFullName":
			return SiteUser.staticSetUserFullName(siteRequest_, o);
			default:
				return BaseModel.staticSetBaseModel(entityVar,  siteRequest_, o);
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrSiteUser(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrSiteUser(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "userKeys":
			return SiteUser.staticSolrUserKeys(siteRequest_, (Long)o);
		case "userId":
			return SiteUser.staticSolrUserId(siteRequest_, (String)o);
		case "userName":
			return SiteUser.staticSolrUserName(siteRequest_, (String)o);
		case "userEmail":
			return SiteUser.staticSolrUserEmail(siteRequest_, (String)o);
		case "userFirstName":
			return SiteUser.staticSolrUserFirstName(siteRequest_, (String)o);
		case "userLastName":
			return SiteUser.staticSolrUserLastName(siteRequest_, (String)o);
		case "userFullName":
			return SiteUser.staticSolrUserFullName(siteRequest_, (String)o);
			default:
				return BaseModel.staticSolrBaseModel(entityVar,  siteRequest_, o);
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrSiteUser(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrSiteUser(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "userKeys":
			return SiteUser.staticSolrStrUserKeys(siteRequest_, (Long)o);
		case "userId":
			return SiteUser.staticSolrStrUserId(siteRequest_, (String)o);
		case "userName":
			return SiteUser.staticSolrStrUserName(siteRequest_, (String)o);
		case "userEmail":
			return SiteUser.staticSolrStrUserEmail(siteRequest_, (String)o);
		case "userFirstName":
			return SiteUser.staticSolrStrUserFirstName(siteRequest_, (String)o);
		case "userLastName":
			return SiteUser.staticSolrStrUserLastName(siteRequest_, (String)o);
		case "userFullName":
			return SiteUser.staticSolrStrUserFullName(siteRequest_, (String)o);
			default:
				return BaseModel.staticSolrStrBaseModel(entityVar,  siteRequest_, o);
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqSiteUser(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqSiteUser(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "userKeys":
			return SiteUser.staticSolrFqUserKeys(siteRequest_, o);
		case "userId":
			return SiteUser.staticSolrFqUserId(siteRequest_, o);
		case "userName":
			return SiteUser.staticSolrFqUserName(siteRequest_, o);
		case "userEmail":
			return SiteUser.staticSolrFqUserEmail(siteRequest_, o);
		case "userFirstName":
			return SiteUser.staticSolrFqUserFirstName(siteRequest_, o);
		case "userLastName":
			return SiteUser.staticSolrFqUserLastName(siteRequest_, o);
		case "userFullName":
			return SiteUser.staticSolrFqUserFullName(siteRequest_, o);
			default:
				return BaseModel.staticSolrFqBaseModel(entityVar,  siteRequest_, o);
		}
	}

	/////////////
	// define //
	/////////////

	@Override public boolean defineForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		if(val != null) {
			for(String v : vars) {
				if(o == null)
					o = defineSiteUser(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineSiteUser(String var, Object val) {
		switch(var.toLowerCase()) {
			case "userid":
				if(val instanceof String)
					setUserId((String)val);
				saves.add("userId");
				return val;
			case "username":
				if(val instanceof String)
					setUserName((String)val);
				saves.add("userName");
				return val;
			case "useremail":
				if(val instanceof String)
					setUserEmail((String)val);
				saves.add("userEmail");
				return val;
			case "userfirstname":
				if(val instanceof String)
					setUserFirstName((String)val);
				saves.add("userFirstName");
				return val;
			case "userlastname":
				if(val instanceof String)
					setUserLastName((String)val);
				saves.add("userLastName");
				return val;
			case "userfullname":
				if(val instanceof String)
					setUserFullName((String)val);
				saves.add("userFullName");
				return val;
			default:
				return super.defineBaseModel(var, val);
		}
	}

	/////////////
	// populate //
	/////////////

	@Override public void populateForClass(SolrDocument solrDocument) {
		populateSiteUser(solrDocument);
	}
	public void populateSiteUser(SolrDocument solrDocument) {
		SiteUser oSiteUser = (SiteUser)this;
		saves = (List<String>)solrDocument.get("saves_docvalues_strings");
		if(saves != null) {
		}

		super.populateBaseModel(solrDocument);
	}

	public void indexSiteUser(SolrInputDocument document) {
		if(userKeys != null) {
			for(java.lang.Long o : userKeys) {
				document.addField("userKeys_docvalues_longs", o);
			}
		}
		if(userId != null) {
			document.addField("userId_docvalues_string", userId);
		}
		if(userName != null) {
			document.addField("userName_docvalues_string", userName);
		}
		if(userEmail != null) {
			document.addField("userEmail_docvalues_string", userEmail);
		}
		if(userFirstName != null) {
			document.addField("userFirstName_docvalues_string", userFirstName);
		}
		if(userLastName != null) {
			document.addField("userLastName_docvalues_string", userLastName);
		}
		if(userFullName != null) {
			document.addField("userFullName_docvalues_string", userFullName);
		}
		super.indexBaseModel(document);

	}

	public static String varIndexedSiteUser(String entityVar) {
		switch(entityVar) {
			case "userKeys":
				return "userKeys_docvalues_longs";
			case "userId":
				return "userId_docvalues_string";
			case "userName":
				return "userName_docvalues_string";
			case "userEmail":
				return "userEmail_docvalues_string";
			case "userFirstName":
				return "userFirstName_docvalues_string";
			case "userLastName":
				return "userLastName_docvalues_string";
			case "userFullName":
				return "userFullName_docvalues_string";
			default:
				return BaseModel.varIndexedBaseModel(entityVar);
		}
	}

	public static String varSearchSiteUser(String entityVar) {
		switch(entityVar) {
			default:
				return BaseModel.varSearchBaseModel(entityVar);
		}
	}

	public static String varSuggestedSiteUser(String entityVar) {
		switch(entityVar) {
			default:
				return BaseModel.varSuggestedBaseModel(entityVar);
		}
	}

	/////////////
	// store //
	/////////////

	@Override public void storeForClass(SolrDocument solrDocument) {
		storeSiteUser(solrDocument);
	}
	public void storeSiteUser(SolrDocument solrDocument) {
		SiteUser oSiteUser = (SiteUser)this;


		super.storeBaseModel(solrDocument);
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestSiteUser() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof SiteUser) {
			SiteUser original = (SiteUser)o;
			if(!Objects.equals(userKeys, original.getUserKeys()))
				apiRequest.addVars("userKeys");
			if(!Objects.equals(userId, original.getUserId()))
				apiRequest.addVars("userId");
			if(!Objects.equals(userName, original.getUserName()))
				apiRequest.addVars("userName");
			if(!Objects.equals(userEmail, original.getUserEmail()))
				apiRequest.addVars("userEmail");
			if(!Objects.equals(userFirstName, original.getUserFirstName()))
				apiRequest.addVars("userFirstName");
			if(!Objects.equals(userLastName, original.getUserLastName()))
				apiRequest.addVars("userLastName");
			if(!Objects.equals(userFullName, original.getUserFullName()))
				apiRequest.addVars("userFullName");
			super.apiRequestBaseModel();
		}
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		sb.append(super.toString());
		sb.append(Optional.ofNullable(userKeys).map(v -> "userKeys: " + v + "\n").orElse(""));
		sb.append(Optional.ofNullable(userId).map(v -> "userId: \"" + v + "\"\n" ).orElse(""));
		sb.append(Optional.ofNullable(userName).map(v -> "userName: \"" + v + "\"\n" ).orElse(""));
		sb.append(Optional.ofNullable(userEmail).map(v -> "userEmail: \"" + v + "\"\n" ).orElse(""));
		sb.append(Optional.ofNullable(userFirstName).map(v -> "userFirstName: \"" + v + "\"\n" ).orElse(""));
		sb.append(Optional.ofNullable(userLastName).map(v -> "userLastName: \"" + v + "\"\n" ).orElse(""));
		sb.append(Optional.ofNullable(userFullName).map(v -> "userFullName: \"" + v + "\"\n" ).orElse(""));
		return sb.toString();
	}

	public static final String VAR_userKeys = "userKeys";
	public static final String VAR_userId = "userId";
	public static final String VAR_userName = "userName";
	public static final String VAR_userEmail = "userEmail";
	public static final String VAR_userFirstName = "userFirstName";
	public static final String VAR_userLastName = "userLastName";
	public static final String VAR_userFullName = "userFullName";
}
