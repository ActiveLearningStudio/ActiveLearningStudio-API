package org.curriki.api.enus.vertx;

import java.io.File;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import org.apache.commons.lang3.BooleanUtils;
import org.apache.commons.lang3.StringUtils;
import org.apache.commons.lang3.exception.ExceptionUtils;
import org.apache.solr.client.solrj.SolrClient;
import org.apache.solr.client.solrj.SolrQuery;
import org.apache.solr.client.solrj.SolrQuery.ORDER;
import org.apache.solr.client.solrj.response.QueryResponse;
import org.apache.solr.client.solrj.util.ClientUtils;
import org.apache.solr.common.SolrDocument;
import org.apache.solr.common.SolrDocumentList;

import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;
import org.curriki.api.enus.writer.AllWriter;
import org.curriki.api.enus.writer.ApiWriter;

import io.vertx.core.json.JsonObject;

public class AppSwagger2 extends AppSwagger2Gen<Object> {

	public static void  main(String[] args) {
		AppSwagger2 api = new AppSwagger2();
		api.initDeepAppSwagger2();
		api.writeOpenApi();
	}

	protected void _solrClientComputate(Wrap<SolrClient> w) {
	}

	protected void _siteRequest_(Wrap<SiteRequestEnUS> c) {
	}

	protected void _config(Wrap<JsonObject> c) {
	}

	protected void _appName(Wrap<String> c) {
		c.o("ActiveLearningStudio-API");
	}

	protected void _languageName(Wrap<String> c) {
		c.o("enUS");
	}

	protected void _appPath(Wrap<String> c) {
		c.o(config.getString(ConfigKeys.APP_PATH + "_" + languageName));
	}

	protected void _openApiVersion(Wrap<String> c) {
		c.o(config.getString(ConfigKeys.OPEN_API_VERSION, "3.0"));
	}

	protected void _openApiVersionNumber(Wrap<Integer> c) {
		c.o((int)Double.parseDouble(StringUtils.substringBefore(openApiVersion, ".")));
	}

	protected void _tabsSchema(Wrap<Integer> c) {
		if(openApiVersionNumber == 2)
			c.o(1);
		else
			c.o(2);
	}

	protected void _apiVersion(Wrap<String> c) {
		c.o(config.getString(ConfigKeys.API_VERSION));
	}

	protected void _openApiYamlPath(Wrap<String> c) {
		c.o(appPath + "/src/main/resources/webroot/" + ("2.0".equals(apiVersion) ? "swagger2" : "openapi3") + "-enUS.yaml");
	}

	protected void _openApiYamlFile(Wrap<File> c) {
		c.o(new File(openApiYamlPath));
	}

	protected void _w(Wrap<AllWriter> c) {
		c.o(AllWriter.create(siteRequest_, openApiYamlFile, "  "));
	}

	protected void _wPaths(Wrap<AllWriter> c) {
		c.o(AllWriter.create(siteRequest_, "  "));
	}

	protected void _wRequestBodies(Wrap<AllWriter> c) {
		c.o(AllWriter.create(siteRequest_, "  "));
	}

	protected void _wSchemas(Wrap<AllWriter> c) {
		c.o(AllWriter.create(siteRequest_, "  "));
	}

	List<String> classApiMethods;

	List<String> classUris;

	List<ApiWriter> apiWriters;

	String classApiTag;

	String classApiUri;

	String classSimpleName;

	String classAbsolutePath;

	Boolean classIsBase;

	Integer contextRows;

	Boolean classKeywordsFound;

	List<String> classKeywords;

	public void  writeOpenApi() {

		writeInfo();
		writeApi();

		w.s(wPaths.toString());
		w.s(wRequestBodies.toString());
		w.s(wSchemas.toString());

		w.flushClose();
	}

	public void  writeInfo() {

		if(openApiVersionNumber == 2)
			wPaths.l("swagger: \"", apiVersion, "\"");
		else
			wPaths.l("openapi: ", apiVersion);

		wPaths.l("info:");

		wPaths.t(1, "title: ").string(config.getString(ConfigKeys.API_TITLE)).l();
//		wPaths.t(1, "description: ").yamlStr(2, siteConfig.getApiDescription());
		if(openApiVersionNumber == 2) {
			wPaths.t(1, "version: ").string(apiVersion).l();
		}
		else if(openApiVersionNumber > 2) {
			wPaths.tl(1, "version: ", apiVersion);
		}
	}

	public void  writeApi() {
		try {
			wPaths.tl(0, "paths:");
			wPaths.l();
			wPaths.tl(1, "/callback:");
			wPaths.tl(2, "get:");
			wPaths.tl(3, "operationId: callback");
			wPaths.tl(3, "x-vertx-event-bus: ", appName, "-", languageName, "-callback");
			wPaths.tl(3, "description: >+");
			wPaths.tl(3, "responses:");
			wPaths.tl(4, "'200':");
			wPaths.tl(5, "description: >+");
			wPaths.tl(5, "content:");
			wPaths.tl(6, "application/json; charset=utf-8:");
			wPaths.tl(7, "schema:");
			wPaths.tl(8, "type: string");
			wPaths.l();
			wPaths.tl(1, "/logout:");
			wPaths.tl(2, "get:");
			wPaths.tl(3, "operationId: logout");
			wPaths.tl(3, "x-vertx-event-bus: ", appName, "-", languageName, "-logout");
			wPaths.tl(3, "description: >+");
			wPaths.tl(3, "responses:");
			wPaths.tl(4, "'200':");
			wPaths.tl(5, "description: >+");
			wPaths.tl(5, "content:");
			wPaths.tl(6, "application/json; charset=utf-8:");
			wPaths.tl(7, "schema:");
			wPaths.tl(8, "type: string");
			wPaths.l();

			if(openApiVersionNumber == 2) {
				wSchemas.tl(0, "definitions:");
			}
			else {
				wRequestBodies.tl(0, "components:");
				if(config.getString(ConfigKeys.AUTH_URL) != null) {
					wRequestBodies.tl(1, "securitySchemes:");
						wRequestBodies.tl(2, "basicAuth:");
						wRequestBodies.tl(3, "type: http");
						wRequestBodies.tl(3, "scheme: basic");
						wRequestBodies.tl(2, "openIdConnect:");
						wRequestBodies.tl(3, "type: openIdConnect");
						wRequestBodies.tl(3, "openIdConnectUrl: ", config.getString(ConfigKeys.AUTH_URL), "/realms/", config.getString(ConfigKeys.AUTH_REALM), "/.well-known/openid-configuration");
				}
				wRequestBodies.tl(1, "requestBodies:");

				wSchemas.tl(1, "schemas:");
			}

			SolrQuery searchClasses = new SolrQuery();
			searchClasses.setQuery("*:*");
			searchClasses.setRows(1000000);
			searchClasses.addFilterQuery("appliChemin_indexed_string:" + ClientUtils.escapeQueryChars(appPath));
			searchClasses.addFilterQuery("classeApi_indexed_boolean:true");
			searchClasses.addFilterQuery("partEstClasse_indexed_boolean:true");
			searchClasses.addSort("classeNomCanonique_enUS_indexed_string", ORDER.asc);
			searchClasses.addSort("partNumero_indexed_int", ORDER.asc);
			QueryResponse searchClassesResponse = solrClientComputate.query(searchClasses);
			SolrDocumentList searchClassesResultats = searchClassesResponse.getResults();
			Integer searchClassesLines = searchClasses.getRows();
			for(Long i = searchClassesResultats.getStart(); i < searchClassesResultats.getNumFound(); i+=searchClassesLines) {
				for(Integer j = 0; j < searchClassesResultats.size(); j++) {
					SolrDocument classSolrDocument = searchClassesResultats.get(j);

					classApiTag = StringUtils.defaultIfBlank((String)classSolrDocument.get("classeApiTag_enUS_stored_string"), classSimpleName + " API");
					classApiUri = (String)classSolrDocument.get("classeApiUri_enUS_stored_string");
					classIsBase = (Boolean)classSolrDocument.get("classeEstBase_stored_boolean");
					contextRows = (Integer)classSolrDocument.get("contexteRows_stored_int");

					classApiMethods = (List<String>)classSolrDocument.get("classeApiMethodes_enUS_stored_strings");
					classUris = new ArrayList<>();
					if(classApiMethods == null)
						classApiMethods = new ArrayList<>();
					apiWriters = new ArrayList<>();

					for(String classApiMethode : classApiMethods) {
						ApiWriter apiWriter = new ApiWriter();
						apiWriter.setClassSolrDocument(classSolrDocument);
						apiWriter.setClassApiMethod(classApiMethode);
						apiWriter.setContextRows(contextRows);
						apiWriter.setWPaths(wPaths);
						apiWriter.setWRequestBodies(wRequestBodies);
						apiWriter.setWSchemas(wSchemas);
						apiWriter.setOpenApiVersion(openApiVersion);
						apiWriter.setAppSwagger2(this);
						apiWriter.setSolrClientComputate(solrClientComputate);
						apiWriter.setClassUris(classUris);
						apiWriter.initDeepApiWriter(siteRequest_);
						apiWriters.add(apiWriter);
					}
					Collections.sort(apiWriters);

					classSimpleName = (String)classSolrDocument.get("classSimpleName_enUS_stored_string");
					classAbsolutePath = (String)classSolrDocument.get("classeCheminAbsolu_stored_string");

					classKeywordsFound = BooleanUtils.isTrue((Boolean)classSolrDocument.get("classeMotsClesTrouves_stored_boolean"));
					classKeywords = (List<String>)classSolrDocument.get("classeMotsCles_stored_strings");

					SolrQuery searchEntites = new SolrQuery();
					searchEntites.setQuery("*:*");
					searchEntites.setRows(1000000);
					searchEntites.addFilterQuery("appliChemin_indexed_string:" + ClientUtils.escapeQueryChars(appPath));
					searchEntites.addFilterQuery("classeCheminAbsolu_indexed_string:" + ClientUtils.escapeQueryChars(classAbsolutePath));
					searchEntites.addFilterQuery("partEstEntite_indexed_boolean:true");
					searchEntites.addSort("partNumero_indexed_int", ORDER.asc);
					QueryResponse searchEntitesResponse = solrClientComputate.query(searchEntites);
					SolrDocumentList searchEntitesResults = searchEntitesResponse.getResults();
					Integer searchEntitesLines = searchEntites.getRows();

					for(Long k = searchEntitesResults.getStart(); k < searchEntitesResults.getNumFound(); k+=searchEntitesLines) {
						for(Integer l = 0; l < searchEntitesResults.size(); l++) {
							for(ApiWriter apiWriter : apiWriters) {
								SolrDocument entiteDocumentSolr = searchEntitesResults.get(l);

								apiWriter.initEntity(entiteDocumentSolr);
								apiWriter.writeEntityHeaders();
								apiWriter.writeEntitySchema(null);
							}
						}
						searchEntites.setStart(i.intValue() + searchEntitesLines);
						searchEntitesResponse = solrClientComputate.query(searchEntites);
						searchEntitesResults = searchEntitesResponse.getResults();
						searchEntitesLines = searchEntites.getRows();
					}
					for(ApiWriter apiWriter : apiWriters) {
						apiWriter.getWriters().flushClose();
						apiWriter.writeApi(false);
					}

					for(ApiWriter apiWriter : apiWriters) {
						apiWriter.getWResponseDescription().flushClose();
					}
				}
				searchClasses.setStart(i.intValue() + searchClassesLines);
				searchClassesResponse = solrClientComputate.query(searchClasses);
				searchClassesResultats = searchClassesResponse.getResults();
				searchClassesLines = searchClasses.getRows();
			}
		} catch (Exception e) {
			ExceptionUtils.rethrow(e);
		}
	}
}
