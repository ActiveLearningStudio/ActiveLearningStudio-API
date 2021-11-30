package org.curriki.api.enus.user;

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
import org.curriki.api.enus.java.ZonedDateTimeSerializer;
import java.lang.String;
import java.math.RoundingMode;
import org.curriki.api.enus.search.SearchList;
import org.slf4j.Logger;
import java.math.MathContext;
import io.vertx.core.Promise;
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.user.SiteUser;
import io.vertx.core.Future;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import org.curriki.api.enus.base.BaseModelPage;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUserGenPage&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class SiteUserGenPageGen<DEV> extends BaseModelPage {
	protected static final Logger LOG = LoggerFactory.getLogger(SiteUserGenPage.class);

	/////////////////////////
	// searchListSiteUser_ //
	/////////////////////////

	/**	 The entity searchListSiteUser_
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected SearchList<SiteUser> searchListSiteUser_;

	/**	<br/> The entity searchListSiteUser_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUserGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:searchListSiteUser_">Find the entity searchListSiteUser_ in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _searchListSiteUser_(Wrap<SearchList<SiteUser>> w);

	public SearchList<SiteUser> getSearchListSiteUser_() {
		return searchListSiteUser_;
	}

	public void setSearchListSiteUser_(SearchList<SiteUser> searchListSiteUser_) {
		this.searchListSiteUser_ = searchListSiteUser_;
	}
	public static SearchList<SiteUser> staticSetSearchListSiteUser_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteUserGenPage searchListSiteUser_Init() {
		Wrap<SearchList<SiteUser>> searchListSiteUser_Wrap = new Wrap<SearchList<SiteUser>>().var("searchListSiteUser_");
		if(searchListSiteUser_ == null) {
			_searchListSiteUser_(searchListSiteUser_Wrap);
			setSearchListSiteUser_(searchListSiteUser_Wrap.o);
		}
		return (SiteUserGenPage)this;
	}

	//////////////////
	// listSiteUser //
	//////////////////

	/**	 The entity listSiteUser
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut JsonArray(). 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonArray listSiteUser = new JsonArray();

	/**	<br/> The entity listSiteUser
	 *  It is constructed before being initialized with the constructor by default JsonArray(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUserGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:listSiteUser">Find the entity listSiteUser in Solr</a>
	 * <br/>
	 * @param listSiteUser is the entity already constructed. 
	 **/
	protected abstract void _listSiteUser(JsonArray l);

	public JsonArray getListSiteUser() {
		return listSiteUser;
	}

	public void setListSiteUser(JsonArray listSiteUser) {
		this.listSiteUser = listSiteUser;
	}
	public static JsonArray staticSetListSiteUser(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteUserGenPage listSiteUserInit() {
		_listSiteUser(listSiteUser);
		return (SiteUserGenPage)this;
	}

	///////////////////
	// siteUserCount //
	///////////////////

	/**	 The entity siteUserCount
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer siteUserCount;

	/**	<br/> The entity siteUserCount
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUserGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteUserCount">Find the entity siteUserCount in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _siteUserCount(Wrap<Integer> w);

	public Integer getSiteUserCount() {
		return siteUserCount;
	}

	public void setSiteUserCount(Integer siteUserCount) {
		this.siteUserCount = siteUserCount;
	}
	@JsonIgnore
	public void setSiteUserCount(String o) {
		this.siteUserCount = SiteUserGenPage.staticSetSiteUserCount(siteRequest_, o);
	}
	public static Integer staticSetSiteUserCount(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected SiteUserGenPage siteUserCountInit() {
		Wrap<Integer> siteUserCountWrap = new Wrap<Integer>().var("siteUserCount");
		if(siteUserCount == null) {
			_siteUserCount(siteUserCountWrap);
			setSiteUserCount(siteUserCountWrap.o);
		}
		return (SiteUserGenPage)this;
	}

	public static Integer staticSolrSiteUserCount(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrSiteUserCount(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqSiteUserCount(SiteRequestEnUS siteRequest_, String o) {
		return SiteUserGenPage.staticSolrStrSiteUserCount(siteRequest_, SiteUserGenPage.staticSolrSiteUserCount(siteRequest_, SiteUserGenPage.staticSetSiteUserCount(siteRequest_, o)));
	}

	///////////////
	// siteUser_ //
	///////////////

	/**	 The entity siteUser_
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SiteUser siteUser_;

	/**	<br/> The entity siteUser_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUserGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteUser_">Find the entity siteUser_ in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _siteUser_(Wrap<SiteUser> w);

	public SiteUser getSiteUser_() {
		return siteUser_;
	}

	public void setSiteUser_(SiteUser siteUser_) {
		this.siteUser_ = siteUser_;
	}
	public static SiteUser staticSetSiteUser_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SiteUserGenPage siteUser_Init() {
		Wrap<SiteUser> siteUser_Wrap = new Wrap<SiteUser>().var("siteUser_");
		if(siteUser_ == null) {
			_siteUser_(siteUser_Wrap);
			setSiteUser_(siteUser_Wrap.o);
		}
		return (SiteUserGenPage)this;
	}

	////////
	// pk //
	////////

	/**	 The entity pk
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Long pk;

	/**	<br/> The entity pk
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.user.SiteUserGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pk">Find the entity pk in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _pk(Wrap<Long> w);

	public Long getPk() {
		return pk;
	}

	public void setPk(Long pk) {
		this.pk = pk;
	}
	@JsonIgnore
	public void setPk(String o) {
		this.pk = SiteUserGenPage.staticSetPk(siteRequest_, o);
	}
	public static Long staticSetPk(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	protected SiteUserGenPage pkInit() {
		Wrap<Long> pkWrap = new Wrap<Long>().var("pk");
		if(pk == null) {
			_pk(pkWrap);
			setPk(pkWrap.o);
		}
		return (SiteUserGenPage)this;
	}

	public static Long staticSolrPk(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrPk(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPk(SiteRequestEnUS siteRequest_, String o) {
		return SiteUserGenPage.staticSolrStrPk(siteRequest_, SiteUserGenPage.staticSolrPk(siteRequest_, SiteUserGenPage.staticSetPk(siteRequest_, o)));
	}

	//////////////
	// initDeep //
	//////////////

	public Future<Void> promiseDeepSiteUserGenPage(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		return promiseDeepSiteUserGenPage();
	}

	public Future<Void> promiseDeepSiteUserGenPage() {
		Promise<Void> promise = Promise.promise();
		Promise<Void> promise2 = Promise.promise();
		promiseSiteUserGenPage(promise2);
		promise2.future().onSuccess(a -> {
			super.promiseDeepBaseModelPage(siteRequest_).onSuccess(b -> {
				promise.complete();
			}).onFailure(ex -> {
				promise.fail(ex);
			});
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	public Future<Void> promiseSiteUserGenPage(Promise<Void> promise) {
		Future.future(a -> a.complete()).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				searchListSiteUser_Init();
				listSiteUserInit();
				siteUserCountInit();
				siteUser_Init();
				pkInit();
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
		return promiseDeepSiteUserGenPage(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestSiteUserGenPage(SiteRequestEnUS siteRequest_) {
			super.siteRequestBaseModelPage(siteRequest_);
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestSiteUserGenPage(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	@Override public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainSiteUserGenPage(v);
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
	public Object obtainSiteUserGenPage(String var) {
		SiteUserGenPage oSiteUserGenPage = (SiteUserGenPage)this;
		switch(var) {
			case "searchListSiteUser_":
				return oSiteUserGenPage.searchListSiteUser_;
			case "listSiteUser":
				return oSiteUserGenPage.listSiteUser;
			case "siteUserCount":
				return oSiteUserGenPage.siteUserCount;
			case "siteUser_":
				return oSiteUserGenPage.siteUser_;
			case "pk":
				return oSiteUserGenPage.pk;
			default:
				return super.obtainBaseModelPage(var);
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
				o = relateSiteUserGenPage(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relateSiteUserGenPage(String var, Object val) {
		SiteUserGenPage oSiteUserGenPage = (SiteUserGenPage)this;
		switch(var) {
			default:
				return super.relateBaseModelPage(var, val);
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetSiteUserGenPage(entityVar,  siteRequest_, o);
	}
	public static Object staticSetSiteUserGenPage(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "siteUserCount":
			return SiteUserGenPage.staticSetSiteUserCount(siteRequest_, o);
		case "pk":
			return SiteUserGenPage.staticSetPk(siteRequest_, o);
			default:
				return BaseModelPage.staticSetBaseModelPage(entityVar,  siteRequest_, o);
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrSiteUserGenPage(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrSiteUserGenPage(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "siteUserCount":
			return SiteUserGenPage.staticSolrSiteUserCount(siteRequest_, (Integer)o);
		case "pk":
			return SiteUserGenPage.staticSolrPk(siteRequest_, (Long)o);
			default:
				return BaseModelPage.staticSolrBaseModelPage(entityVar,  siteRequest_, o);
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrSiteUserGenPage(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrSiteUserGenPage(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "siteUserCount":
			return SiteUserGenPage.staticSolrStrSiteUserCount(siteRequest_, (Integer)o);
		case "pk":
			return SiteUserGenPage.staticSolrStrPk(siteRequest_, (Long)o);
			default:
				return BaseModelPage.staticSolrStrBaseModelPage(entityVar,  siteRequest_, o);
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqSiteUserGenPage(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqSiteUserGenPage(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "siteUserCount":
			return SiteUserGenPage.staticSolrFqSiteUserCount(siteRequest_, o);
		case "pk":
			return SiteUserGenPage.staticSolrFqPk(siteRequest_, o);
			default:
				return BaseModelPage.staticSolrFqBaseModelPage(entityVar,  siteRequest_, o);
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
					o = defineSiteUserGenPage(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineSiteUserGenPage(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return super.defineBaseModelPage(var, val);
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestSiteUserGenPage() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof SiteUserGenPage) {
			SiteUserGenPage original = (SiteUserGenPage)o;
			super.apiRequestBaseModelPage();
		}
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		sb.append(super.toString());
		return sb.toString();
	}

	public static final String VAR_searchListSiteUser_ = "searchListSiteUser_";
	public static final String VAR_listSiteUser = "listSiteUser";
	public static final String VAR_siteUserCount = "siteUserCount";
	public static final String VAR_siteUser_ = "siteUser_";
	public static final String VAR_pk = "pk";
}
