package org.curriki.api.enus.vertx;

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
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import io.vertx.core.Future;
import java.io.File;
import org.apache.solr.client.solrj.SolrClient;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import java.lang.Object;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class AppSwagger2Gen<DEV> extends Object {
	protected static final Logger LOG = LoggerFactory.getLogger(AppSwagger2.class);

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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:solrClientComputate">Find the entity solrClientComputate in Solr</a>
	 * <br/>
	 * @param w is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _solrClientComputate(Wrap<SolrClient> w);

	public SolrClient getSolrClientComputate() {
		return solrClientComputate;
	}

	public void setSolrClientComputate(SolrClient solrClientComputate) {
		this.solrClientComputate = solrClientComputate;
	}
	public static SolrClient staticSetSolrClientComputate(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AppSwagger2 solrClientComputateInit() {
		Wrap<SolrClient> solrClientComputateWrap = new Wrap<SolrClient>().var("solrClientComputate");
		if(solrClientComputate == null) {
			_solrClientComputate(solrClientComputateWrap);
			setSolrClientComputate(solrClientComputateWrap.o);
		}
		return (AppSwagger2)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:siteRequest_">Find the entity siteRequest_ in Solr</a>
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
	protected AppSwagger2 siteRequest_Init() {
		Wrap<SiteRequestEnUS> siteRequest_Wrap = new Wrap<SiteRequestEnUS>().var("siteRequest_");
		if(siteRequest_ == null) {
			_siteRequest_(siteRequest_Wrap);
			setSiteRequest_(siteRequest_Wrap.o);
		}
		return (AppSwagger2)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:config">Find the entity config in Solr</a>
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
	protected AppSwagger2 configInit() {
		Wrap<JsonObject> configWrap = new Wrap<JsonObject>().var("config");
		if(config == null) {
			_config(configWrap);
			setConfig(configWrap.o);
		}
		return (AppSwagger2)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:appName">Find the entity appName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _appName(Wrap<String> c);

	public String getAppName() {
		return appName;
	}
	public void setAppName(String o) {
		this.appName = AppSwagger2.staticSetAppName(siteRequest_, o);
	}
	public static String staticSetAppName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected AppSwagger2 appNameInit() {
		Wrap<String> appNameWrap = new Wrap<String>().var("appName");
		if(appName == null) {
			_appName(appNameWrap);
			setAppName(appNameWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static String staticSolrAppName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrAppName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqAppName(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrAppName(siteRequest_, AppSwagger2.staticSolrAppName(siteRequest_, AppSwagger2.staticSetAppName(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:languageName">Find the entity languageName in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _languageName(Wrap<String> c);

	public String getLanguageName() {
		return languageName;
	}
	public void setLanguageName(String o) {
		this.languageName = AppSwagger2.staticSetLanguageName(siteRequest_, o);
	}
	public static String staticSetLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected AppSwagger2 languageNameInit() {
		Wrap<String> languageNameWrap = new Wrap<String>().var("languageName");
		if(languageName == null) {
			_languageName(languageNameWrap);
			setLanguageName(languageNameWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static String staticSolrLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqLanguageName(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrLanguageName(siteRequest_, AppSwagger2.staticSolrLanguageName(siteRequest_, AppSwagger2.staticSetLanguageName(siteRequest_, o)));
	}

	/////////////
	// appPath //
	/////////////

	/**	 The entity appPath
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String appPath;

	/**	<br/> The entity appPath
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:appPath">Find the entity appPath in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _appPath(Wrap<String> c);

	public String getAppPath() {
		return appPath;
	}
	public void setAppPath(String o) {
		this.appPath = AppSwagger2.staticSetAppPath(siteRequest_, o);
	}
	public static String staticSetAppPath(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected AppSwagger2 appPathInit() {
		Wrap<String> appPathWrap = new Wrap<String>().var("appPath");
		if(appPath == null) {
			_appPath(appPathWrap);
			setAppPath(appPathWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static String staticSolrAppPath(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrAppPath(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqAppPath(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrAppPath(siteRequest_, AppSwagger2.staticSolrAppPath(siteRequest_, AppSwagger2.staticSetAppPath(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:openApiVersion">Find the entity openApiVersion in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _openApiVersion(Wrap<String> c);

	public String getOpenApiVersion() {
		return openApiVersion;
	}
	public void setOpenApiVersion(String o) {
		this.openApiVersion = AppSwagger2.staticSetOpenApiVersion(siteRequest_, o);
	}
	public static String staticSetOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected AppSwagger2 openApiVersionInit() {
		Wrap<String> openApiVersionWrap = new Wrap<String>().var("openApiVersion");
		if(openApiVersion == null) {
			_openApiVersion(openApiVersionWrap);
			setOpenApiVersion(openApiVersionWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static String staticSolrOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqOpenApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrOpenApiVersion(siteRequest_, AppSwagger2.staticSolrOpenApiVersion(siteRequest_, AppSwagger2.staticSetOpenApiVersion(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:openApiVersionNumber">Find the entity openApiVersionNumber in Solr</a>
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
		this.openApiVersionNumber = AppSwagger2.staticSetOpenApiVersionNumber(siteRequest_, o);
	}
	public static Integer staticSetOpenApiVersionNumber(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected AppSwagger2 openApiVersionNumberInit() {
		Wrap<Integer> openApiVersionNumberWrap = new Wrap<Integer>().var("openApiVersionNumber");
		if(openApiVersionNumber == null) {
			_openApiVersionNumber(openApiVersionNumberWrap);
			setOpenApiVersionNumber(openApiVersionNumberWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static Integer staticSolrOpenApiVersionNumber(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrOpenApiVersionNumber(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqOpenApiVersionNumber(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrOpenApiVersionNumber(siteRequest_, AppSwagger2.staticSolrOpenApiVersionNumber(siteRequest_, AppSwagger2.staticSetOpenApiVersionNumber(siteRequest_, o)));
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:tabsSchema">Find the entity tabsSchema in Solr</a>
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
		this.tabsSchema = AppSwagger2.staticSetTabsSchema(siteRequest_, o);
	}
	public static Integer staticSetTabsSchema(SiteRequestEnUS siteRequest_, String o) {
		if(NumberUtils.isParsable(o))
			return Integer.parseInt(o);
		return null;
	}
	protected AppSwagger2 tabsSchemaInit() {
		Wrap<Integer> tabsSchemaWrap = new Wrap<Integer>().var("tabsSchema");
		if(tabsSchema == null) {
			_tabsSchema(tabsSchemaWrap);
			setTabsSchema(tabsSchemaWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static Integer staticSolrTabsSchema(SiteRequestEnUS siteRequest_, Integer o) {
		return o;
	}

	public static String staticSolrStrTabsSchema(SiteRequestEnUS siteRequest_, Integer o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqTabsSchema(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrTabsSchema(siteRequest_, AppSwagger2.staticSolrTabsSchema(siteRequest_, AppSwagger2.staticSetTabsSchema(siteRequest_, o)));
	}

	////////////////
	// apiVersion //
	////////////////

	/**	 The entity apiVersion
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String apiVersion;

	/**	<br/> The entity apiVersion
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:apiVersion">Find the entity apiVersion in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _apiVersion(Wrap<String> c);

	public String getApiVersion() {
		return apiVersion;
	}
	public void setApiVersion(String o) {
		this.apiVersion = AppSwagger2.staticSetApiVersion(siteRequest_, o);
	}
	public static String staticSetApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected AppSwagger2 apiVersionInit() {
		Wrap<String> apiVersionWrap = new Wrap<String>().var("apiVersion");
		if(apiVersion == null) {
			_apiVersion(apiVersionWrap);
			setApiVersion(apiVersionWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static String staticSolrApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqApiVersion(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrApiVersion(siteRequest_, AppSwagger2.staticSolrApiVersion(siteRequest_, AppSwagger2.staticSetApiVersion(siteRequest_, o)));
	}

	/////////////////////
	// openApiYamlPath //
	/////////////////////

	/**	 The entity openApiYamlPath
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected String openApiYamlPath;

	/**	<br/> The entity openApiYamlPath
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:openApiYamlPath">Find the entity openApiYamlPath in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _openApiYamlPath(Wrap<String> c);

	public String getOpenApiYamlPath() {
		return openApiYamlPath;
	}
	public void setOpenApiYamlPath(String o) {
		this.openApiYamlPath = AppSwagger2.staticSetOpenApiYamlPath(siteRequest_, o);
	}
	public static String staticSetOpenApiYamlPath(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}
	protected AppSwagger2 openApiYamlPathInit() {
		Wrap<String> openApiYamlPathWrap = new Wrap<String>().var("openApiYamlPath");
		if(openApiYamlPath == null) {
			_openApiYamlPath(openApiYamlPathWrap);
			setOpenApiYamlPath(openApiYamlPathWrap.o);
		}
		return (AppSwagger2)this;
	}

	public static String staticSolrOpenApiYamlPath(SiteRequestEnUS siteRequest_, String o) {
		return o;
	}

	public static String staticSolrStrOpenApiYamlPath(SiteRequestEnUS siteRequest_, String o) {
		return o == null ? null : o.toString();
	}

	public static String staticSolrFqOpenApiYamlPath(SiteRequestEnUS siteRequest_, String o) {
		return AppSwagger2.staticSolrStrOpenApiYamlPath(siteRequest_, AppSwagger2.staticSolrOpenApiYamlPath(siteRequest_, AppSwagger2.staticSetOpenApiYamlPath(siteRequest_, o)));
	}

	/////////////////////
	// openApiYamlFile //
	/////////////////////

	/**	 The entity openApiYamlFile
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected File openApiYamlFile;

	/**	<br/> The entity openApiYamlFile
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:openApiYamlFile">Find the entity openApiYamlFile in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _openApiYamlFile(Wrap<File> c);

	public File getOpenApiYamlFile() {
		return openApiYamlFile;
	}

	public void setOpenApiYamlFile(File openApiYamlFile) {
		this.openApiYamlFile = openApiYamlFile;
	}
	public static File staticSetOpenApiYamlFile(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AppSwagger2 openApiYamlFileInit() {
		Wrap<File> openApiYamlFileWrap = new Wrap<File>().var("openApiYamlFile");
		if(openApiYamlFile == null) {
			_openApiYamlFile(openApiYamlFileWrap);
			setOpenApiYamlFile(openApiYamlFileWrap.o);
		}
		return (AppSwagger2)this;
	}

	///////
	// w //
	///////

	/**	 The entity w
	 *	 is defined as null before being initialized. 
	 */
	@JsonProperty
	@JsonInclude(Include.NON_NULL)
	protected AllWriter w;

	/**	<br/> The entity w
	 *  is defined as null before being initialized. 
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:w">Find the entity w in Solr</a>
	 * <br/>
	 * @param c is for wrapping a value to assign to this entity during initialization. 
	 **/
	protected abstract void _w(Wrap<AllWriter> c);

	public AllWriter getW() {
		return w;
	}

	public void setW(AllWriter w) {
		this.w = w;
	}
	public static AllWriter staticSetW(SiteRequestEnUS siteRequest_, String o) {
		return null;
	}
	protected AppSwagger2 wInit() {
		Wrap<AllWriter> wWrap = new Wrap<AllWriter>().var("w");
		if(w == null) {
			_w(wWrap);
			setW(wWrap.o);
		}
		if(w != null)
			w.initDeepForClass(siteRequest_);
		return (AppSwagger2)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wPaths">Find the entity wPaths in Solr</a>
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
	protected AppSwagger2 wPathsInit() {
		Wrap<AllWriter> wPathsWrap = new Wrap<AllWriter>().var("wPaths");
		if(wPaths == null) {
			_wPaths(wPathsWrap);
			setWPaths(wPathsWrap.o);
		}
		if(wPaths != null)
			wPaths.initDeepForClass(siteRequest_);
		return (AppSwagger2)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wRequestBodies">Find the entity wRequestBodies in Solr</a>
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
	protected AppSwagger2 wRequestBodiesInit() {
		Wrap<AllWriter> wRequestBodiesWrap = new Wrap<AllWriter>().var("wRequestBodies");
		if(wRequestBodies == null) {
			_wRequestBodies(wRequestBodiesWrap);
			setWRequestBodies(wRequestBodiesWrap.o);
		}
		if(wRequestBodies != null)
			wRequestBodies.initDeepForClass(siteRequest_);
		return (AppSwagger2)this;
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
	 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstEntite_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.AppSwagger2&fq=classeEtendGen_indexed_boolean:true&fq=entiteVar_enUS_indexed_string:wSchemas">Find the entity wSchemas in Solr</a>
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
	protected AppSwagger2 wSchemasInit() {
		Wrap<AllWriter> wSchemasWrap = new Wrap<AllWriter>().var("wSchemas");
		if(wSchemas == null) {
			_wSchemas(wSchemasWrap);
			setWSchemas(wSchemasWrap.o);
		}
		if(wSchemas != null)
			wSchemas.initDeepForClass(siteRequest_);
		return (AppSwagger2)this;
	}

	//////////////
	// initDeep //
	//////////////

	public AppSwagger2 initDeepAppSwagger2(SiteRequestEnUS siteRequest_) {
		setSiteRequest_(siteRequest_);
		initDeepAppSwagger2();
		return (AppSwagger2)this;
	}

	public void initDeepAppSwagger2() {
		initAppSwagger2();
	}

	public void initAppSwagger2() {
				solrClientComputateInit();
				siteRequest_Init();
				configInit();
				appNameInit();
				languageNameInit();
				appPathInit();
				openApiVersionInit();
				openApiVersionNumberInit();
				tabsSchemaInit();
				apiVersionInit();
				openApiYamlPathInit();
				openApiYamlFileInit();
				wInit();
				wPathsInit();
				wRequestBodiesInit();
				wSchemasInit();
	}

	public void initDeepForClass(SiteRequestEnUS siteRequest_) {
		initDeepAppSwagger2(siteRequest_);
	}

	/////////////////
	// siteRequest //
	/////////////////

	public void siteRequestAppSwagger2(SiteRequestEnUS siteRequest_) {
		if(w != null)
			w.setSiteRequest_(siteRequest_);
		if(wPaths != null)
			wPaths.setSiteRequest_(siteRequest_);
		if(wRequestBodies != null)
			wRequestBodies.setSiteRequest_(siteRequest_);
		if(wSchemas != null)
			wSchemas.setSiteRequest_(siteRequest_);
	}

	public void siteRequestForClass(SiteRequestEnUS siteRequest_) {
		siteRequestAppSwagger2(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainAppSwagger2(v);
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
	public Object obtainAppSwagger2(String var) {
		AppSwagger2 oAppSwagger2 = (AppSwagger2)this;
		switch(var) {
			case "solrClientComputate":
				return oAppSwagger2.solrClientComputate;
			case "siteRequest_":
				return oAppSwagger2.siteRequest_;
			case "config":
				return oAppSwagger2.config;
			case "appName":
				return oAppSwagger2.appName;
			case "languageName":
				return oAppSwagger2.languageName;
			case "appPath":
				return oAppSwagger2.appPath;
			case "openApiVersion":
				return oAppSwagger2.openApiVersion;
			case "openApiVersionNumber":
				return oAppSwagger2.openApiVersionNumber;
			case "tabsSchema":
				return oAppSwagger2.tabsSchema;
			case "apiVersion":
				return oAppSwagger2.apiVersion;
			case "openApiYamlPath":
				return oAppSwagger2.openApiYamlPath;
			case "openApiYamlFile":
				return oAppSwagger2.openApiYamlFile;
			case "w":
				return oAppSwagger2.w;
			case "wPaths":
				return oAppSwagger2.wPaths;
			case "wRequestBodies":
				return oAppSwagger2.wRequestBodies;
			case "wSchemas":
				return oAppSwagger2.wSchemas;
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
				o = relateAppSwagger2(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relateAppSwagger2(String var, Object val) {
		AppSwagger2 oAppSwagger2 = (AppSwagger2)this;
		switch(var) {
			default:
				return null;
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetAppSwagger2(entityVar,  siteRequest_, o);
	}
	public static Object staticSetAppSwagger2(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "appName":
			return AppSwagger2.staticSetAppName(siteRequest_, o);
		case "languageName":
			return AppSwagger2.staticSetLanguageName(siteRequest_, o);
		case "appPath":
			return AppSwagger2.staticSetAppPath(siteRequest_, o);
		case "openApiVersion":
			return AppSwagger2.staticSetOpenApiVersion(siteRequest_, o);
		case "openApiVersionNumber":
			return AppSwagger2.staticSetOpenApiVersionNumber(siteRequest_, o);
		case "tabsSchema":
			return AppSwagger2.staticSetTabsSchema(siteRequest_, o);
		case "apiVersion":
			return AppSwagger2.staticSetApiVersion(siteRequest_, o);
		case "openApiYamlPath":
			return AppSwagger2.staticSetOpenApiYamlPath(siteRequest_, o);
			default:
				return null;
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrAppSwagger2(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrAppSwagger2(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "appName":
			return AppSwagger2.staticSolrAppName(siteRequest_, (String)o);
		case "languageName":
			return AppSwagger2.staticSolrLanguageName(siteRequest_, (String)o);
		case "appPath":
			return AppSwagger2.staticSolrAppPath(siteRequest_, (String)o);
		case "openApiVersion":
			return AppSwagger2.staticSolrOpenApiVersion(siteRequest_, (String)o);
		case "openApiVersionNumber":
			return AppSwagger2.staticSolrOpenApiVersionNumber(siteRequest_, (Integer)o);
		case "tabsSchema":
			return AppSwagger2.staticSolrTabsSchema(siteRequest_, (Integer)o);
		case "apiVersion":
			return AppSwagger2.staticSolrApiVersion(siteRequest_, (String)o);
		case "openApiYamlPath":
			return AppSwagger2.staticSolrOpenApiYamlPath(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrAppSwagger2(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrAppSwagger2(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
		case "appName":
			return AppSwagger2.staticSolrStrAppName(siteRequest_, (String)o);
		case "languageName":
			return AppSwagger2.staticSolrStrLanguageName(siteRequest_, (String)o);
		case "appPath":
			return AppSwagger2.staticSolrStrAppPath(siteRequest_, (String)o);
		case "openApiVersion":
			return AppSwagger2.staticSolrStrOpenApiVersion(siteRequest_, (String)o);
		case "openApiVersionNumber":
			return AppSwagger2.staticSolrStrOpenApiVersionNumber(siteRequest_, (Integer)o);
		case "tabsSchema":
			return AppSwagger2.staticSolrStrTabsSchema(siteRequest_, (Integer)o);
		case "apiVersion":
			return AppSwagger2.staticSolrStrApiVersion(siteRequest_, (String)o);
		case "openApiYamlPath":
			return AppSwagger2.staticSolrStrOpenApiYamlPath(siteRequest_, (String)o);
			default:
				return null;
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqAppSwagger2(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqAppSwagger2(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
		case "appName":
			return AppSwagger2.staticSolrFqAppName(siteRequest_, o);
		case "languageName":
			return AppSwagger2.staticSolrFqLanguageName(siteRequest_, o);
		case "appPath":
			return AppSwagger2.staticSolrFqAppPath(siteRequest_, o);
		case "openApiVersion":
			return AppSwagger2.staticSolrFqOpenApiVersion(siteRequest_, o);
		case "openApiVersionNumber":
			return AppSwagger2.staticSolrFqOpenApiVersionNumber(siteRequest_, o);
		case "tabsSchema":
			return AppSwagger2.staticSolrFqTabsSchema(siteRequest_, o);
		case "apiVersion":
			return AppSwagger2.staticSolrFqApiVersion(siteRequest_, o);
		case "openApiYamlPath":
			return AppSwagger2.staticSolrFqOpenApiYamlPath(siteRequest_, o);
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
					o = defineAppSwagger2(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineAppSwagger2(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	//////////////////
	// apiRequest //
	//////////////////

	public void apiRequestAppSwagger2() {
		ApiRequest apiRequest = Optional.ofNullable(siteRequest_).map(SiteRequestEnUS::getApiRequest_).orElse(null);
		Object o = Optional.ofNullable(apiRequest).map(ApiRequest::getOriginal).orElse(null);
		if(o != null && o instanceof AppSwagger2) {
			AppSwagger2 original = (AppSwagger2)o;
		}
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		return sb.toString();
	}

	public static final String VAR_solrClientComputate = "solrClientComputate";
	public static final String VAR_siteRequest_ = "siteRequest_";
	public static final String VAR_config = "config";
	public static final String VAR_appName = "appName";
	public static final String VAR_languageName = "languageName";
	public static final String VAR_appPath = "appPath";
	public static final String VAR_openApiVersion = "openApiVersion";
	public static final String VAR_openApiVersionNumber = "openApiVersionNumber";
	public static final String VAR_tabsSchema = "tabsSchema";
	public static final String VAR_apiVersion = "apiVersion";
	public static final String VAR_openApiYamlPath = "openApiYamlPath";
	public static final String VAR_openApiYamlFile = "openApiYamlFile";
	public static final String VAR_w = "w";
	public static final String VAR_wPaths = "wPaths";
	public static final String VAR_wRequestBodies = "wRequestBodies";
	public static final String VAR_wSchemas = "wSchemas";
}
