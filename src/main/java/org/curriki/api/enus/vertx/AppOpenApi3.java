package org.curriki.api.enus.vertx;

import java.io.File;
import java.util.Iterator;

import org.apache.commons.configuration2.INIConfiguration;
import org.apache.commons.configuration2.builder.fluent.Configurations;
import org.apache.commons.configuration2.ex.ConfigurationException;
import org.apache.commons.lang3.StringUtils;
import org.apache.commons.lang3.exception.ExceptionUtils;
import org.apache.solr.client.solrj.impl.HttpSolrClient;

import org.curriki.api.enus.config.ConfigKeys;
import org.curriki.api.enus.request.SiteRequestEnUS;
import org.curriki.api.enus.wrap.Wrap;

import io.vertx.core.json.JsonObject;

public class AppOpenApi3 extends AppOpenApi3Gen<AppSwagger2> {

	@Override()
	protected void  _apiVersion(Wrap<String> c) {
		c.o("3.0.0");
	}

	public static void  main(String[] args) {
		Configurations configurations = new Configurations();
		String configPath = System.getenv(ConfigKeys.CONFIG_PATH);
		File configFile = new File(configPath);

		if(configFile.exists()) {
			try {
				INIConfiguration iniConfig = configurations.ini(configFile);
				JsonObject config = new JsonObject();
				Iterator<String> keyIterator = iniConfig.getKeys();
				while(keyIterator.hasNext()) {
					String key = keyIterator.next();
					String shortKey = key;
					if(StringUtils.contains(key, "."))
						shortKey = StringUtils.substringAfter(shortKey, ".");
					String value = iniConfig.getString(key);
					config.put(shortKey, value);
				}

				AppOpenApi3 api = new AppOpenApi3();
				HttpSolrClient solrClientComputate = new HttpSolrClient.Builder(config.getString(ConfigKeys.SOLR_URL_COMPUTATE)).build();
				SiteRequestEnUS siteRequest = new SiteRequestEnUS();
				siteRequest.setConfig(config);
				api.setSolrClientComputate(solrClientComputate);
				api.setConfig(config);
				siteRequest.initDeepSiteRequestEnUS();
				api.initDeepAppOpenApi3(siteRequest);
				api.writeOpenApi();
			} catch (ConfigurationException e) {
				ExceptionUtils.rethrow(e);
			}
		}
	}
}
