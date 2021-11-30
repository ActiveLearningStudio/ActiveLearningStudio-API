package org.curriki.api.enus.base;

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
import org.curriki.api.enus.page.PageLayout;
import java.math.MathContext;
import io.vertx.core.Promise;
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import io.vertx.core.Future;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.base.BaseModelGenPage&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class BaseModelGenPageGen<DEV> extends PageLayout {
	protected static final Logger LOG = LoggerFactory.getLogger(BaseModelGenPage.class);

	//////////////////////////
	// searchListBaseModel_ //
	//////////////////////////

	/**	 The entity searchListBaseModel_
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected SearchList<BaseModel> searchListBaseModel_;

	/**	<br/> The entity searchListBaseModel_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.base.BaseModelGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:searchListBaseModel_">Find the entity searchListBaseModel_ in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _searchListBaseModel_(Wrap<SearchList<BaseModel>> w);

	public SearchList<BaseModel> getSearchListBaseModel_() {
		return searchListBaseModel_;
	}

	public void setSearchListBaseModel_(SearchList<BaseModel> searchListBaseModel_) {
		this.searchListBaseModel_ = searchListBaseModel_;
	}
	public static SearchList<BaseModel> staticSetSearchListBaseModel_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected BaseModelGenPage searchListBaseModel_Init() {
		Wrap<SearchList<BaseModel>> searchListBaseModel_Wrap = new Wrap<SearchList<BaseModel>>().var("searchListBaseModel_");
		if(searchListBaseModel_ == null) {
			_searchListBaseModel_(searchListBaseModel_Wrap);
			setSearchListBaseModel_(searchListBaseModel_Wrap.o);
		}
		return (BaseModelGenPage)this;
	}

	///////////////////
	// listBaseModel //
	///////////////////

	/**	 The entity listBaseModel
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut JsonArray(). 
	 */
	@JsonInclude(Include.NON_NULL)
	protected JsonArray listBaseModel = new JsonArray();

	/**	<br/> The entity listBaseModel
	 *  It is constructed before being initialized with the constructor by default JsonArray(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.base.BaseModelGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:listBaseModel">Find the entity listBaseModel in Solr</a>
	 * <br/>
	 * @param listBaseModel is the entity already constructed. 
	 **/
	protected abstract void _listBaseModel(JsonArray l);

	public JsonArray getListBaseModel() {
		return listBaseModel;
	}

	public void setListBaseModel(JsonArray listBaseModel) {
		this.listBaseModel = listBaseModel;
	}
	public static JsonArray staticSetListBaseModel(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected BaseModelGenPage listBaseModelInit() {
		_listBaseModel(listBaseModel);
		return (BaseModelGenPage)this;
	}

	////////////////////
	// baseModelCount //
	////////////////////

	/**	 The entity baseModelCount
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer baseModelCount;

	/**	<br/> The entity baseModelCount
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.base.BaseModelGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:baseModelCount">Find the entity baseModelCount in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _baseModelCount(Wrap<Integer> w);

	public Integer getBaseModelCount() {
		return baseModelCount;
	}

	public void setBaseModelCount(Integer baseModelCount) {
		this.baseModelCount = baseModelCount;
	}
	@JsonIgnore
	public void setBaseModelCount(String o) {
		this.baseModelCount = BaseModelGenPage.staticSetBaseModelCount(siteRequest_, o);
	}
	public static Integer staticSetBaseModelCount(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected BaseModelGenPage baseModelCountInit() {
		Wrap<Integer> baseModelCountWrap = new Wrap<Integer>().var("baseModelCount");
		if(baseModelCount == null) {
			_baseModelCount(baseModelCountWrap);
			setBaseModelCount(baseModelCountWrap.o);
		}
		return (BaseModelGenPage)this;
	}

	public static Integer staticSolrBaseModelCount(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrBaseModelCount(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqBaseModelCount(SiteRequestEnUS siteRequest_, String o) {
		return BaseModelGenPage.staticSolrStrBaseModelCount(siteRequest_, BaseModelGenPage.staticSolrBaseModelCount(siteRequest_, BaseModelGenPage.staticSetBaseModelCount(siteRequest_, o)));
	}

	////////////////
	// baseModel_ //
	////////////////

	/**	 The entity baseModel_
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected BaseModel baseModel_;

	/**	<br/> The entity baseModel_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.base.BaseModelGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:baseModel_">Find the entity baseModel_ in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _baseModel_(Wrap<BaseModel> w);

	public BaseModel getBaseModel_() {
		return baseModel_;
	}

	public void setBaseModel_(BaseModel baseModel_) {
		this.baseModel_ = baseModel_;
	}
	public static BaseModel staticSetBaseModel_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected BaseModelGenPage baseModel_Init() {
		Wrap<BaseModel> baseModel_Wrap = new Wrap<BaseModel>().var("baseModel_");
		if(baseModel_ == null) {
			_baseModel_(baseModel_Wrap);
			setBaseModel_(baseModel_Wrap.o);
		}
		return (BaseModelGenPage)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.base.BaseModelGenPage&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:pk">Find the entity pk in Solr</a>
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
		this.pk = BaseModelGenPage.staticSetPk(siteRequest_, o);
	}
	public static Long staticSetPk(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Long.parseLong(o);
		return null;
	}
	protected BaseModelGenPage pkInit() {
		Wrap<Long> pkWrap = new Wrap<Long>().var("pk");
		if(pk == null) {
			_pk(pkWrap);
			setPk(pkWrap.o);
		}
		return (BaseModelGenPage)this;
	}

	public static Long staticSolrPk(SiteRequestEnUS siteRequest_, Long o) {
		return o;
	}

	public static String staticSolrStrPk(SiteRequestEnUS siteRequest_, Long o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPk(SiteRequestEnUS siteRequest_, String o) {
		return BaseModelGenPage.staticSolrStrPk(siteRequest_, BaseModelGenPage.staticSolrPk(siteRequest_, BaseModelGenPage.staticSetPk(siteRequest_, o)));
	}

	//////////////
	// initDeep //
	//////////////

	public Future<Void> promiseDeepBaseModelGenPage(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		return promiseDeepBaseModelGenPage();
	}

	public Future<Void> promiseDeepBaseModelGenPage() {
		Promise<Void> promise = Promise.promise();
		Promise<Void> promise2 = Promise.promise();
		promiseBaseModelGenPage(promise2);
		promise2.future().onSuccess(a -> {
			super.promiseDeepPageLayout(siteRequest_).onSuccess(b -> {
				promise.complete();
			}).onFailure(ex -> {
				promise.fail(ex);
			});
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	public Future<Void> promiseBaseModelGenPage(Promise<Void> promise) {
		Future.future(a -> a.complete()).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				searchListBaseModel_Init();
				listBaseModelInit();
				baseModelCountInit();
				baseModel_Init();
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
		return promiseDeepBaseModelGenPage(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestBaseModelGenPage(SiteRequestEnUS siteRequest_) {
			super.siteRequestPageLayout(siteRequest_);
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestBaseModelGenPage(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	@Override public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainBaseModelGenPage(v);
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
	public Object obtainBaseModelGenPage(String var) {
		BaseModelGenPage oBaseModelGenPage = (BaseModelGenPage)this;
		switch(var) {
			case "searchListBaseModel_":
				return oBaseModelGenPage.searchListBaseModel_;
			case "listBaseModel":
				return oBaseModelGenPage.listBaseModel;
			case "baseModelCount":
				return oBaseModelGenPage.baseModelCount;
			case "baseModel_":
				return oBaseModelGenPage.baseModel_;
			case "pk":
				return oBaseModelGenPage.pk;
			default:
				return super.obtainPageLayout(var);
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
				o = relateBaseModelGenPage(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relateBaseModelGenPage(String var, Object val) {
		BaseModelGenPage oBaseModelGenPage = (BaseModelGenPage)this;
		switch(var) {
			default:
				return super.relatePageLayout(var, val);
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetBaseModelGenPage(entityVar,  siteRequest_, o);
	}
	public static Object staticSetBaseModelGenPage(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "baseModelCount":
			return BaseModelGenPage.staticSetBaseModelCount(siteRequest_, o);
		case "pk":
			return BaseModelGenPage.staticSetPk(siteRequest_, o);
			default:
				return PageLayout.staticSetPageLayout(entityVar,  siteRequest_, o);
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrBaseModelGenPage(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrBaseModelGenPage(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "baseModelCount":
			return BaseModelGenPage.staticSolrBaseModelCount(siteRequest_, (Integer)o);
		case "pk":
			return BaseModelGenPage.staticSolrPk(siteRequest_, (Long)o);
			default:
				return PageLayout.staticSolrPageLayout(entityVar,  siteRequest_, o);
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrBaseModelGenPage(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrBaseModelGenPage(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "baseModelCount":
			return BaseModelGenPage.staticSolrStrBaseModelCount(siteRequest_, (Integer)o);
		case "pk":
			return BaseModelGenPage.staticSolrStrPk(siteRequest_, (Long)o);
			default:
				return PageLayout.staticSolrStrPageLayout(entityVar,  siteRequest_, o);
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqBaseModelGenPage(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqBaseModelGenPage(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "baseModelCount":
			return BaseModelGenPage.staticSolrFqBaseModelCount(siteRequest_, o);
		case "pk":
			return BaseModelGenPage.staticSolrFqPk(siteRequest_, o);
			default:
				return PageLayout.staticSolrFqPageLayout(entityVar,  siteRequest_, o);
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
					o = defineBaseModelGenPage(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineBaseModelGenPage(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return super.definePageLayout(var, val);
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestBaseModelGenPage() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof BaseModelGenPage) {
			BaseModelGenPage original = (BaseModelGenPage)o;
			super.apiRequestPageLayout();
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

	public static final String VAR_searchListBaseModel_ = "searchListBaseModel_";
	public static final String VAR_listBaseModel = "listBaseModel";
	public static final String VAR_baseModelCount = "baseModelCount";
	public static final String VAR_baseModel_ = "baseModel_";
	public static final String VAR_pk = "pk";
}
