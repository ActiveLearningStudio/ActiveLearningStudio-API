package org.curriki.api.enus.vertx;

import com.fasterxml.jackson.annotation.JsonProperty;
import java.util.Arrays;
import com.fasterxml.jackson.databind.ser.std.ToStringSerializer;
import org.curriki.api.enus.base.BaseModel;
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
import com.fasterxml.jackson.databind.annotation.JsonSerialize;
import java.util.Map;
import com.fasterxml.jackson.annotation.JsonIgnore;
import org.curriki.api.enus.java.ZonedDateTimeSerializer;
import java.math.RoundingMode;
import org.slf4j.Logger;
import java.math.MathContext;
import io.vertx.core.Promise;
import org.apache.commons.text.StringEscapeUtils;
import com.fasterxml.jackson.annotation.JsonInclude.Include;
import com.fasterxml.jackson.annotation.JsonFormat;
import org.curriki.api.enus.config.ConfigKeys;
import io.vertx.core.Future;
import java.util.Objects;
import io.vertx.core.json.JsonArray;
import io.vertx.core.AbstractVerticle;
import org.apache.commons.lang3.math.NumberUtils;
import java.util.Optional;
import com.fasterxml.jackson.annotation.JsonInclude;
import com.fasterxml.jackson.databind.annotation.JsonDeserialize;
import org.curriki.api.enus.java.LocalDateSerializer;

/**	
 * <br/><a href="http://localhost:8983/solr/computate/select?q=*:*&fq=partEstClasse_indexed_boolean:true&fq=classeNomCanonique_enUS_indexed_string:org.curriki.api.enus.vertx.WorkerVerticle&fq=classeEtendGen_indexed_boolean:true">Find the class  in Solr. </a>
 * <br/>
 **/
public abstract class WorkerVerticleGen<DEV> extends AbstractVerticle {
	protected static final Logger LOG = LoggerFactory.getLogger(WorkerVerticle.class);
	public static final String configureSharedWorkerExecutorFail1 = "Could not configure the shared worker executor. ";
	public static final String configureSharedWorkerExecutorFail = configureSharedWorkerExecutorFail1;
	public static final String configureSharedWorkerExecutorComplete1 = "The shared worker executor \"{}\" was configured successfully. ";
	public static final String configureSharedWorkerExecutorComplete = configureSharedWorkerExecutorComplete1;

	public static final String configureEmailComplete1 = "Configure sending email succeeded. ";
	public static final String configureEmailComplete = configureEmailComplete1;
	public static final String configureEmailFail1 = "Configure sending email failed. ";
	public static final String configureEmailFail = configureEmailFail1;

	public static final String importDataComplete1 = "Importing initial data completed. ";
	public static final String importDataComplete = importDataComplete1;
	public static final String importDataFail1 = "Importing initial data failed. ";
	public static final String importDataFail = importDataFail1;
	public static final String importDataSkip1 = "data import skipped. ";
	public static final String importDataSkip = importDataSkip1;

	public static final String syncDbToSolrComplete1 = "Syncing database to Solr completed. ";
	public static final String syncDbToSolrComplete = syncDbToSolrComplete1;
	public static final String syncDbToSolrFail1 = "Syncing database to Solr failed. ";
	public static final String syncDbToSolrFail = syncDbToSolrFail1;
	public static final String syncDbToSolrSkip1 = "Skip syncing database to Solr. ";
	public static final String syncDbToSolrSkip = syncDbToSolrSkip1;

	public static final String syncDataComplete1 = "%s data sync completed. ";
	public static final String syncDataComplete = syncDataComplete1;
	public static final String syncDataFail1 = "%s data sync failed. ";
	public static final String syncDataFail = syncDataFail1;
	public static final String syncDataCounterResetFail1 = "%s data sync failed to reset counter. ";
	public static final String syncDataCounterResetFail = syncDataCounterResetFail1;
	public static final String syncDataSkip1 = "%s data sync skipped. ";
	public static final String syncDataSkip = syncDataSkip1;
	public static final String syncDataStarted1 = "%s data sync started. ";
	public static final String syncDataStarted = syncDataStarted1;

	public static final String refreshAllDataComplete1 = "Refresh all data completed. ";
	public static final String refreshAllDataComplete = refreshAllDataComplete1;
	public static final String refreshAllDataFail1 = "Refresh all data failed. ";
	public static final String refreshAllDataFail = refreshAllDataFail1;
	public static final String refreshAllDataSkip1 = "Refresh all data skipped. ";
	public static final String refreshAllDataSkip = refreshAllDataSkip1;

	public static final String refreshDataComplete1 = "%s refresh completed. ";
	public static final String refreshDataComplete = refreshDataComplete1;
	public static final String refreshDataFail1 = "%s refresh failed. ";
	public static final String refreshDataFail = refreshDataFail1;
	public static final String refreshDataSkip1 = "%s refresh skipped. ";
	public static final String refreshDataSkip = refreshDataSkip1;


	//////////////
	// initDeep //
	//////////////

	public WorkerVerticle initDeepWorkerVerticle(SiteRequestEnUS siteRequest_) {
		initDeepWorkerVerticle();
		return (WorkerVerticle)this;
	}

	public void initDeepWorkerVerticle() {
		initWorkerVerticle();
	}

	public void initWorkerVerticle() {
	}

	public void initDeepForClass(SiteRequestEnUS siteRequest_) {
		initDeepWorkerVerticle(siteRequest_);
	}

	/////////////
	// obtain //
	/////////////

	public Object obtainForClass(String var) {
		String[] vars = StringUtils.split(var, ".");
		Object o = null;
		for(String v : vars) {
			if(o == null)
				o = obtainWorkerVerticle(v);
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
	public Object obtainWorkerVerticle(String var) {
		WorkerVerticle oWorkerVerticle = (WorkerVerticle)this;
		switch(var) {
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
				o = relateWorkerVerticle(v, val);
			else if(o instanceof BaseModel) {
				BaseModel baseModel = (BaseModel)o;
				o = baseModel.relateForClass(v, val);
			}
		}
		return o != null;
	}
	public Object relateWorkerVerticle(String var, Object val) {
		WorkerVerticle oWorkerVerticle = (WorkerVerticle)this;
		switch(var) {
			default:
				return null;
		}
	}

	///////////////
	// staticSet //
	///////////////

	public static Object staticSetForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSetWorkerVerticle(entityVar,  siteRequest_, o);
	}
	public static Object staticSetWorkerVerticle(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
			default:
				return null;
		}
	}

	////////////////
	// staticSolr //
	////////////////

	public static Object staticSolrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrWorkerVerticle(entityVar,  siteRequest_, o);
	}
	public static Object staticSolrWorkerVerticle(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
			default:
				return null;
		}
	}

	///////////////////
	// staticSolrStr //
	///////////////////

	public static String staticSolrStrForClass(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		return staticSolrStrWorkerVerticle(entityVar,  siteRequest_, o);
	}
	public static String staticSolrStrWorkerVerticle(String entityVar, SiteRequestEnUS siteRequest_, Object o) {
		switch(entityVar) {
			default:
				return null;
		}
	}

	//////////////////
	// staticSolrFq //
	//////////////////

	public static String staticSolrFqForClass(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		return staticSolrFqWorkerVerticle(entityVar,  siteRequest_, o);
	}
	public static String staticSolrFqWorkerVerticle(String entityVar, SiteRequestEnUS siteRequest_, String o) {
		switch(entityVar) {
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
					o = defineWorkerVerticle(v, val);
				else if(o instanceof BaseModel) {
					BaseModel oBaseModel = (BaseModel)o;
					o = oBaseModel.defineForClass(v, val);
				}
			}
		}
		return o != null;
	}
	public Object defineWorkerVerticle(String var, Object val) {
		switch(var.toLowerCase()) {
			default:
				return null;
		}
	}

	//////////////
	// toString //
	//////////////

	@Override public String toString() {
		StringBuilder sb = new StringBuilder();
		return sb.toString();
	}

	public static final String[] WorkerVerticleVals = new String[] { configureSharedWorkerExecutorFail1, configureSharedWorkerExecutorComplete1, configureEmailComplete1, configureEmailFail1, importDataComplete1, importDataFail1, importDataSkip1, syncDbToSolrComplete1, syncDbToSolrFail1, syncDbToSolrSkip1, syncDataComplete1, syncDataFail1, syncDataCounterResetFail1, syncDataSkip1, syncDataStarted1, refreshAllDataComplete1, refreshAllDataFail1, refreshAllDataSkip1, refreshDataComplete1, refreshDataFail1, refreshDataSkip1 };

}
