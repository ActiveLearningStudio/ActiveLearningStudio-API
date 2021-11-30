package org.curriki.api.enus.writer;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.Arrays;
import com.fasterxml.jackson.databind.ser.std.ToStringSerializer;
import org.curriki.api.enus.base.BaseModel;
import org.curriki.api.enus.request.api.ApiRequest;
import org.slf4j.LoggerFactory;
import org.curriki.api.enus.writer.AllWriter;
import java.util.HashMap;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.apache.commons.lang3.StringUtils;
import java.lang.Integer;
import java.text.NumberFormat;
import java.util.ArrayList;
import org.curriki.api.enus.wrap.Wrap;
import org.curriki.api.enus.java.ZonedDateTimeDeserializer;
import org.apache.commons.collections.CollectionUtils;
import org.curriki.api.enus.vertx.AppSwagger2;
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
import org.curriki.api.enus.writer.AllWriters;
import io.vertx.core.Promise;
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
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import java.lang.Object;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class ApiWriterGen<DEV> extends Object {
	protected static final Logger LOG = LoggerFactory.getLogger(ApiWriter.class);

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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteRequest_">Find the entity siteRequest_ in Solr</a>
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
	protected ApiWriter siteRequest_Init() {
		Wrap<SiteRequestEnUS> siteRequest_Wrap = new Wrap<SiteRequestEnUS>().var("siteRequest_");
		if(siteRequest_ == null) {
			_siteRequest_(siteRequest_Wrap);
			setSiteRequest_(siteRequest_Wrap.o);
		}
		return (ApiWriter)this;
	}

	///////////////////////
	// classSolrDocument //
	///////////////////////

	/**	 The entity classSolrDocument
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SolrDocument classSolrDocument;

	/**	<br/> The entity classSolrDocument
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classSolrDocument">Find the entity classSolrDocument in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classSolrDocument(Wrap<SolrDocument> c);

	public SolrDocument getClassSolrDocument() {
		return classSolrDocument;
	}

	public void setClassSolrDocument(SolrDocument classSolrDocument) {
		this.classSolrDocument = classSolrDocument;
	}
	public static SolrDocument staticSetClassSolrDocument(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter classSolrDocumentInit() {
		Wrap<SolrDocument> classSolrDocumentWrap = new Wrap<SolrDocument>().var("classSolrDocument");
		if(classSolrDocument == null) {
			_classSolrDocument(classSolrDocumentWrap);
			setClassSolrDocument(classSolrDocumentWrap.o);
		}
		return (ApiWriter)this;
	}

	/////////////////
	// contextRows //
	/////////////////

	/**	 The entity contextRows
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer contextRows;

	/**	<br/> The entity contextRows
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:contextRows">Find the entity contextRows in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _contextRows(Wrap<Integer> c);

	public Integer getContextRows() {
		return contextRows;
	}

	public void setContextRows(Integer contextRows) {
		this.contextRows = contextRows;
	}
	@JsonIgnore
	public void setContextRows(String o) {
		this.contextRows = ApiWriter.staticSetContextRows(siteRequest_, o);
	}
	public static Integer staticSetContextRows(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected ApiWriter contextRowsInit() {
		Wrap<Integer> contextRowsWrap = new Wrap<Integer>().var("contextRows");
		if(contextRows == null) {
			_contextRows(contextRowsWrap);
			setContextRows(contextRowsWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Integer staticSolrContextRows(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrContextRows(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqContextRows(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrContextRows(siteRequest_, ApiWriter.staticSolrContextRows(siteRequest_, ApiWriter.staticSetContextRows(siteRequest_, o)));
	}

	////////////////////
	// classApiMethod //
	////////////////////

	/**	 The entity classApiMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiMethod;

	/**	<br/> The entity classApiMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiMethod">Find the entity classApiMethod in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiMethod(Wrap<String> c);

	public String getClassApiMethod() {
		return classApiMethod;
	}
	public void setClassApiMethod(String o) {
		this.classApiMethod = ApiWriter.staticSetClassApiMethod(siteRequest_, o);
	}
	public static String staticSetClassApiMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiMethodInit() {
		Wrap<String> classApiMethodWrap = new Wrap<String>().var("classApiMethod");
		if(classApiMethod == null) {
			_classApiMethod(classApiMethodWrap);
			setClassApiMethod(classApiMethodWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiMethod(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiMethod(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiMethod(siteRequest_, ApiWriter.staticSolrClassApiMethod(siteRequest_, ApiWriter.staticSetClassApiMethod(siteRequest_, o)));
	}

	////////////////////
	// openApiVersion //
	////////////////////

	/**	 The entity openApiVersion
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String openApiVersion;

	/**	<br/> The entity openApiVersion
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:openApiVersion">Find the entity openApiVersion in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _openApiVersion(Wrap<String> c);

	public String getOpenApiVersion() {
		return openApiVersion;
	}
	public void setOpenApiVersion(String o) {
		this.openApiVersion = ApiWriter.staticSetOpenApiVersion(siteRequest_, o);
	}
	public static String staticSetOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter openApiVersionInit() {
		Wrap<String> openApiVersionWrap = new Wrap<String>().var("openApiVersion");
		if(openApiVersion == null) {
			_openApiVersion(openApiVersionWrap);
			setOpenApiVersion(openApiVersionWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrOpenApiVersion(siteRequest_, ApiWriter.staticSolrOpenApiVersion(siteRequest_, ApiWriter.staticSetOpenApiVersion(siteRequest_, o)));
	}

	/////////////////
	// appSwagger2 //
	/////////////////

	/**	 The entity appSwagger2
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AppSwagger2 appSwagger2;

	/**	<br/> The entity appSwagger2
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:appSwagger2">Find the entity appSwagger2 in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _appSwagger2(Wrap<AppSwagger2> c);

	public AppSwagger2 getAppSwagger2() {
		return appSwagger2;
	}

	public void setAppSwagger2(AppSwagger2 appSwagger2) {
		this.appSwagger2 = appSwagger2;
	}
	public static AppSwagger2 staticSetAppSwagger2(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter appSwagger2Init() {
		Wrap<AppSwagger2> appSwagger2Wrap = new Wrap<AppSwagger2>().var("appSwagger2");
		if(appSwagger2 == null) {
			_appSwagger2(appSwagger2Wrap);
			setAppSwagger2(appSwagger2Wrap.o);
		}
		if(appSwagger2 != null)
			appSwagger2.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	///////////////
	// classUris //
	///////////////

	/**	 The entity classUris
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> classUris;

	/**	<br/> The entity classUris
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classUris">Find the entity classUris in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classUris(Wrap<List<String>> c);

	public List<String> getClassUris() {
		return classUris;
	}

	public void setClassUris(List<String> classUris) {
		this.classUris = classUris;
	}
	public static String staticSetClassUris(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public ApiWriter addClassUris(String...objets) {
		for(String o : objets) {
			addClassUris(o);
		}
		return (ApiWriter)this;
	}
	public ApiWriter addClassUris(String o) {
		if(o != null)
			this.classUris.add(o);
		return (ApiWriter)this;
	}
	@JsonIgnore
	public void setClassUris(JsonArray objets) {
		classUris.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addClassUris(o);
		}
	}
	protected ApiWriter classUrisInit() {
		Wrap<List<String>> classUrisWrap = new Wrap<List<String>>().var("classUris");
		if(classUris == null) {
			_classUris(classUrisWrap);
			setClassUris(classUrisWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassUris(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassUris(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassUris(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassUris(siteRequest_, ApiWriter.staticSolrClassUris(siteRequest_, ApiWriter.staticSetClassUris(siteRequest_, o)));
	}

	//////////////////////////
	// openApiVersionNumber //
	//////////////////////////

	/**	 The entity openApiVersionNumber
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer openApiVersionNumber;

	/**	<br/> The entity openApiVersionNumber
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:openApiVersionNumber">Find the entity openApiVersionNumber in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _openApiVersionNumber(Wrap<Integer> c);

	public Integer getOpenApiVersionNumber() {
		return openApiVersionNumber;
	}

	public void setOpenApiVersionNumber(Integer openApiVersionNumber) {
		this.openApiVersionNumber = openApiVersionNumber;
	}
	@JsonIgnore
	public void setOpenApiVersionNumber(String o) {
		this.openApiVersionNumber = ApiWriter.staticSetOpenApiVersionNumber(siteRequest_, o);
	}
	public static Integer staticSetOpenApiVersionNumber(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected ApiWriter openApiVersionNumberInit() {
		Wrap<Integer> openApiVersionNumberWrap = new Wrap<Integer>().var("openApiVersionNumber");
		if(openApiVersionNumber == null) {
			_openApiVersionNumber(openApiVersionNumberWrap);
			setOpenApiVersionNumber(openApiVersionNumberWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Integer staticSolrOpenApiVersionNumber(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrOpenApiVersionNumber(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqOpenApiVersionNumber(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrOpenApiVersionNumber(siteRequest_, ApiWriter.staticSolrOpenApiVersionNumber(siteRequest_, ApiWriter.staticSetOpenApiVersionNumber(siteRequest_, o)));
	}

	////////////////
	// tabsSchema //
	////////////////

	/**	 The entity tabsSchema
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer tabsSchema;

	/**	<br/> The entity tabsSchema
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:tabsSchema">Find the entity tabsSchema in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _tabsSchema(Wrap<Integer> c);

	public Integer getTabsSchema() {
		return tabsSchema;
	}

	public void setTabsSchema(Integer tabsSchema) {
		this.tabsSchema = tabsSchema;
	}
	@JsonIgnore
	public void setTabsSchema(String o) {
		this.tabsSchema = ApiWriter.staticSetTabsSchema(siteRequest_, o);
	}
	public static Integer staticSetTabsSchema(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected ApiWriter tabsSchemaInit() {
		Wrap<Integer> tabsSchemaWrap = new Wrap<Integer>().var("tabsSchema");
		if(tabsSchema == null) {
			_tabsSchema(tabsSchemaWrap);
			setTabsSchema(tabsSchemaWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Integer staticSolrTabsSchema(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrTabsSchema(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqTabsSchema(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrTabsSchema(siteRequest_, ApiWriter.staticSolrTabsSchema(siteRequest_, ApiWriter.staticSetTabsSchema(siteRequest_, o)));
	}

	///////////////////
	// tabsResponses //
	///////////////////

	/**	 The entity tabsResponses
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonSerialize(using = ToStringSerializer.class)
	@JsonInclude(Include.NON_NULL)
	protected Integer tabsResponses;

	/**	<br/> The entity tabsResponses
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:tabsResponses">Find the entity tabsResponses in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _tabsResponses(Wrap<Integer> c);

	public Integer getTabsResponses() {
		return tabsResponses;
	}

	public void setTabsResponses(Integer tabsResponses) {
		this.tabsResponses = tabsResponses;
	}
	@JsonIgnore
	public void setTabsResponses(String o) {
		this.tabsResponses = ApiWriter.staticSetTabsResponses(siteRequest_, o);
	}
	public static Integer staticSetTabsResponses(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected ApiWriter tabsResponsesInit() {
		Wrap<Integer> tabsResponsesWrap = new Wrap<Integer>().var("tabsResponses");
		if(tabsResponses == null) {
			_tabsResponses(tabsResponsesWrap);
			setTabsResponses(tabsResponsesWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Integer staticSolrTabsResponses(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrTabsResponses(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqTabsResponses(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrTabsResponses(siteRequest_, ApiWriter.staticSolrTabsResponses(siteRequest_, ApiWriter.staticSetTabsResponses(siteRequest_, o)));
	}

	////////////
	// wPaths //
	////////////

	/**	 The entity wPaths
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wPaths;

	/**	<br/> The entity wPaths
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wPaths">Find the entity wPaths in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wPaths(Wrap<AllWriter> c);

	public AllWriter getWPaths() {
		return wPaths;
	}

	public void setWPaths(AllWriter wPaths) {
		this.wPaths = wPaths;
	}
	public static AllWriter staticSetWPaths(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wPathsInit() {
		Wrap<AllWriter> wPathsWrap = new Wrap<AllWriter>().var("wPaths");
		if(wPaths == null) {
			_wPaths(wPathsWrap);
			setWPaths(wPathsWrap.o);
		}
		if(wPaths != null)
			wPaths.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	////////////////////
	// wRequestBodies //
	////////////////////

	/**	 The entity wRequestBodies
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wRequestBodies;

	/**	<br/> The entity wRequestBodies
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wRequestBodies">Find the entity wRequestBodies in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wRequestBodies(Wrap<AllWriter> c);

	public AllWriter getWRequestBodies() {
		return wRequestBodies;
	}

	public void setWRequestBodies(AllWriter wRequestBodies) {
		this.wRequestBodies = wRequestBodies;
	}
	public static AllWriter staticSetWRequestBodies(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wRequestBodiesInit() {
		Wrap<AllWriter> wRequestBodiesWrap = new Wrap<AllWriter>().var("wRequestBodies");
		if(wRequestBodies == null) {
			_wRequestBodies(wRequestBodiesWrap);
			setWRequestBodies(wRequestBodiesWrap.o);
		}
		if(wRequestBodies != null)
			wRequestBodies.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	//////////////
	// wSchemas //
	//////////////

	/**	 The entity wSchemas
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wSchemas;

	/**	<br/> The entity wSchemas
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wSchemas">Find the entity wSchemas in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wSchemas(Wrap<AllWriter> c);

	public AllWriter getWSchemas() {
		return wSchemas;
	}

	public void setWSchemas(AllWriter wSchemas) {
		this.wSchemas = wSchemas;
	}
	public static AllWriter staticSetWSchemas(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wSchemasInit() {
		Wrap<AllWriter> wSchemasWrap = new Wrap<AllWriter>().var("wSchemas");
		if(wSchemas == null) {
			_wSchemas(wSchemasWrap);
			setWSchemas(wSchemasWrap.o);
		}
		if(wSchemas != null)
			wSchemas.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:config">Find the entity config in Solr</a>
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
	protected ApiWriter configInit() {
		Wrap<JsonObject> configWrap = new Wrap<JsonObject>().var("config");
		if(config == null) {
			_config(configWrap);
			setConfig(configWrap.o);
		}
		return (ApiWriter)this;
	}

	/////////////////////////
	// solrClientComputate //
	/////////////////////////

	/**	 The entity solrClientComputate
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SolrClient solrClientComputate;

	/**	<br/> The entity solrClientComputate
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:solrClientComputate">Find the entity solrClientComputate in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _solrClientComputate(Wrap<SolrClient> c);

	public SolrClient getSolrClientComputate() {
		return solrClientComputate;
	}

	public void setSolrClientComputate(SolrClient solrClientComputate) {
		this.solrClientComputate = solrClientComputate;
	}
	public static SolrClient staticSetSolrClientComputate(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter solrClientComputateInit() {
		Wrap<SolrClient> solrClientComputateWrap = new Wrap<SolrClient>().var("solrClientComputate");
		if(solrClientComputate == null) {
			_solrClientComputate(solrClientComputateWrap);
			setSolrClientComputate(solrClientComputateWrap.o);
		}
		return (ApiWriter)this;
	}

	/////////////////////
	// wRequestHeaders //
	/////////////////////

	/**	 The entity wRequestHeaders
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wRequestHeaders;

	/**	<br/> The entity wRequestHeaders
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wRequestHeaders">Find the entity wRequestHeaders in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wRequestHeaders(Wrap<AllWriter> c);

	public AllWriter getWRequestHeaders() {
		return wRequestHeaders;
	}

	public void setWRequestHeaders(AllWriter wRequestHeaders) {
		this.wRequestHeaders = wRequestHeaders;
	}
	public static AllWriter staticSetWRequestHeaders(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wRequestHeadersInit() {
		Wrap<AllWriter> wRequestHeadersWrap = new Wrap<AllWriter>().var("wRequestHeaders");
		if(wRequestHeaders == null) {
			_wRequestHeaders(wRequestHeadersWrap);
			setWRequestHeaders(wRequestHeadersWrap.o);
		}
		if(wRequestHeaders != null)
			wRequestHeaders.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	/////////////////////////
	// wRequestDescription //
	/////////////////////////

	/**	 The entity wRequestDescription
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wRequestDescription;

	/**	<br/> The entity wRequestDescription
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wRequestDescription">Find the entity wRequestDescription in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wRequestDescription(Wrap<AllWriter> c);

	public AllWriter getWRequestDescription() {
		return wRequestDescription;
	}

	public void setWRequestDescription(AllWriter wRequestDescription) {
		this.wRequestDescription = wRequestDescription;
	}
	public static AllWriter staticSetWRequestDescription(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wRequestDescriptionInit() {
		Wrap<AllWriter> wRequestDescriptionWrap = new Wrap<AllWriter>().var("wRequestDescription");
		if(wRequestDescription == null) {
			_wRequestDescription(wRequestDescriptionWrap);
			setWRequestDescription(wRequestDescriptionWrap.o);
		}
		if(wRequestDescription != null)
			wRequestDescription.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	//////////////////////////
	// wResponseDescription //
	//////////////////////////

	/**	 The entity wResponseDescription
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wResponseDescription;

	/**	<br/> The entity wResponseDescription
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wResponseDescription">Find the entity wResponseDescription in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wResponseDescription(Wrap<AllWriter> c);

	public AllWriter getWResponseDescription() {
		return wResponseDescription;
	}

	public void setWResponseDescription(AllWriter wResponseDescription) {
		this.wResponseDescription = wResponseDescription;
	}
	public static AllWriter staticSetWResponseDescription(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wResponseDescriptionInit() {
		Wrap<AllWriter> wResponseDescriptionWrap = new Wrap<AllWriter>().var("wResponseDescription");
		if(wResponseDescription == null) {
			_wResponseDescription(wResponseDescriptionWrap);
			setWResponseDescription(wResponseDescriptionWrap.o);
		}
		if(wResponseDescription != null)
			wResponseDescription.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	//////////////////
	// wRequestBody //
	//////////////////

	/**	 The entity wRequestBody
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wRequestBody;

	/**	<br/> The entity wRequestBody
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wRequestBody">Find the entity wRequestBody in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wRequestBody(Wrap<AllWriter> c);

	public AllWriter getWRequestBody() {
		return wRequestBody;
	}

	public void setWRequestBody(AllWriter wRequestBody) {
		this.wRequestBody = wRequestBody;
	}
	public static AllWriter staticSetWRequestBody(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wRequestBodyInit() {
		Wrap<AllWriter> wRequestBodyWrap = new Wrap<AllWriter>().var("wRequestBody");
		if(wRequestBody == null) {
			_wRequestBody(wRequestBodyWrap);
			setWRequestBody(wRequestBodyWrap.o);
		}
		if(wRequestBody != null)
			wRequestBody.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	///////////////////
	// wResponseBody //
	///////////////////

	/**	 The entity wResponseBody
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wResponseBody;

	/**	<br/> The entity wResponseBody
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wResponseBody">Find the entity wResponseBody in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wResponseBody(Wrap<AllWriter> c);

	public AllWriter getWResponseBody() {
		return wResponseBody;
	}

	public void setWResponseBody(AllWriter wResponseBody) {
		this.wResponseBody = wResponseBody;
	}
	public static AllWriter staticSetWResponseBody(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wResponseBodyInit() {
		Wrap<AllWriter> wResponseBodyWrap = new Wrap<AllWriter>().var("wResponseBody");
		if(wResponseBody == null) {
			_wResponseBody(wResponseBodyWrap);
			setWResponseBody(wResponseBodyWrap.o);
		}
		if(wResponseBody != null)
			wResponseBody.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	////////////////////
	// wRequestSchema //
	////////////////////

	/**	 The entity wRequestSchema
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wRequestSchema;

	/**	<br/> The entity wRequestSchema
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wRequestSchema">Find the entity wRequestSchema in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wRequestSchema(Wrap<AllWriter> c);

	public AllWriter getWRequestSchema() {
		return wRequestSchema;
	}

	public void setWRequestSchema(AllWriter wRequestSchema) {
		this.wRequestSchema = wRequestSchema;
	}
	public static AllWriter staticSetWRequestSchema(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wRequestSchemaInit() {
		Wrap<AllWriter> wRequestSchemaWrap = new Wrap<AllWriter>().var("wRequestSchema");
		if(wRequestSchema == null) {
			_wRequestSchema(wRequestSchemaWrap);
			setWRequestSchema(wRequestSchemaWrap.o);
		}
		if(wRequestSchema != null)
			wRequestSchema.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	/////////////////////
	// wResponseSchema //
	/////////////////////

	/**	 The entity wResponseSchema
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter wResponseSchema;

	/**	<br/> The entity wResponseSchema
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wResponseSchema">Find the entity wResponseSchema in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _wResponseSchema(Wrap<AllWriter> c);

	public AllWriter getWResponseSchema() {
		return wResponseSchema;
	}

	public void setWResponseSchema(AllWriter wResponseSchema) {
		this.wResponseSchema = wResponseSchema;
	}
	public static AllWriter staticSetWResponseSchema(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter wResponseSchemaInit() {
		Wrap<AllWriter> wResponseSchemaWrap = new Wrap<AllWriter>().var("wResponseSchema");
		if(wResponseSchema == null) {
			_wResponseSchema(wResponseSchemaWrap);
			setWResponseSchema(wResponseSchemaWrap.o);
		}
		if(wResponseSchema != null)
			wResponseSchema.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	/////////////
	// writers //
	/////////////

	/**	 The entity writers
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriters writers;

	/**	<br/> The entity writers
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:writers">Find the entity writers in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _writers(Wrap<AllWriters> c);

	public AllWriters getWriters() {
		return writers;
	}

	public void setWriters(AllWriters writers) {
		this.writers = writers;
	}
	public static AllWriters staticSetWriters(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter writersInit() {
		Wrap<AllWriters> writersWrap = new Wrap<AllWriters>().var("writers");
		if(writers == null) {
			_writers(writersWrap);
			setWriters(writersWrap.o);
		}
		if(writers != null)
			writers.initDeepForClass(siteRequest_);
		return (ApiWriter)this;
	}

	/////////////////
	// classApiTag //
	/////////////////

	/**	 The entity classApiTag
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiTag;

	/**	<br/> The entity classApiTag
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiTag">Find the entity classApiTag in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiTag(Wrap<String> c);

	public String getClassApiTag() {
		return classApiTag;
	}
	public void setClassApiTag(String o) {
		this.classApiTag = ApiWriter.staticSetClassApiTag(siteRequest_, o);
	}
	public static String staticSetClassApiTag(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiTagInit() {
		Wrap<String> classApiTagWrap = new Wrap<String>().var("classApiTag");
		if(classApiTag == null) {
			_classApiTag(classApiTagWrap);
			setClassApiTag(classApiTagWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiTag(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiTag(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiTag(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiTag(siteRequest_, ApiWriter.staticSolrClassApiTag(siteRequest_, ApiWriter.staticSetClassApiTag(siteRequest_, o)));
	}

	//////////////////////
	// classExtendsBase //
	//////////////////////

	/**	 The entity classExtendsBase
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classExtendsBase;

	/**	<br/> The entity classExtendsBase
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classExtendsBase">Find the entity classExtendsBase in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classExtendsBase(Wrap<Boolean> c);

	public Boolean getClassExtendsBase() {
		return classExtendsBase;
	}

	public void setClassExtendsBase(Boolean classExtendsBase) {
		this.classExtendsBase = classExtendsBase;
	}
	@JsonIgnore
	public void setClassExtendsBase(String o) {
		this.classExtendsBase = ApiWriter.staticSetClassExtendsBase(siteRequest_, o);
	}
	public static Boolean staticSetClassExtendsBase(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classExtendsBaseInit() {
		Wrap<Boolean> classExtendsBaseWrap = new Wrap<Boolean>().var("classExtendsBase");
		if(classExtendsBase == null) {
			_classExtendsBase(classExtendsBaseWrap);
			setClassExtendsBase(classExtendsBaseWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassExtendsBase(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassExtendsBase(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassExtendsBase(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassExtendsBase(siteRequest_, ApiWriter.staticSolrClassExtendsBase(siteRequest_, ApiWriter.staticSetClassExtendsBase(siteRequest_, o)));
	}

	/////////////////
	// classIsBase //
	/////////////////

	/**	 The entity classIsBase
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classIsBase;

	/**	<br/> The entity classIsBase
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classIsBase">Find the entity classIsBase in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classIsBase(Wrap<Boolean> c);

	public Boolean getClassIsBase() {
		return classIsBase;
	}

	public void setClassIsBase(Boolean classIsBase) {
		this.classIsBase = classIsBase;
	}
	@JsonIgnore
	public void setClassIsBase(String o) {
		this.classIsBase = ApiWriter.staticSetClassIsBase(siteRequest_, o);
	}
	public static Boolean staticSetClassIsBase(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classIsBaseInit() {
		Wrap<Boolean> classIsBaseWrap = new Wrap<Boolean>().var("classIsBase");
		if(classIsBase == null) {
			_classIsBase(classIsBaseWrap);
			setClassIsBase(classIsBaseWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassIsBase(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassIsBase(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassIsBase(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassIsBase(siteRequest_, ApiWriter.staticSolrClassIsBase(siteRequest_, ApiWriter.staticSetClassIsBase(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classSimpleName">Find the entity classSimpleName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classSimpleName(Wrap<String> c);

	public String getClassSimpleName() {
		return classSimpleName;
	}
	public void setClassSimpleName(String o) {
		this.classSimpleName = ApiWriter.staticSetClassSimpleName(siteRequest_, o);
	}
	public static String staticSetClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classSimpleNameInit() {
		Wrap<String> classSimpleNameWrap = new Wrap<String>().var("classSimpleName");
		if(classSimpleName == null) {
			_classSimpleName(classSimpleNameWrap);
			setClassSimpleName(classSimpleNameWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassSimpleName(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassSimpleName(siteRequest_, ApiWriter.staticSolrClassSimpleName(siteRequest_, ApiWriter.staticSetClassSimpleName(siteRequest_, o)));
	}

	/////////////
	// appName //
	/////////////

	/**	 The entity appName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String appName;

	/**	<br/> The entity appName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:appName">Find the entity appName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _appName(Wrap<String> c);

	public String getAppName() {
		return appName;
	}
	public void setAppName(String o) {
		this.appName = ApiWriter.staticSetAppName(siteRequest_, o);
	}
	public static String staticSetAppName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter appNameInit() {
		Wrap<String> appNameWrap = new Wrap<String>().var("appName");
		if(appName == null) {
			_appName(appNameWrap);
			setAppName(appNameWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrAppName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrAppName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqAppName(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrAppName(siteRequest_, ApiWriter.staticSolrAppName(siteRequest_, ApiWriter.staticSetAppName(siteRequest_, o)));
	}

	///////////////////////
	// classAbsolutePath //
	///////////////////////

	/**	 The entity classAbsolutePath
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classAbsolutePath;

	/**	<br/> The entity classAbsolutePath
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classAbsolutePath">Find the entity classAbsolutePath in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classAbsolutePath(Wrap<String> c);

	public String getClassAbsolutePath() {
		return classAbsolutePath;
	}
	public void setClassAbsolutePath(String o) {
		this.classAbsolutePath = ApiWriter.staticSetClassAbsolutePath(siteRequest_, o);
	}
	public static String staticSetClassAbsolutePath(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classAbsolutePathInit() {
		Wrap<String> classAbsolutePathWrap = new Wrap<String>().var("classAbsolutePath");
		if(classAbsolutePath == null) {
			_classAbsolutePath(classAbsolutePathWrap);
			setClassAbsolutePath(classAbsolutePathWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassAbsolutePath(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassAbsolutePath(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassAbsolutePath(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassAbsolutePath(siteRequest_, ApiWriter.staticSolrClassAbsolutePath(siteRequest_, ApiWriter.staticSetClassAbsolutePath(siteRequest_, o)));
	}

	///////////////////////
	// classApiUriMethod //
	///////////////////////

	/**	 The entity classApiUriMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiUriMethod;

	/**	<br/> The entity classApiUriMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiUriMethod">Find the entity classApiUriMethod in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiUriMethod(Wrap<String> c);

	public String getClassApiUriMethod() {
		return classApiUriMethod;
	}
	public void setClassApiUriMethod(String o) {
		this.classApiUriMethod = ApiWriter.staticSetClassApiUriMethod(siteRequest_, o);
	}
	public static String staticSetClassApiUriMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiUriMethodInit() {
		Wrap<String> classApiUriMethodWrap = new Wrap<String>().var("classApiUriMethod");
		if(classApiUriMethod == null) {
			_classApiUriMethod(classApiUriMethodWrap);
			setClassApiUriMethod(classApiUriMethodWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiUriMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiUriMethod(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiUriMethod(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiUriMethod(siteRequest_, ApiWriter.staticSolrClassApiUriMethod(siteRequest_, ApiWriter.staticSetClassApiUriMethod(siteRequest_, o)));
	}

	/////////////////////////
	// classRoleUserMethod //
	/////////////////////////

	/**	 The entity classRoleUserMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classRoleUserMethod;

	/**	<br/> The entity classRoleUserMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classRoleUserMethod">Find the entity classRoleUserMethod in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classRoleUserMethod(Wrap<Boolean> c);

	public Boolean getClassRoleUserMethod() {
		return classRoleUserMethod;
	}

	public void setClassRoleUserMethod(Boolean classRoleUserMethod) {
		this.classRoleUserMethod = classRoleUserMethod;
	}
	@JsonIgnore
	public void setClassRoleUserMethod(String o) {
		this.classRoleUserMethod = ApiWriter.staticSetClassRoleUserMethod(siteRequest_, o);
	}
	public static Boolean staticSetClassRoleUserMethod(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classRoleUserMethodInit() {
		Wrap<Boolean> classRoleUserMethodWrap = new Wrap<Boolean>().var("classRoleUserMethod");
		if(classRoleUserMethod == null) {
			_classRoleUserMethod(classRoleUserMethodWrap);
			setClassRoleUserMethod(classRoleUserMethodWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassRoleUserMethod(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassRoleUserMethod(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassRoleUserMethod(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassRoleUserMethod(siteRequest_, ApiWriter.staticSolrClassRoleUserMethod(siteRequest_, ApiWriter.staticSetClassRoleUserMethod(siteRequest_, o)));
	}

	//////////////////////////
	// classApiMethodMethod //
	//////////////////////////

	/**	 The entity classApiMethodMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiMethodMethod;

	/**	<br/> The entity classApiMethodMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiMethodMethod">Find the entity classApiMethodMethod in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiMethodMethod(Wrap<String> c);

	public String getClassApiMethodMethod() {
		return classApiMethodMethod;
	}
	public void setClassApiMethodMethod(String o) {
		this.classApiMethodMethod = ApiWriter.staticSetClassApiMethodMethod(siteRequest_, o);
	}
	public static String staticSetClassApiMethodMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiMethodMethodInit() {
		Wrap<String> classApiMethodMethodWrap = new Wrap<String>().var("classApiMethodMethod");
		if(classApiMethodMethod == null) {
			_classApiMethodMethod(classApiMethodMethodWrap);
			setClassApiMethodMethod(classApiMethodMethodWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiMethodMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiMethodMethod(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiMethodMethod(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiMethodMethod(siteRequest_, ApiWriter.staticSolrClassApiMethodMethod(siteRequest_, ApiWriter.staticSetClassApiMethodMethod(siteRequest_, o)));
	}

	////////////////////////////////
	// classApiMediaType200Method //
	////////////////////////////////

	/**	 The entity classApiMediaType200Method
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiMediaType200Method;

	/**	<br/> The entity classApiMediaType200Method
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiMediaType200Method">Find the entity classApiMediaType200Method in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiMediaType200Method(Wrap<String> c);

	public String getClassApiMediaType200Method() {
		return classApiMediaType200Method;
	}
	public void setClassApiMediaType200Method(String o) {
		this.classApiMediaType200Method = ApiWriter.staticSetClassApiMediaType200Method(siteRequest_, o);
	}
	public static String staticSetClassApiMediaType200Method(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiMediaType200MethodInit() {
		Wrap<String> classApiMediaType200MethodWrap = new Wrap<String>().var("classApiMediaType200Method");
		if(classApiMediaType200Method == null) {
			_classApiMediaType200Method(classApiMediaType200MethodWrap);
			setClassApiMediaType200Method(classApiMediaType200MethodWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiMediaType200Method(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiMediaType200Method(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiMediaType200Method(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiMediaType200Method(siteRequest_, ApiWriter.staticSolrClassApiMediaType200Method(siteRequest_, ApiWriter.staticSetClassApiMediaType200Method(siteRequest_, o)));
	}

	///////////////////////////////
	// classApiOperationIdMethod //
	///////////////////////////////

	/**	 The entity classApiOperationIdMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiOperationIdMethod;

	/**	<br/> The entity classApiOperationIdMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiOperationIdMethod">Find the entity classApiOperationIdMethod in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiOperationIdMethod(Wrap<String> c);

	public String getClassApiOperationIdMethod() {
		return classApiOperationIdMethod;
	}
	public void setClassApiOperationIdMethod(String o) {
		this.classApiOperationIdMethod = ApiWriter.staticSetClassApiOperationIdMethod(siteRequest_, o);
	}
	public static String staticSetClassApiOperationIdMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiOperationIdMethodInit() {
		Wrap<String> classApiOperationIdMethodWrap = new Wrap<String>().var("classApiOperationIdMethod");
		if(classApiOperationIdMethod == null) {
			_classApiOperationIdMethod(classApiOperationIdMethodWrap);
			setClassApiOperationIdMethod(classApiOperationIdMethodWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiOperationIdMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiOperationIdMethod(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiOperationIdMethod(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiOperationIdMethod(siteRequest_, ApiWriter.staticSolrClassApiOperationIdMethod(siteRequest_, ApiWriter.staticSetClassApiOperationIdMethod(siteRequest_, o)));
	}

	//////////////////////////////////////
	// classApiOperationIdMethodRequest //
	//////////////////////////////////////

	/**	 The entity classApiOperationIdMethodRequest
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiOperationIdMethodRequest;

	/**	<br/> The entity classApiOperationIdMethodRequest
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiOperationIdMethodRequest">Find the entity classApiOperationIdMethodRequest in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiOperationIdMethodRequest(Wrap<String> c);

	public String getClassApiOperationIdMethodRequest() {
		return classApiOperationIdMethodRequest;
	}
	public void setClassApiOperationIdMethodRequest(String o) {
		this.classApiOperationIdMethodRequest = ApiWriter.staticSetClassApiOperationIdMethodRequest(siteRequest_, o);
	}
	public static String staticSetClassApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiOperationIdMethodRequestInit() {
		Wrap<String> classApiOperationIdMethodRequestWrap = new Wrap<String>().var("classApiOperationIdMethodRequest");
		if(classApiOperationIdMethodRequest == null) {
			_classApiOperationIdMethodRequest(classApiOperationIdMethodRequestWrap);
			setClassApiOperationIdMethodRequest(classApiOperationIdMethodRequestWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiOperationIdMethodRequest(siteRequest_, ApiWriter.staticSolrClassApiOperationIdMethodRequest(siteRequest_, ApiWriter.staticSetClassApiOperationIdMethodRequest(siteRequest_, o)));
	}

	///////////////////////////////////////
	// classApiOperationIdMethodResponse //
	///////////////////////////////////////

	/**	 The entity classApiOperationIdMethodResponse
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classApiOperationIdMethodResponse;

	/**	<br/> The entity classApiOperationIdMethodResponse
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classApiOperationIdMethodResponse">Find the entity classApiOperationIdMethodResponse in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classApiOperationIdMethodResponse(Wrap<String> c);

	public String getClassApiOperationIdMethodResponse() {
		return classApiOperationIdMethodResponse;
	}
	public void setClassApiOperationIdMethodResponse(String o) {
		this.classApiOperationIdMethodResponse = ApiWriter.staticSetClassApiOperationIdMethodResponse(siteRequest_, o);
	}
	public static String staticSetClassApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classApiOperationIdMethodResponseInit() {
		Wrap<String> classApiOperationIdMethodResponseWrap = new Wrap<String>().var("classApiOperationIdMethodResponse");
		if(classApiOperationIdMethodResponse == null) {
			_classApiOperationIdMethodResponse(classApiOperationIdMethodResponseWrap);
			setClassApiOperationIdMethodResponse(classApiOperationIdMethodResponseWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassApiOperationIdMethodResponse(siteRequest_, ApiWriter.staticSolrClassApiOperationIdMethodResponse(siteRequest_, ApiWriter.staticSetClassApiOperationIdMethodResponse(siteRequest_, o)));
	}

	///////////////////////////////////////////
	// classSuperApiOperationIdMethodRequest //
	///////////////////////////////////////////

	/**	 The entity classSuperApiOperationIdMethodRequest
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classSuperApiOperationIdMethodRequest;

	/**	<br/> The entity classSuperApiOperationIdMethodRequest
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classSuperApiOperationIdMethodRequest">Find the entity classSuperApiOperationIdMethodRequest in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classSuperApiOperationIdMethodRequest(Wrap<String> c);

	public String getClassSuperApiOperationIdMethodRequest() {
		return classSuperApiOperationIdMethodRequest;
	}
	public void setClassSuperApiOperationIdMethodRequest(String o) {
		this.classSuperApiOperationIdMethodRequest = ApiWriter.staticSetClassSuperApiOperationIdMethodRequest(siteRequest_, o);
	}
	public static String staticSetClassSuperApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classSuperApiOperationIdMethodRequestInit() {
		Wrap<String> classSuperApiOperationIdMethodRequestWrap = new Wrap<String>().var("classSuperApiOperationIdMethodRequest");
		if(classSuperApiOperationIdMethodRequest == null) {
			_classSuperApiOperationIdMethodRequest(classSuperApiOperationIdMethodRequestWrap);
			setClassSuperApiOperationIdMethodRequest(classSuperApiOperationIdMethodRequestWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassSuperApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassSuperApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassSuperApiOperationIdMethodRequest(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassSuperApiOperationIdMethodRequest(siteRequest_, ApiWriter.staticSolrClassSuperApiOperationIdMethodRequest(siteRequest_, ApiWriter.staticSetClassSuperApiOperationIdMethodRequest(siteRequest_, o)));
	}

	////////////////////////////////////////////
	// classSuperApiOperationIdMethodResponse //
	////////////////////////////////////////////

	/**	 The entity classSuperApiOperationIdMethodResponse
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classSuperApiOperationIdMethodResponse;

	/**	<br/> The entity classSuperApiOperationIdMethodResponse
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classSuperApiOperationIdMethodResponse">Find the entity classSuperApiOperationIdMethodResponse in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classSuperApiOperationIdMethodResponse(Wrap<String> c);

	public String getClassSuperApiOperationIdMethodResponse() {
		return classSuperApiOperationIdMethodResponse;
	}
	public void setClassSuperApiOperationIdMethodResponse(String o) {
		this.classSuperApiOperationIdMethodResponse = ApiWriter.staticSetClassSuperApiOperationIdMethodResponse(siteRequest_, o);
	}
	public static String staticSetClassSuperApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classSuperApiOperationIdMethodResponseInit() {
		Wrap<String> classSuperApiOperationIdMethodResponseWrap = new Wrap<String>().var("classSuperApiOperationIdMethodResponse");
		if(classSuperApiOperationIdMethodResponse == null) {
			_classSuperApiOperationIdMethodResponse(classSuperApiOperationIdMethodResponseWrap);
			setClassSuperApiOperationIdMethodResponse(classSuperApiOperationIdMethodResponseWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassSuperApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassSuperApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassSuperApiOperationIdMethodResponse(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassSuperApiOperationIdMethodResponse(siteRequest_, ApiWriter.staticSolrClassSuperApiOperationIdMethodResponse(siteRequest_, ApiWriter.staticSetClassSuperApiOperationIdMethodResponse(siteRequest_, o)));
	}

	//////////////////////////////////
	// classPageCanonicalNameMethod //
	//////////////////////////////////

	/**	 The entity classPageCanonicalNameMethod
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String classPageCanonicalNameMethod;

	/**	<br/> The entity classPageCanonicalNameMethod
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classPageCanonicalNameMethod">Find the entity classPageCanonicalNameMethod in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classPageCanonicalNameMethod(Wrap<String> c);

	public String getClassPageCanonicalNameMethod() {
		return classPageCanonicalNameMethod;
	}
	public void setClassPageCanonicalNameMethod(String o) {
		this.classPageCanonicalNameMethod = ApiWriter.staticSetClassPageCanonicalNameMethod(siteRequest_, o);
	}
	public static String staticSetClassPageCanonicalNameMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter classPageCanonicalNameMethodInit() {
		Wrap<String> classPageCanonicalNameMethodWrap = new Wrap<String>().var("classPageCanonicalNameMethod");
		if(classPageCanonicalNameMethod == null) {
			_classPageCanonicalNameMethod(classPageCanonicalNameMethodWrap);
			setClassPageCanonicalNameMethod(classPageCanonicalNameMethodWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassPageCanonicalNameMethod(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassPageCanonicalNameMethod(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassPageCanonicalNameMethod(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassPageCanonicalNameMethod(siteRequest_, ApiWriter.staticSolrClassPageCanonicalNameMethod(siteRequest_, ApiWriter.staticSetClassPageCanonicalNameMethod(siteRequest_, o)));
	}

	////////////////////////
	// classKeywordsFound //
	////////////////////////

	/**	 The entity classKeywordsFound
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classKeywordsFound;

	/**	<br/> The entity classKeywordsFound
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classKeywordsFound">Find the entity classKeywordsFound in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classKeywordsFound(Wrap<Boolean> c);

	public Boolean getClassKeywordsFound() {
		return classKeywordsFound;
	}

	public void setClassKeywordsFound(Boolean classKeywordsFound) {
		this.classKeywordsFound = classKeywordsFound;
	}
	@JsonIgnore
	public void setClassKeywordsFound(String o) {
		this.classKeywordsFound = ApiWriter.staticSetClassKeywordsFound(siteRequest_, o);
	}
	public static Boolean staticSetClassKeywordsFound(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classKeywordsFoundInit() {
		Wrap<Boolean> classKeywordsFoundWrap = new Wrap<Boolean>().var("classKeywordsFound");
		if(classKeywordsFound == null) {
			_classKeywordsFound(classKeywordsFoundWrap);
			setClassKeywordsFound(classKeywordsFoundWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassKeywordsFound(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassKeywordsFound(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassKeywordsFound(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassKeywordsFound(siteRequest_, ApiWriter.staticSolrClassKeywordsFound(siteRequest_, ApiWriter.staticSetClassKeywordsFound(siteRequest_, o)));
	}

	///////////////////
	// classKeywords //
	///////////////////

	/**	 The entity classKeywords
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> classKeywords;

	/**	<br/> The entity classKeywords
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classKeywords">Find the entity classKeywords in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classKeywords(Wrap<List<String>> c);

	public List<String> getClassKeywords() {
		return classKeywords;
	}

	public void setClassKeywords(List<String> classKeywords) {
		this.classKeywords = classKeywords;
	}
	public static String staticSetClassKeywords(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public ApiWriter addClassKeywords(String...objets) {
		for(String o : objets) {
			addClassKeywords(o);
		}
		return (ApiWriter)this;
	}
	public ApiWriter addClassKeywords(String o) {
		if(o != null)
			this.classKeywords.add(o);
		return (ApiWriter)this;
	}
	@JsonIgnore
	public void setClassKeywords(JsonArray objets) {
		classKeywords.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addClassKeywords(o);
		}
	}
	protected ApiWriter classKeywordsInit() {
		Wrap<List<String>> classKeywordsWrap = new Wrap<List<String>>().var("classKeywords");
		if(classKeywords == null) {
			_classKeywords(classKeywordsWrap);
			setClassKeywords(classKeywordsWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassKeywords(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassKeywords(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassKeywords(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassKeywords(siteRequest_, ApiWriter.staticSolrClassKeywords(siteRequest_, ApiWriter.staticSetClassKeywords(siteRequest_, o)));
	}

	/////////////////////
	// classPublicRead //
	/////////////////////

	/**	 The entity classPublicRead
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classPublicRead;

	/**	<br/> The entity classPublicRead
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classPublicRead">Find the entity classPublicRead in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classPublicRead(Wrap<Boolean> c);

	public Boolean getClassPublicRead() {
		return classPublicRead;
	}

	public void setClassPublicRead(Boolean classPublicRead) {
		this.classPublicRead = classPublicRead;
	}
	@JsonIgnore
	public void setClassPublicRead(String o) {
		this.classPublicRead = ApiWriter.staticSetClassPublicRead(siteRequest_, o);
	}
	public static Boolean staticSetClassPublicRead(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classPublicReadInit() {
		Wrap<Boolean> classPublicReadWrap = new Wrap<Boolean>().var("classPublicRead");
		if(classPublicRead == null) {
			_classPublicRead(classPublicReadWrap);
			setClassPublicRead(classPublicReadWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassPublicRead(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassPublicRead(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassPublicRead(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassPublicRead(siteRequest_, ApiWriter.staticSolrClassPublicRead(siteRequest_, ApiWriter.staticSetClassPublicRead(siteRequest_, o)));
	}

	//////////////////////
	// classRoleSession //
	//////////////////////

	/**	 The entity classRoleSession
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classRoleSession;

	/**	<br/> The entity classRoleSession
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classRoleSession">Find the entity classRoleSession in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classRoleSession(Wrap<Boolean> c);

	public Boolean getClassRoleSession() {
		return classRoleSession;
	}

	public void setClassRoleSession(Boolean classRoleSession) {
		this.classRoleSession = classRoleSession;
	}
	@JsonIgnore
	public void setClassRoleSession(String o) {
		this.classRoleSession = ApiWriter.staticSetClassRoleSession(siteRequest_, o);
	}
	public static Boolean staticSetClassRoleSession(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classRoleSessionInit() {
		Wrap<Boolean> classRoleSessionWrap = new Wrap<Boolean>().var("classRoleSession");
		if(classRoleSession == null) {
			_classRoleSession(classRoleSessionWrap);
			setClassRoleSession(classRoleSessionWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassRoleSession(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassRoleSession(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassRoleSession(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassRoleSession(siteRequest_, ApiWriter.staticSolrClassRoleSession(siteRequest_, ApiWriter.staticSetClassRoleSession(siteRequest_, o)));
	}

	//////////////////////////
	// classRoleUtilisateur //
	//////////////////////////

	/**	 The entity classRoleUtilisateur
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classRoleUtilisateur;

	/**	<br/> The entity classRoleUtilisateur
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classRoleUtilisateur">Find the entity classRoleUtilisateur in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classRoleUtilisateur(Wrap<Boolean> c);

	public Boolean getClassRoleUtilisateur() {
		return classRoleUtilisateur;
	}

	public void setClassRoleUtilisateur(Boolean classRoleUtilisateur) {
		this.classRoleUtilisateur = classRoleUtilisateur;
	}
	@JsonIgnore
	public void setClassRoleUtilisateur(String o) {
		this.classRoleUtilisateur = ApiWriter.staticSetClassRoleUtilisateur(siteRequest_, o);
	}
	public static Boolean staticSetClassRoleUtilisateur(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classRoleUtilisateurInit() {
		Wrap<Boolean> classRoleUtilisateurWrap = new Wrap<Boolean>().var("classRoleUtilisateur");
		if(classRoleUtilisateur == null) {
			_classRoleUtilisateur(classRoleUtilisateurWrap);
			setClassRoleUtilisateur(classRoleUtilisateurWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassRoleUtilisateur(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassRoleUtilisateur(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassRoleUtilisateur(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassRoleUtilisateur(siteRequest_, ApiWriter.staticSolrClassRoleUtilisateur(siteRequest_, ApiWriter.staticSetClassRoleUtilisateur(siteRequest_, o)));
	}

	//////////////////
	// classRoleAll //
	//////////////////

	/**	 The entity classRoleAll
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classRoleAll;

	/**	<br/> The entity classRoleAll
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classRoleAll">Find the entity classRoleAll in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classRoleAll(Wrap<Boolean> c);

	public Boolean getClassRoleAll() {
		return classRoleAll;
	}

	public void setClassRoleAll(Boolean classRoleAll) {
		this.classRoleAll = classRoleAll;
	}
	@JsonIgnore
	public void setClassRoleAll(String o) {
		this.classRoleAll = ApiWriter.staticSetClassRoleAll(siteRequest_, o);
	}
	public static Boolean staticSetClassRoleAll(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classRoleAllInit() {
		Wrap<Boolean> classRoleAllWrap = new Wrap<Boolean>().var("classRoleAll");
		if(classRoleAll == null) {
			_classRoleAll(classRoleAllWrap);
			setClassRoleAll(classRoleAllWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassRoleAll(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassRoleAll(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassRoleAll(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassRoleAll(siteRequest_, ApiWriter.staticSolrClassRoleAll(siteRequest_, ApiWriter.staticSetClassRoleAll(siteRequest_, o)));
	}

	/////////////////////
	// classRolesFound //
	/////////////////////

	/**	 The entity classRolesFound
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected Boolean classRolesFound;

	/**	<br/> The entity classRolesFound
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classRolesFound">Find the entity classRolesFound in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classRolesFound(Wrap<Boolean> c);

	public Boolean getClassRolesFound() {
		return classRolesFound;
	}

	public void setClassRolesFound(Boolean classRolesFound) {
		this.classRolesFound = classRolesFound;
	}
	@JsonIgnore
	public void setClassRolesFound(String o) {
		this.classRolesFound = ApiWriter.staticSetClassRolesFound(siteRequest_, o);
	}
	public static Boolean staticSetClassRolesFound(SiteRequestEnUS siteRequest_, String o) {
		return Boolean.parseBoolean(o);
	}
	protected ApiWriter classRolesFoundInit() {
		Wrap<Boolean> classRolesFoundWrap = new Wrap<Boolean>().var("classRolesFound");
		if(classRolesFound == null) {
			_classRolesFound(classRolesFoundWrap);
			setClassRolesFound(classRolesFoundWrap.o);
		}
		return (ApiWriter)this;
	}

	public static Boolean staticSolrClassRolesFound(SiteRequestEnUS siteRequest_, Boolean o) {
		return o;
	}

	public static String staticSolrStrClassRolesFound(SiteRequestEnUS siteRequest_, Boolean o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassRolesFound(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassRolesFound(siteRequest_, ApiWriter.staticSolrClassRolesFound(siteRequest_, ApiWriter.staticSetClassRolesFound(siteRequest_, o)));
	}

	////////////////
	// classRoles //
	////////////////

	/**	 The entity classRoles
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> classRoles;

	/**	<br/> The entity classRoles
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classRoles">Find the entity classRoles in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classRoles(Wrap<List<String>> c);

	public List<String> getClassRoles() {
		return classRoles;
	}

	public void setClassRoles(List<String> classRoles) {
		this.classRoles = classRoles;
	}
	public static String staticSetClassRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public ApiWriter addClassRoles(String...objets) {
		for(String o : objets) {
			addClassRoles(o);
		}
		return (ApiWriter)this;
	}
	public ApiWriter addClassRoles(String o) {
		if(o != null)
			this.classRoles.add(o);
		return (ApiWriter)this;
	}
	@JsonIgnore
	public void setClassRoles(JsonArray objets) {
		classRoles.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addClassRoles(o);
		}
	}
	protected ApiWriter classRolesInit() {
		Wrap<List<String>> classRolesWrap = new Wrap<List<String>>().var("classRoles");
		if(classRoles == null) {
			_classRoles(classRolesWrap);
			setClassRoles(classRolesWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassRoles(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassRoles(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassRoles(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassRoles(siteRequest_, ApiWriter.staticSolrClassRoles(siteRequest_, ApiWriter.staticSetClassRoles(siteRequest_, o)));
	}

	////////////////////////
	// classRolesLanguage //
	////////////////////////

	/**	 The entity classRolesLanguage
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonFormat(shape = JsonFormat.Shape.ARRAY)
	@JsonInclude(Include.NON_NULL)
	protected List<String> classRolesLanguage;

	/**	<br/> The entity classRolesLanguage
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:classRolesLanguage">Find the entity classRolesLanguage in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _classRolesLanguage(Wrap<List<String>> c);

	public List<String> getClassRolesLanguage() {
		return classRolesLanguage;
	}

	public void setClassRolesLanguage(List<String> classRolesLanguage) {
		this.classRolesLanguage = classRolesLanguage;
	}
	public static String staticSetClassRolesLanguage(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	public ApiWriter addClassRolesLanguage(String...objets) {
		for(String o : objets) {
			addClassRolesLanguage(o);
		}
		return (ApiWriter)this;
	}
	public ApiWriter addClassRolesLanguage(String o) {
		if(o != null)
			this.classRolesLanguage.add(o);
		return (ApiWriter)this;
	}
	@JsonIgnore
	public void setClassRolesLanguage(JsonArray objets) {
		classRolesLanguage.clear();
		for(int i = 0; i < objets.size(); i++) {
			String o = objets.getString(i);
			addClassRolesLanguage(o);
		}
	}
	protected ApiWriter classRolesLanguageInit() {
		Wrap<List<String>> classRolesLanguageWrap = new Wrap<List<String>>().var("classRolesLanguage");
		if(classRolesLanguage == null) {
			_classRolesLanguage(classRolesLanguageWrap);
			setClassRolesLanguage(classRolesLanguageWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrClassRolesLanguage(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrClassRolesLanguage(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqClassRolesLanguage(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrClassRolesLanguage(siteRequest_, ApiWriter.staticSolrClassRolesLanguage(siteRequest_, ApiWriter.staticSetClassRolesLanguage(siteRequest_, o)));
	}

	//////////////////
	// languageName //
	//////////////////

	/**	 The entity languageName
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String languageName;

	/**	<br/> The entity languageName
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:languageName">Find the entity languageName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _languageName(Wrap<String> c);

	public String getLanguageName() {
		return languageName;
	}
	public void setLanguageName(String o) {
		this.languageName = ApiWriter.staticSetLanguageName(siteRequest_, o);
	}
	public static String staticSetLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected ApiWriter languageNameInit() {
		Wrap<String> languageNameWrap = new Wrap<String>().var("languageName");
		if(languageName == null) {
			_languageName(languageNameWrap);
			setLanguageName(languageNameWrap.o);
		}
		return (ApiWriter)this;
	}

	public static String staticSolrLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return ApiWriter.staticSolrStrLanguageName(siteRequest_, ApiWriter.staticSolrLanguageName(siteRequest_, ApiWriter.staticSetLanguageName(siteRequest_, o)));
	}

	////////////////////////
	// entitySolrDocument //
	////////////////////////

	/**	 The entity entitySolrDocument
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected SolrDocument entitySolrDocument;

	/**	<br/> The entity entitySolrDocument
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.writer.ApiWriter&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:entitySolrDocument">Find the entity entitySolrDocument in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _entitySolrDocument(Wrap<SolrDocument> c);

	public SolrDocument getEntitySolrDocument() {
		return entitySolrDocument;
	}

	public void setEntitySolrDocument(SolrDocument entitySolrDocument) {
		this.entitySolrDocument = entitySolrDocument;
	}
	public static SolrDocument staticSetEntitySolrDocument(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected ApiWriter entitySolrDocumentInit() {
		Wrap<SolrDocument> entitySolrDocumentWrap = new Wrap<SolrDocument>().var("entitySolrDocument");
		if(entitySolrDocument == null) {
			_entitySolrDocument(entitySolrDocumentWrap);
			setEntitySolrDocument(entitySolrDocumentWrap.o);
		}
		return (ApiWriter)this;
	}

	//////////////
	// initDeep //
	//////////////

	public ApiWriter initDeepApiWriter(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		initDeepApiWriter();
		return (ApiWriter)this;
	}

	public void initDeepApiWriter() {
		initApiWriter();
	}

	public void initApiWriter() {
				siteRequest_Init();
				classSolrDocumentInit();
				contextRowsInit();
				classApiMethodInit();
				openApiVersionInit();
				appSwagger2Init();
				classUrisInit();
				openApiVersionNumberInit();
				tabsSchemaInit();
				tabsResponsesInit();
				wPathsInit();
				wRequestBodiesInit();
				wSchemasInit();
				configInit();
				solrClientComputateInit();
				wRequestHeadersInit();
				wRequestDescriptionInit();
				wResponseDescriptionInit();
				wRequestBodyInit();
				wResponseBodyInit();
				wRequestSchemaInit();
				wResponseSchemaInit();
				writersInit();
				classApiTagInit();
				classExtendsBaseInit();
				classIsBaseInit();
				classSimpleNameInit();
				appNameInit();
				classAbsolutePathInit();
				classApiUriMethodInit();
				classRoleUserMethodInit();
				classApiMethodMethodInit();
				classApiMediaType200MethodInit();
				classApiOperationIdMethodInit();
				classApiOperationIdMethodRequestInit();
				classApiOperationIdMethodResponseInit();
				classSuperApiOperationIdMethodRequestInit();
				classSuperApiOperationIdMethodResponseInit();
				classPageCanonicalNameMethodInit();
				classKeywordsFoundInit();
				classKeywordsInit();
				classPublicReadInit();
				classRoleSessionInit();
				classRoleUtilisateurInit();
				classRoleAllInit();
				classRolesFoundInit();
				classRolesInit();
				classRolesLanguageInit();
				languageNameInit();
				entitySolrDocumentInit();
	}

	public void initDeepForClass(SiteRequestEnUS siteRequest_) {
		initDeepApiWriter(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestApiWriter(SiteRequestEnUS siteRequest_) {
		if(appSwagger2 != null)
			appSwagger2.setSiteRequest_(siteRequest_);
		if(wPaths != null)
			wPaths.setSiteRequest_(siteRequest_);
		if(wRequestBodies != null)
			wRequestBodies.setSiteRequest_(siteRequest_);
		if(wSchemas != null)
			wSchemas.setSiteRequest_(siteRequest_);
		if(wRequestHeaders != null)
			wRequestHeaders.setSiteRequest_(siteRequest_);
		if(wRequestDescription != null)
			wRequestDescription.setSiteRequest_(siteRequest_);
		if(wResponseDescription != null)
			wResponseDescription.setSiteRequest_(siteRequest_);
		if(wRequestBody != null)
			wRequestBody.setSiteRequest_(siteRequest_);
		if(wResponseBody != null)
			wResponseBody.setSiteRequest_(siteRequest_);
		if(wRequestSchema != null)
			wRequestSchema.setSiteRequest_(siteRequest_);
		if(wResponseSchema != null)
			wResponseSchema.setSiteRequest_(siteRequest_);
		if(writers != null)
			writers.setSiteRequest_(siteRequest_);
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestApiWriter(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainApiWriter(v);
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
	public Object obtainApiWriter(String var) {
		ApiWriter oApiWriter = (ApiWriter)this;
		switch(var) {
			case "siteRequest_":
				return oApiWriter.siteRequest_;
			case "classSolrDocument":
				return oApiWriter.classSolrDocument;
			case "contextRows":
				return oApiWriter.contextRows;
			case "classApiMethod":
				return oApiWriter.classApiMethod;
			case "openApiVersion":
				return oApiWriter.openApiVersion;
			case "appSwagger2":
				return oApiWriter.appSwagger2;
			case "classUris":
				return oApiWriter.classUris;
			case "openApiVersionNumber":
				return oApiWriter.openApiVersionNumber;
			case "tabsSchema":
				return oApiWriter.tabsSchema;
			case "tabsResponses":
				return oApiWriter.tabsResponses;
			case "wPaths":
				return oApiWriter.wPaths;
			case "wRequestBodies":
				return oApiWriter.wRequestBodies;
			case "wSchemas":
				return oApiWriter.wSchemas;
			case "config":
				return oApiWriter.config;
			case "solrClientComputate":
				return oApiWriter.solrClientComputate;
			case "wRequestHeaders":
				return oApiWriter.wRequestHeaders;
			case "wRequestDescription":
				return oApiWriter.wRequestDescription;
			case "wResponseDescription":
				return oApiWriter.wResponseDescription;
			case "wRequestBody":
				return oApiWriter.wRequestBody;
			case "wResponseBody":
				return oApiWriter.wResponseBody;
			case "wRequestSchema":
				return oApiWriter.wRequestSchema;
			case "wResponseSchema":
				return oApiWriter.wResponseSchema;
			case "writers":
				return oApiWriter.writers;
			case "classApiTag":
				return oApiWriter.classApiTag;
			case "classExtendsBase":
				return oApiWriter.classExtendsBase;
			case "classIsBase":
				return oApiWriter.classIsBase;
			case "classSimpleName":
				return oApiWriter.classSimpleName;
			case "appName":
				return oApiWriter.appName;
			case "classAbsolutePath":
				return oApiWriter.classAbsolutePath;
			case "classApiUriMethod":
				return oApiWriter.classApiUriMethod;
			case "classRoleUserMethod":
				return oApiWriter.classRoleUserMethod;
			case "classApiMethodMethod":
				return oApiWriter.classApiMethodMethod;
			case "classApiMediaType200Method":
				return oApiWriter.classApiMediaType200Method;
			case "classApiOperationIdMethod":
				return oApiWriter.classApiOperationIdMethod;
			case "classApiOperationIdMethodRequest":
				return oApiWriter.classApiOperationIdMethodRequest;
			case "classApiOperationIdMethodResponse":
				return oApiWriter.classApiOperationIdMethodResponse;
			case "classSuperApiOperationIdMethodRequest":
				return oApiWriter.classSuperApiOperationIdMethodRequest;
			case "classSuperApiOperationIdMethodResponse":
				return oApiWriter.classSuperApiOperationIdMethodResponse;
			case "classPageCanonicalNameMethod":
				return oApiWriter.classPageCanonicalNameMethod;
			case "classKeywordsFound":
				return oApiWriter.classKeywordsFound;
			case "classKeywords":
				return oApiWriter.classKeywords;
			case "classPublicRead":
				return oApiWriter.classPublicRead;
			case "classRoleSession":
				return oApiWriter.classRoleSession;
			case "classRoleUtilisateur":
				return oApiWriter.classRoleUtilisateur;
			case "classRoleAll":
				return oApiWriter.classRoleAll;
			case "classRolesFound":
				return oApiWriter.classRolesFound;
			case "classRoles":
				return oApiWriter.classRoles;
			case "classRolesLanguage":
				return oApiWriter.classRolesLanguage;
			case "languageName":
				return oApiWriter.languageName;
			case "entitySolrDocument":
				return oApiWriter.entitySolrDocument;
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
				o = relateApiWriter(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relateApiWriter(String var, Object val) {
		ApiWriter oApiWriter = (ApiWriter)this;
		switch(var) {
			default:
				return null;
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetApiWriter(entityVar,  siteRequest_, o);
	}
	public static Object staticSetApiWriter(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "contextRows":
			return ApiWriter.staticSetContextRows(siteRequest_, o);
		case "classApiMethod":
			return ApiWriter.staticSetClassApiMethod(siteRequest_, o);
		case "openApiVersion":
			return ApiWriter.staticSetOpenApiVersion(siteRequest_, o);
		case "classUris":
			return ApiWriter.staticSetClassUris(siteRequest_, o);
		case "openApiVersionNumber":
			return ApiWriter.staticSetOpenApiVersionNumber(siteRequest_, o);
		case "tabsSchema":
			return ApiWriter.staticSetTabsSchema(siteRequest_, o);
		case "tabsResponses":
			return ApiWriter.staticSetTabsResponses(siteRequest_, o);
		case "classApiTag":
			return ApiWriter.staticSetClassApiTag(siteRequest_, o);
		case "classExtendsBase":
			return ApiWriter.staticSetClassExtendsBase(siteRequest_, o);
		case "classIsBase":
			return ApiWriter.staticSetClassIsBase(siteRequest_, o);
		case "classSimpleName":
			return ApiWriter.staticSetClassSimpleName(siteRequest_, o);
		case "appName":
			return ApiWriter.staticSetAppName(siteRequest_, o);
		case "classAbsolutePath":
			return ApiWriter.staticSetClassAbsolutePath(siteRequest_, o);
		case "classApiUriMethod":
			return ApiWriter.staticSetClassApiUriMethod(siteRequest_, o);
		case "classRoleUserMethod":
			return ApiWriter.staticSetClassRoleUserMethod(siteRequest_, o);
		case "classApiMethodMethod":
			return ApiWriter.staticSetClassApiMethodMethod(siteRequest_, o);
		case "classApiMediaType200Method":
			return ApiWriter.staticSetClassApiMediaType200Method(siteRequest_, o);
		case "classApiOperationIdMethod":
			return ApiWriter.staticSetClassApiOperationIdMethod(siteRequest_, o);
		case "classApiOperationIdMethodRequest":
			return ApiWriter.staticSetClassApiOperationIdMethodRequest(siteRequest_, o);
		case "classApiOperationIdMethodResponse":
			return ApiWriter.staticSetClassApiOperationIdMethodResponse(siteRequest_, o);
		case "classSuperApiOperationIdMethodRequest":
			return ApiWriter.staticSetClassSuperApiOperationIdMethodRequest(siteRequest_, o);
		case "classSuperApiOperationIdMethodResponse":
			return ApiWriter.staticSetClassSuperApiOperationIdMethodResponse(siteRequest_, o);
		case "classPageCanonicalNameMethod":
			return ApiWriter.staticSetClassPageCanonicalNameMethod(siteRequest_, o);
		case "classKeywordsFound":
			return ApiWriter.staticSetClassKeywordsFound(siteRequest_, o);
		case "classKeywords":
			return ApiWriter.staticSetClassKeywords(siteRequest_, o);
		case "classPublicRead":
			return ApiWriter.staticSetClassPublicRead(siteRequest_, o);
		case "classRoleSession":
			return ApiWriter.staticSetClassRoleSession(siteRequest_, o);
		case "classRoleUtilisateur":
			return ApiWriter.staticSetClassRoleUtilisateur(siteRequest_, o);
		case "classRoleAll":
			return ApiWriter.staticSetClassRoleAll(siteRequest_, o);
		case "classRolesFound":
			return ApiWriter.staticSetClassRolesFound(siteRequest_, o);
		case "classRoles":
			return ApiWriter.staticSetClassRoles(siteRequest_, o);
		case "classRolesLanguage":
			return ApiWriter.staticSetClassRolesLanguage(siteRequest_, o);
		case "languageName":
			return ApiWriter.staticSetLanguageName(siteRequest_, o);
			default:
				return null;
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrApiWriter(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrApiWriter(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "contextRows":
			return ApiWriter.staticSolrContextRows(siteRequest_, (Integer)o);
		case "classApiMethod":
			return ApiWriter.staticSolrClassApiMethod(siteRequest_, (String)o);
		case "openApiVersion":
			return ApiWriter.staticSolrOpenApiVersion(siteRequest_, (String)o);
		case "classUris":
			return ApiWriter.staticSolrClassUris(siteRequest_, (String)o);
		case "openApiVersionNumber":
			return ApiWriter.staticSolrOpenApiVersionNumber(siteRequest_, (Integer)o);
		case "tabsSchema":
			return ApiWriter.staticSolrTabsSchema(siteRequest_, (Integer)o);
		case "tabsResponses":
			return ApiWriter.staticSolrTabsResponses(siteRequest_, (Integer)o);
		case "classApiTag":
			return ApiWriter.staticSolrClassApiTag(siteRequest_, (String)o);
		case "classExtendsBase":
			return ApiWriter.staticSolrClassExtendsBase(siteRequest_, (Boolean)o);
		case "classIsBase":
			return ApiWriter.staticSolrClassIsBase(siteRequest_, (Boolean)o);
		case "classSimpleName":
			return ApiWriter.staticSolrClassSimpleName(siteRequest_, (String)o);
		case "appName":
			return ApiWriter.staticSolrAppName(siteRequest_, (String)o);
		case "classAbsolutePath":
			return ApiWriter.staticSolrClassAbsolutePath(siteRequest_, (String)o);
		case "classApiUriMethod":
			return ApiWriter.staticSolrClassApiUriMethod(siteRequest_, (String)o);
		case "classRoleUserMethod":
			return ApiWriter.staticSolrClassRoleUserMethod(siteRequest_, (Boolean)o);
		case "classApiMethodMethod":
			return ApiWriter.staticSolrClassApiMethodMethod(siteRequest_, (String)o);
		case "classApiMediaType200Method":
			return ApiWriter.staticSolrClassApiMediaType200Method(siteRequest_, (String)o);
		case "classApiOperationIdMethod":
			return ApiWriter.staticSolrClassApiOperationIdMethod(siteRequest_, (String)o);
		case "classApiOperationIdMethodRequest":
			return ApiWriter.staticSolrClassApiOperationIdMethodRequest(siteRequest_, (String)o);
		case "classApiOperationIdMethodResponse":
			return ApiWriter.staticSolrClassApiOperationIdMethodResponse(siteRequest_, (String)o);
		case "classSuperApiOperationIdMethodRequest":
			return ApiWriter.staticSolrClassSuperApiOperationIdMethodRequest(siteRequest_, (String)o);
		case "classSuperApiOperationIdMethodResponse":
			return ApiWriter.staticSolrClassSuperApiOperationIdMethodResponse(siteRequest_, (String)o);
		case "classPageCanonicalNameMethod":
			return ApiWriter.staticSolrClassPageCanonicalNameMethod(siteRequest_, (String)o);
		case "classKeywordsFound":
			return ApiWriter.staticSolrClassKeywordsFound(siteRequest_, (Boolean)o);
		case "classKeywords":
			return ApiWriter.staticSolrClassKeywords(siteRequest_, (String)o);
		case "classPublicRead":
			return ApiWriter.staticSolrClassPublicRead(siteRequest_, (Boolean)o);
		case "classRoleSession":
			return ApiWriter.staticSolrClassRoleSession(siteRequest_, (Boolean)o);
		case "classRoleUtilisateur":
			return ApiWriter.staticSolrClassRoleUtilisateur(siteRequest_, (Boolean)o);
		case "classRoleAll":
			return ApiWriter.staticSolrClassRoleAll(siteRequest_, (Boolean)o);
		case "classRolesFound":
			return ApiWriter.staticSolrClassRolesFound(siteRequest_, (Boolean)o);
		case "classRoles":
			return ApiWriter.staticSolrClassRoles(siteRequest_, (String)o);
		case "classRolesLanguage":
			return ApiWriter.staticSolrClassRolesLanguage(siteRequest_, (String)o);
		case "languageName":
			return ApiWriter.staticSolrLanguageName(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrApiWriter(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrApiWriter(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "contextRows":
			return ApiWriter.staticSolrStrContextRows(siteRequest_, (Integer)o);
		case "classApiMethod":
			return ApiWriter.staticSolrStrClassApiMethod(siteRequest_, (String)o);
		case "openApiVersion":
			return ApiWriter.staticSolrStrOpenApiVersion(siteRequest_, (String)o);
		case "classUris":
			return ApiWriter.staticSolrStrClassUris(siteRequest_, (String)o);
		case "openApiVersionNumber":
			return ApiWriter.staticSolrStrOpenApiVersionNumber(siteRequest_, (Integer)o);
		case "tabsSchema":
			return ApiWriter.staticSolrStrTabsSchema(siteRequest_, (Integer)o);
		case "tabsResponses":
			return ApiWriter.staticSolrStrTabsResponses(siteRequest_, (Integer)o);
		case "classApiTag":
			return ApiWriter.staticSolrStrClassApiTag(siteRequest_, (String)o);
		case "classExtendsBase":
			return ApiWriter.staticSolrStrClassExtendsBase(siteRequest_, (Boolean)o);
		case "classIsBase":
			return ApiWriter.staticSolrStrClassIsBase(siteRequest_, (Boolean)o);
		case "classSimpleName":
			return ApiWriter.staticSolrStrClassSimpleName(siteRequest_, (String)o);
		case "appName":
			return ApiWriter.staticSolrStrAppName(siteRequest_, (String)o);
		case "classAbsolutePath":
			return ApiWriter.staticSolrStrClassAbsolutePath(siteRequest_, (String)o);
		case "classApiUriMethod":
			return ApiWriter.staticSolrStrClassApiUriMethod(siteRequest_, (String)o);
		case "classRoleUserMethod":
			return ApiWriter.staticSolrStrClassRoleUserMethod(siteRequest_, (Boolean)o);
		case "classApiMethodMethod":
			return ApiWriter.staticSolrStrClassApiMethodMethod(siteRequest_, (String)o);
		case "classApiMediaType200Method":
			return ApiWriter.staticSolrStrClassApiMediaType200Method(siteRequest_, (String)o);
		case "classApiOperationIdMethod":
			return ApiWriter.staticSolrStrClassApiOperationIdMethod(siteRequest_, (String)o);
		case "classApiOperationIdMethodRequest":
			return ApiWriter.staticSolrStrClassApiOperationIdMethodRequest(siteRequest_, (String)o);
		case "classApiOperationIdMethodResponse":
			return ApiWriter.staticSolrStrClassApiOperationIdMethodResponse(siteRequest_, (String)o);
		case "classSuperApiOperationIdMethodRequest":
			return ApiWriter.staticSolrStrClassSuperApiOperationIdMethodRequest(siteRequest_, (String)o);
		case "classSuperApiOperationIdMethodResponse":
			return ApiWriter.staticSolrStrClassSuperApiOperationIdMethodResponse(siteRequest_, (String)o);
		case "classPageCanonicalNameMethod":
			return ApiWriter.staticSolrStrClassPageCanonicalNameMethod(siteRequest_, (String)o);
		case "classKeywordsFound":
			return ApiWriter.staticSolrStrClassKeywordsFound(siteRequest_, (Boolean)o);
		case "classKeywords":
			return ApiWriter.staticSolrStrClassKeywords(siteRequest_, (String)o);
		case "classPublicRead":
			return ApiWriter.staticSolrStrClassPublicRead(siteRequest_, (Boolean)o);
		case "classRoleSession":
			return ApiWriter.staticSolrStrClassRoleSession(siteRequest_, (Boolean)o);
		case "classRoleUtilisateur":
			return ApiWriter.staticSolrStrClassRoleUtilisateur(siteRequest_, (Boolean)o);
		case "classRoleAll":
			return ApiWriter.staticSolrStrClassRoleAll(siteRequest_, (Boolean)o);
		case "classRolesFound":
			return ApiWriter.staticSolrStrClassRolesFound(siteRequest_, (Boolean)o);
		case "classRoles":
			return ApiWriter.staticSolrStrClassRoles(siteRequest_, (String)o);
		case "classRolesLanguage":
			return ApiWriter.staticSolrStrClassRolesLanguage(siteRequest_, (String)o);
		case "languageName":
			return ApiWriter.staticSolrStrLanguageName(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqApiWriter(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqApiWriter(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "contextRows":
			return ApiWriter.staticSolrFqContextRows(siteRequest_, o);
		case "classApiMethod":
			return ApiWriter.staticSolrFqClassApiMethod(siteRequest_, o);
		case "openApiVersion":
			return ApiWriter.staticSolrFqOpenApiVersion(siteRequest_, o);
		case "classUris":
			return ApiWriter.staticSolrFqClassUris(siteRequest_, o);
		case "openApiVersionNumber":
			return ApiWriter.staticSolrFqOpenApiVersionNumber(siteRequest_, o);
		case "tabsSchema":
			return ApiWriter.staticSolrFqTabsSchema(siteRequest_, o);
		case "tabsResponses":
			return ApiWriter.staticSolrFqTabsResponses(siteRequest_, o);
		case "classApiTag":
			return ApiWriter.staticSolrFqClassApiTag(siteRequest_, o);
		case "classExtendsBase":
			return ApiWriter.staticSolrFqClassExtendsBase(siteRequest_, o);
		case "classIsBase":
			return ApiWriter.staticSolrFqClassIsBase(siteRequest_, o);
		case "classSimpleName":
			return ApiWriter.staticSolrFqClassSimpleName(siteRequest_, o);
		case "appName":
			return ApiWriter.staticSolrFqAppName(siteRequest_, o);
		case "classAbsolutePath":
			return ApiWriter.staticSolrFqClassAbsolutePath(siteRequest_, o);
		case "classApiUriMethod":
			return ApiWriter.staticSolrFqClassApiUriMethod(siteRequest_, o);
		case "classRoleUserMethod":
			return ApiWriter.staticSolrFqClassRoleUserMethod(siteRequest_, o);
		case "classApiMethodMethod":
			return ApiWriter.staticSolrFqClassApiMethodMethod(siteRequest_, o);
		case "classApiMediaType200Method":
			return ApiWriter.staticSolrFqClassApiMediaType200Method(siteRequest_, o);
		case "classApiOperationIdMethod":
			return ApiWriter.staticSolrFqClassApiOperationIdMethod(siteRequest_, o);
		case "classApiOperationIdMethodRequest":
			return ApiWriter.staticSolrFqClassApiOperationIdMethodRequest(siteRequest_, o);
		case "classApiOperationIdMethodResponse":
			return ApiWriter.staticSolrFqClassApiOperationIdMethodResponse(siteRequest_, o);
		case "classSuperApiOperationIdMethodRequest":
			return ApiWriter.staticSolrFqClassSuperApiOperationIdMethodRequest(siteRequest_, o);
		case "classSuperApiOperationIdMethodResponse":
			return ApiWriter.staticSolrFqClassSuperApiOperationIdMethodResponse(siteRequest_, o);
		case "classPageCanonicalNameMethod":
			return ApiWriter.staticSolrFqClassPageCanonicalNameMethod(siteRequest_, o);
		case "classKeywordsFound":
			return ApiWriter.staticSolrFqClassKeywordsFound(siteRequest_, o);
		case "classKeywords":
			return ApiWriter.staticSolrFqClassKeywords(siteRequest_, o);
		case "classPublicRead":
			return ApiWriter.staticSolrFqClassPublicRead(siteRequest_, o);
		case "classRoleSession":
			return ApiWriter.staticSolrFqClassRoleSession(siteRequest_, o);
		case "classRoleUtilisateur":
			return ApiWriter.staticSolrFqClassRoleUtilisateur(siteRequest_, o);
		case "classRoleAll":
			return ApiWriter.staticSolrFqClassRoleAll(siteRequest_, o);
		case "classRolesFound":
			return ApiWriter.staticSolrFqClassRolesFound(siteRequest_, o);
		case "classRoles":
			return ApiWriter.staticSolrFqClassRoles(siteRequest_, o);
		case "classRolesLanguage":
			return ApiWriter.staticSolrFqClassRolesLanguage(siteRequest_, o);
		case "languageName":
			return ApiWriter.staticSolrFqLanguageName(siteRequest_, o);
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
					o = defineApiWriter(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineApiWriter(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestApiWriter() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof ApiWriter) {
			ApiWriter original = (ApiWriter)o;
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
	public static final String VAR_classSolrDocument = "classSolrDocument";
	public static final String VAR_contextRows = "contextRows";
	public static final String VAR_classApiMethod = "classApiMethod";
	public static final String VAR_openApiVersion = "openApiVersion";
	public static final String VAR_appSwagger2 = "appSwagger2";
	public static final String VAR_classUris = "classUris";
	public static final String VAR_openApiVersionNumber = "openApiVersionNumber";
	public static final String VAR_tabsSchema = "tabsSchema";
	public static final String VAR_tabsResponses = "tabsResponses";
	public static final String VAR_wPaths = "wPaths";
	public static final String VAR_wRequestBodies = "wRequestBodies";
	public static final String VAR_wSchemas = "wSchemas";
	public static final String VAR_config = "config";
	public static final String VAR_solrClientComputate = "solrClientComputate";
	public static final String VAR_wRequestHeaders = "wRequestHeaders";
	public static final String VAR_wRequestDescription = "wRequestDescription";
	public static final String VAR_wResponseDescription = "wResponseDescription";
	public static final String VAR_wRequestBody = "wRequestBody";
	public static final String VAR_wResponseBody = "wResponseBody";
	public static final String VAR_wRequestSchema = "wRequestSchema";
	public static final String VAR_wResponseSchema = "wResponseSchema";
	public static final String VAR_writers = "writers";
	public static final String VAR_classApiTag = "classApiTag";
	public static final String VAR_classExtendsBase = "classExtendsBase";
	public static final String VAR_classIsBase = "classIsBase";
	public static final String VAR_classSimpleName = "classSimpleName";
	public static final String VAR_appName = "appName";
	public static final String VAR_classAbsolutePath = "classAbsolutePath";
	public static final String VAR_classApiUriMethod = "classApiUriMethod";
	public static final String VAR_classRoleUserMethod = "classRoleUserMethod";
	public static final String VAR_classApiMethodMethod = "classApiMethodMethod";
	public static final String VAR_classApiMediaType200Method = "classApiMediaType200Method";
	public static final String VAR_classApiOperationIdMethod = "classApiOperationIdMethod";
	public static final String VAR_classApiOperationIdMethodRequest = "classApiOperationIdMethodRequest";
	public static final String VAR_classApiOperationIdMethodResponse = "classApiOperationIdMethodResponse";
	public static final String VAR_classSuperApiOperationIdMethodRequest = "classSuperApiOperationIdMethodRequest";
	public static final String VAR_classSuperApiOperationIdMethodResponse = "classSuperApiOperationIdMethodResponse";
	public static final String VAR_classPageCanonicalNameMethod = "classPageCanonicalNameMethod";
	public static final String VAR_classKeywordsFound = "classKeywordsFound";
	public static final String VAR_classKeywords = "classKeywords";
	public static final String VAR_classPublicRead = "classPublicRead";
	public static final String VAR_classRoleSession = "classRoleSession";
	public static final String VAR_classRoleUtilisateur = "classRoleUtilisateur";
	public static final String VAR_classRoleAll = "classRoleAll";
	public static final String VAR_classRolesFound = "classRolesFound";
	public static final String VAR_classRoles = "classRoles";
	public static final String VAR_classRolesLanguage = "classRolesLanguage";
	public static final String VAR_languageName = "languageName";
	public static final String VAR_entitySolrDocument = "entitySolrDocument";
}
