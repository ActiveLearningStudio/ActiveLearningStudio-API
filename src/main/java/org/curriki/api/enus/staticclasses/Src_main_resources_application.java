package org.curriki.api.enus.staticclasses;

/**
 * ContentType: text/plain
 * DisplayName.enUS: ActiveLearningStudio-API/src/main/resources/application.properties
 * Map.hackathonLabel: create_java_class_Src_main_resources_application
 * Map.hackathonMission: Create the file ActiveLearningStudio-API/src/main/resources/application.properties
 * Map.hackathonTitle: create an application.properties file for defining the default config variable values
 * Map.hackathonColumn: Develop Base Resources
 * Map.hackathonLabels: Java,Maven
 * enUS: Define the JDBC Database properties. 
 * enUS: Define the site properties. 
 * enUS: Define the Single-Sign On Authentication properties. 
 * enUS: Define the Apache Zookeeper cluster manager properties. 
 * enUS: Define the Apache Solr search engine properties. 
 * enUS: Define the SMTP Email properties. 
 * enUS: Define the static content properties. 
 * enUS: Define the API import properties. 
 * enUS: Define the database to Solr sync properties, in case you restore the site from a database backup. 
 * enUS: Enable the refresh data properties, in case you want to reindex the data in the search engine after doing more search engine tuning. 
 */
public class Src_main_resources_application {

	/**
	 * DisplayName.enUS: Define the JDBC Database properties. 
	 * enUS: Create a new file: /usr/local/src/ActiveLearningStudio-API/src/main/resources/application.properties
	 * enUS: 
	 * enUS: 
	 */
	public void part1() {
//# Define the JDBC Database properties. 
//JDBC_HOST=localhost
//JDBC_PORT=5432
//JDBC_DATABASE=ActiveLearningStudio-API
//JDBC_USERNAME=ActiveLearningStudio-API
//JDBC_PASSWORD=
//JDBC_MAX_POOL_SIZE=10
//JDBC_MAX_WAIT_QUEUE_SIZE=1000
//
//# Define the site properties. 
//SITE_HOST_NAME=localhost
//SITE_PORT=11281
//SITE_BASE_URL=http://localhost:11281
//SITE_ZONE=America/Denver
	}

	/**
	 * DisplayName.enUS: Define the Single-Sign On Authentication properties. 
	 * enUS: 
	 */
	public void part2() {
//
//# Define the Single-Sign On Authentication properties. 
//AUTH_REALM=
//AUTH_RESOURCE=
//AUTH_SECRET=
//AUTH_HOST_NAME=
//AUTH_PORT=443
//AUTH_SSL=true
//AUTH_SSL_REQUIRED=all
//AUTH_TOKEN_URI=/auth/realms/AUTH_REALM/protocol/openid-connect/token
	}

	/**
	 * DisplayName.enUS: Define the Apache Zookeeper cluster manager properties. 
	 * enUS: 
	 */
	public void part3() {
//
//# Define the Apache Zookeeper cluster manager properties. 
//ZOOKEEPER_HOST_NAME=localhost
//ZOOKEEPER_PORT=2181
	}

	/**
	 * DisplayName.enUS: Define the Apache Solr search engine properties. 
	 * enUS: 
	 */
	public void part4() {
//
//# Define the Apache Solr search engine properties. 
//SOLR_URL=http://localhost:8983/solr/ActiveLearningStudio-API
//SOLR_HOST_NAME=localhost
//SOLR_PORT=8983
//SOLR_COLLECTION=ActiveLearningStudio-API
//SOLR_URL_COMPUTATE=http://localhost:8983/solr/computate
	}

	/**
	 * DisplayName.enUS: Define the SMTP Email properties. 
	 * enUS: 
	 */
	public void part5() {
//
//# Define the SMTP Email properties. 
//API_CONTACT_EMAIL=
//EMAIL_HOST=
//EMAIL_PORT=
//EMAIL_SSL=
//EMAIL_AUTH=
//EMAIL_USERNAME=
//EMAIL_PASSWORD=
//EMAIL_FROM=
//EMAIL_ADMIN=
	}

	/**
	 * DisplayName.enUS: Define the static content properties. 
	 * enUS: 
	 */
	public void part6() {
//
//# Define the static content properties. 
//STATIC_BASE_URL=http://localhost:11281/static
//STATIC_PATH=/usr/local/src/ActiveLearningStudio-API-static
	}

	/**
	 * DisplayName.enUS: Define the API import properties. 
	 * enUS: 
	 */
	public void part7() {
//
//# Define the API import properties. 
//API_COUNTER_PAUSE=10
//API_COUNTER_RESUME=5
	}

	/**
	 * DisplayName.enUS: Define the database to Solr sync properties, in case you restore the site from a database backup. 
	 * enUS: 
	 */
	public void part8() {
//
//# Define the database to Solr sync properties, in case you restore the site from a database backup. 
//TIMER_DB_SOLR_SYNC_IN_SECONDS=1
//ENABLE_DB_SOLR_SYNC=false
	}

	/**
	 * DisplayName.enUS: Enable the refresh data properties, in case you want to reindex the data in the search engine after doing more search engine tuning. 
	 * enUS: 
	 */
	public void part9() {
//
//# Enable the refresh data properties, in case you want to reindex the data in the search engine after doing more search engine tuning. 
//ENABLE_REFRESH_DATA=false
	}
}
