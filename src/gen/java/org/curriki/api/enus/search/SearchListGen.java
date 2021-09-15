package org.curriki.api.enus.search;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.Arrays;
import com.fasterxml.jackson.databind.ser.std.ToStringSerializer;
import org.curriki.api.enus.base.BaseModel;
import org.curriki.api.enus.request.api.ApiRequest;
import org.apache.solr.common.SolrDocumentList;
import org.curriki.api.enus.writer.AllWriter;
import org.slf4j.LoggerFactory;
import java.util.HashMap;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.apache.commons.lang3.StringUtils;
import java.text.NumberFormat;
import java.util.ArrayList;
import org.curriki.api.enus.wrap.Wrap;
import org.curriki.api.enus.java.ZonedDateTimeDeserializer;
import org.apache.commons.collections.CollectionUtils;
import com.fasterxml.jackson.databind.annotation.JsonSerialize;
import java.util.Map;
import com.fasterxml.jackson.annotation.JsonIgnore;
import java.lang.Boolean;
import org.curriki.api.enus.java.ZonedDateTimeSerializer;
import java.lang.String;
import java.math.RoundingMode;
import org.slf4j.Logger;
import java.math.MathContext;
import org.apache.solr.client.solrj.response.QueryResponse;
import io.vertx.core.Promise;
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import io.vertx.core.Future;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import java.util.List;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import java.lang.Class;
import java.lang.Object;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class SearchListGen<DEV> {
	protected static final Logger LOG = LoggerFactory.getLogger(SearchList.class);

	///////
	// c //
	///////

	/**	 The entity c
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected Class<?> c;
	@JsonIgnore
	public Wrap<Class<?>> cWrap = new Wrap<Class<?>>().var("c").o(c);

	/**	<br/> The entity c
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:c">Find the entity c in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _c(Wrap<Class<?>> c);

	public Class<?> getC() {
		return c;
	}

	public void setC(Class<?> c) {
		this.c = c;
		this.cWrap.alreadyInitialized = true;
	}
	public static Class<?> staticSetC(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SearchList cInit() {
		if(!cWrap.alreadyInitialized) {
			_c(cWrap);
			if(c == null)
				setC(cWrap.o);
			cWrap.o(null);
		}
		cWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	//////////////////
	// siteRequest_ //
	//////////////////

	/**	 The entity siteRequest_
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected SiteRequestEnUS siteRequest_;
	@JsonIgnore
	public Wrap<SiteRequestEnUS> siteRequest_Wrap = new Wrap<SiteRequestEnUS>().var("siteRequest_").o(siteRequest_);

	/**	<br/> The entity siteRequest_
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteRequest_">Find the entity siteRequest_ in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _siteRequest_(Wrap<SiteRequestEnUS> c);

	public SiteRequestEnUS getSiteRequest_() {
		return siteRequest_;
	}

	public void setSiteRequest_(SiteRequestEnUS siteRequest_) {
		this.siteRequest_ = siteRequest_;
		this.siteRequest_Wrap.alreadyInitialized = true;
	}
	public static SiteRequestEnUS staticSetSiteRequest_(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SearchList siteRequest_Init() {
		if(!siteRequest_Wrap.alreadyInitialized) {
			_siteRequest_(siteRequest_Wrap);
			if(siteRequest_ == null)
				setSiteRequest_(siteRequest_Wrap.o);
			siteRequest_Wrap.o(null);
		}
		siteRequest_Wrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	///////////
	// store //
	///////////

	/**	 The entity store
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected Boolean store;
	@JsonIgnore
	public Wrap<Boolean> storeWrap = new Wrap<Boolean>().var("store").o(store);

	/**	<br/> The entity store
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:store">Find the entity store in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _store(Wrap<Boolean> c);

	public Boolean getStore() {
		return store;
	}

	public void setStore(Boolean store) {
		this.store = store;
		this.storeWrap.alreadyInitialized = true;
	}
	@JsonIgnore
	public void setStore(String o) {
		this.store = SearchList.staticSetStore(siteRequest_, o);
		this.storeWrap.alreadyInitialized = true;
	}
	public static Boolean staticSetStore(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected SearchList storeInit() {
		if(!storeWrap.alreadyInitialized) {
			_store(storeWrap);
			if(store == null)
				setStore(storeWrap.o);
			storeWrap.o(null);
		}
		storeWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	public static Boolean staticSolrStore(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrStore(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqStore(SiteRequestEnUS siteRequest_, String o) {
		return SearchList.staticSolrStrStore(siteRequest_, SearchList.staticSolrStore(siteRequest_, SearchList.staticSetStore(siteRequest_, o)));
	}

	public Boolean solrStore() {
		return SearchList.staticSolrStore(siteRequest_, store);
	}

	public String strStore() {
		return store == null ? "" : store.toString();
	}

	public Boolean sqlStore() {
		return store;
	}

	public String jsonStore() {
		return store == null ? "" : store.toString();
	}

	//////////////
	// populate //
	//////////////

	/**	 The entity populate
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected Boolean populate;
	@JsonIgnore
	public Wrap<Boolean> populateWrap = new Wrap<Boolean>().var("populate").o(populate);

	/**	<br/> The entity populate
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:populate">Find the entity populate in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _populate(Wrap<Boolean> c);

	public Boolean getPopulate() {
		return populate;
	}

	public void setPopulate(Boolean populate) {
		this.populate = populate;
		this.populateWrap.alreadyInitialized = true;
	}
	@JsonIgnore
	public void setPopulate(String o) {
		this.populate = SearchList.staticSetPopulate(siteRequest_, o);
		this.populateWrap.alreadyInitialized = true;
	}
	public static Boolean staticSetPopulate(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected SearchList populateInit() {
		if(!populateWrap.alreadyInitialized) {
			_populate(populateWrap);
			if(populate == null)
				setPopulate(populateWrap.o);
			populateWrap.o(null);
		}
		populateWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	public static Boolean staticSolrPopulate(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrPopulate(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqPopulate(SiteRequestEnUS siteRequest_, String o) {
		return SearchList.staticSolrStrPopulate(siteRequest_, SearchList.staticSolrPopulate(siteRequest_, SearchList.staticSetPopulate(siteRequest_, o)));
	}

	public Boolean solrPopulate() {
		return SearchList.staticSolrPopulate(siteRequest_, populate);
	}

	public String strPopulate() {
		return populate == null ? "" : populate.toString();
	}

	public Boolean sqlPopulate() {
		return populate;
	}

	public String jsonPopulate() {
		return populate == null ? "" : populate.toString();
	}

	////////////
	// fields //
	////////////

	/**	 The entity fields
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<String>(). 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected List<String> fields = new ArrayList<String>();
	@JsonIgnore
	public Wrap<List<String>> fieldsWrap = new Wrap<List<String>>().var("fields").o(fields);

	/**	<br/> The entity fields
	 *  It is constructed before being initialized with the constructor by default List<String>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:fields">Find the entity fields in Solr</a>
	 * <br/>
	 * @param fields is the entity already constructed. 
	 **/
	protected abstract void _fields(List<String> c);

	public List<String> getFields() {
		return fields;
	}

	public void setFields(List<String> fields) {
		this.fields = fields;
		this.fieldsWrap.alreadyInitialized = true;
	}
	public static String staticSetFields(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public SearchList addFields(String...objets) {
		for(String o : objets) {
			addFields(o);
		}
		return (SearchList)this;
	}
	public SearchList addFields(String o) {
		if(o != null && !fields.contains(o))
			this.fields.add(o);
		return (SearchList)this;
	}
	@JsonIgnore
	public void setFields(JsonArray objets) {
		fields.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addFields(o);
		}
	}
	protected SearchList fieldsInit() {
		if(!fieldsWrap.alreadyInitialized) {
			_fields(fields);
		}
		fieldsWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	public static String staticSolrFields(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrFields(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqFields(SiteRequestEnUS siteRequest_, String o) {
		return SearchList.staticSolrStrFields(siteRequest_, SearchList.staticSolrFields(siteRequest_, SearchList.staticSetFields(siteRequest_, o)));
	}

	public List<String> solrFields() {
		List<String> l = new ArrayList<String>();
		for(String o : fields) {
			l.add(SearchList.staticSolrFields(siteRequest_, o));
		}
		return l;
	}

	public String strFields() {
		return fields == null ? "" : fields.toString();
	}

	public List<String> sqlFields() {
		return fields;
	}

	public String jsonFields() {
		return fields == null ? "" : fields.toString();
	}

	///////////////
	// solrQuery //
	///////////////

	/**	 The entity solrQuery
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut SolrQuery(). 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected SolrQuery solrQuery = new SolrQuery();
	@JsonIgnore
	public Wrap<SolrQuery> solrQueryWrap = new Wrap<SolrQuery>().var("solrQuery").o(solrQuery);

	/**	<br/> The entity solrQuery
	 *  It is constructed before being initialized with the constructor by default SolrQuery(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:solrQuery">Find the entity solrQuery in Solr</a>
	 * <br/>
	 * @param solrQuery is the entity already constructed. 
	 **/
	protected abstract void _solrQuery(SolrQuery o);

	public SolrQuery getSolrQuery() {
		return solrQuery;
	}

	public void setSolrQuery(SolrQuery solrQuery) {
		this.solrQuery = solrQuery;
		this.solrQueryWrap.alreadyInitialized = true;
	}
	public static SolrQuery staticSetSolrQuery(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SearchList solrQueryInit() {
		if(!solrQueryWrap.alreadyInitialized) {
			_solrQuery(solrQuery);
		}
		solrQueryWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	///////////////////
	// queryResponse //
	///////////////////

	/**	 The entity queryResponse
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected QueryResponse queryResponse;
	@JsonIgnore
	public Wrap<QueryResponse> queryResponseWrap = new Wrap<QueryResponse>().var("queryResponse").o(queryResponse);

	/**	<br/> The entity queryResponse
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:queryResponse">Find the entity queryResponse in Solr</a>
	 * <br/>
	 * @param promise is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _queryResponse(Promise<QueryResponse> promise);

	public QueryResponse getQueryResponse() {
		return queryResponse;
	}

	public void setQueryResponse(QueryResponse queryResponse) {
		this.queryResponse = queryResponse;
		this.queryResponseWrap.alreadyInitialized = true;
	}
	public static QueryResponse staticSetQueryResponse(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected Future<QueryResponse> queryResponsePromise() {
		Promise<QueryResponse> promise = Promise.promise();
		if(!queryResponseWrap.alreadyInitialized) {
			Promise<QueryResponse> promise2 = Promise.promise();
			_queryResponse(promise2);
			promise2.future().onSuccess(o -> {
				setQueryResponse(o);
				queryResponseWrap.alreadyInitialized(true);
				promise.complete(o);
			}).onFailure(ex -> {
				promise.fail(ex);
			});
		} else {
			promise.complete();
		}
		return promise.future();
	}

	//////////////////////
	// solrDocumentList //
	//////////////////////

	/**	 The entity solrDocumentList
	 *	 is defined as null before being initialized. 
	 */
	@JsonIgnore
	@JsonInclude(Include.NON_NULL)
	protected SolrDocumentList solrDocumentList;
	@JsonIgnore
	public Wrap<SolrDocumentList> solrDocumentListWrap = new Wrap<SolrDocumentList>().var("solrDocumentList").o(solrDocumentList);

	/**	<br/> The entity solrDocumentList
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:solrDocumentList">Find the entity solrDocumentList in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _solrDocumentList(Wrap<SolrDocumentList> c);

	public SolrDocumentList getSolrDocumentList() {
		return solrDocumentList;
	}

	public void setSolrDocumentList(SolrDocumentList solrDocumentList) {
		this.solrDocumentList = solrDocumentList;
		this.solrDocumentListWrap.alreadyInitialized = true;
	}
	public static SolrDocumentList staticSetSolrDocumentList(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SearchList solrDocumentListInit() {
		if(!solrDocumentListWrap.alreadyInitialized) {
			_solrDocumentList(solrDocumentListWrap);
			if(solrDocumentList == null)
				setSolrDocumentList(solrDocumentListWrap.o);
			solrDocumentListWrap.o(null);
		}
		solrDocumentListWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	//////////
	// list //
	//////////

	/**	 The entity list
	 *	Il est construit avant d'être initialisé avec le constructeur par défaut List<DEV>(). 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<DEV> list = new ArrayList<DEV>();
	@JsonIgnore
	public Wrap<List<DEV>> listWrap = new Wrap<List<DEV>>().var("list").o(list);

	/**	<br/> The entity list
	 *  It is constructed before being initialized with the constructor by default List<DEV>(). 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:list">Find the entity list in Solr</a>
	 * <br/>
	 * @param list is the entity already constructed. 
	 **/
	protected abstract void _list(List<DEV> l);

	public List<DEV> getList() {
		return list;
	}

	public void setList(List<DEV> list) {
		this.list = list;
		this.listWrap.alreadyInitialized = true;
	}
	public SearchList addList(DEV...objets) {
		for(DEV o : objets) {
			addList(o);
		}
		return (SearchList)this;
	}
	public SearchList addList(DEV o) {
		if(o != null && !list.contains(o))
			this.list.add(o);
		return (SearchList)this;
	}
	protected SearchList listInit() {
		if(!listWrap.alreadyInitialized) {
			_list(list);
		}
		listWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	///////////
	// first //
	///////////

	/**	 The entity first
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Object first;
	@JsonIgnore
	public Wrap<Object> firstWrap = new Wrap<Object>().var("first").o(first);

	/**	<br/> The entity first
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.search.SearchList&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:first">Find the entity first in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _first(Wrap<Object> w);

	public Object getFirst() {
		return first;
	}

	public void setFirst(Object first) {
		this.first = first;
		this.firstWrap.alreadyInitialized = true;
	}
	public static Object staticSetFirst(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected SearchList firstInit() {
		if(!firstWrap.alreadyInitialized) {
			_first(firstWrap);
			if(first == null)
				setFirst(firstWrap.o);
			firstWrap.o(null);
		}
		firstWrap.alreadyInitialized(true);
		return (SearchList)this;
	}

	//////////////
	// initDeep //
	//////////////

	protected boolean alreadyInitializedSearchList = false;

	public Future<Void> promiseDeepSearchList(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		if(!alreadyInitializedSearchList) {
			alreadyInitializedSearchList = true;
			return promiseDeepSearchList();
		} else {
			return Future.succeededFuture();
		}
	}

	public Future<Void> promiseDeepSearchList() {
		Promise<Void> promise = Promise.promise();
		Promise<Void> promise2 = Promise.promise();
		promiseSearchList(promise2);
		promise2.future().onSuccess(a -> {
			promise.complete();
		}).onFailure(ex -> {
			promise.fail(ex);
		});
		return promise.future();
	}

	public Future<Void> promiseSearchList(Promise<Void> promise) {
		Future.future(a -> a.complete()).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				cInit();
				siteRequest_Init();
				storeInit();
				populateInit();
				fieldsInit();
				solrQueryInit();
				promise2.complete();
			} catch(Exception ex) {
				promise2.fail(ex);
			}
			return promise2.future();
		}).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			queryResponsePromise().onSuccess(queryResponse -> {
				promise2.complete();
			}).onFailure(ex -> {
				promise2.fail(ex);
			});
			return promise2.future();
		}).compose(a -> {
			Promise<Void> promise2 = Promise.promise();
			try {
				solrDocumentListInit();
				listInit();
				firstInit();
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
		return promiseDeepSearchList(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestSearchList(SiteRequestEnUS siteRequest_) {
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestSearchList(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainSearchList(v);
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
	public Object obtainSearchList(String var) {
		SearchList oSearchList = (SearchList)this;
		switch(var) {
			case "c":
				return oSearchList.c;
			case "siteRequest_":
				return oSearchList.siteRequest_;
			case "store":
				return oSearchList.store;
			case "populate":
				return oSearchList.populate;
			case "fields":
				return oSearchList.fields;
			case "solrQuery":
				return oSearchList.solrQuery;
			case "queryResponse":
				return oSearchList.queryResponse;
			case "solrDocumentList":
				return oSearchList.solrDocumentList;
			case "list":
				return oSearchList.list;
			case "first":
				return oSearchList.first;
			default:
				return null;
		}
	}

	///////////////
	// attribute //
	///////////////

	public boolean attributeForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = attributeSearchList(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.attributeForClass(v, val);
			}
		}
		return o != null;
	}
	public Object attributeSearchList(String var, Object val) {
		SearchList oSearchList = (SearchList)this;
		switch(var) {
			default:
				return null;
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetSearchList(entityVar,  siteRequest_, o);
	}
	public static Object staticSetSearchList(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "store":
			return SearchList.staticSetStore(siteRequest_, o);
		case "populate":
			return SearchList.staticSetPopulate(siteRequest_, o);
		case "fields":
			return SearchList.staticSetFields(siteRequest_, o);
			default:
				return null;
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrSearchList(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrSearchList(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "store":
			return SearchList.staticSolrStore(siteRequest_, (Boolean)o);
		case "populate":
			return SearchList.staticSolrPopulate(siteRequest_, (Boolean)o);
		case "fields":
			return SearchList.staticSolrFields(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrSearchList(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrSearchList(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "store":
			return SearchList.staticSolrStrStore(siteRequest_, (Boolean)o);
		case "populate":
			return SearchList.staticSolrStrPopulate(siteRequest_, (Boolean)o);
		case "fields":
			return SearchList.staticSolrStrFields(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqSearchList(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqSearchList(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "store":
			return SearchList.staticSolrFqStore(siteRequest_, o);
		case "populate":
			return SearchList.staticSolrFqPopulate(siteRequest_, o);
		case "fields":
			return SearchList.staticSolrFqFields(siteRequest_, o);
			default:
				return null;
		}
	}

	/////////////
	// define //
	/////////////

	public boolean defineForClass(String var, String val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		if(val != null) {
			for(String v : vars) {
				if(o == null)
					o = defineSearchList(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineSearchList(String var, String val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	public boolean defineForClass(String var, Object val) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		if(val != null) {
			for(String v : vars) {
				if(o == null)
					o = defineSearchList(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineSearchList(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestSearchList() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof SearchList) {
			SearchList original = (SearchList)o;
		}
	}

	//////////////
	// hashCode //
	//////////////

	@Override public int hashCode() {
		return Objects.hash();
	}

	////////////
	// equals //
	////////////

	@Override public boolean equals(Object o) {
		if(this == o)
			return true;
		if(!(o instanceof SearchList))
			return false;
		SearchList that = (SearchList)o;
		return true;
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		sb.append("SearchList { ");
		sb.append(" }");
		return sb.toString();
	}

	public static final String VAR_c = "c";
	public static final String VAR_siteRequest_ = "siteRequest_";
	public static final String VAR_store = "store";
	public static final String VAR_populate = "populate";
	public static final String VAR_fields = "fields";
	public static final String VAR_solrQuery = "solrQuery";
	public static final String VAR_queryResponse = "queryResponse";
	public static final String VAR_solrDocumentList = "solrDocumentList";
	public static final String VAR_list = "list";
	public static final String VAR_first = "first";
}
