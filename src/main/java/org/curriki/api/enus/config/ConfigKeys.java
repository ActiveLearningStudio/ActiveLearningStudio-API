package org.curriki.api.enus.config;

/**
 * Keyword: classSimpleNameConfigKeys
 */
public class ConfigKeys {

	/**
	 * The path to the config file of the site. 
	 **/
	public static final String CONFIG_PATH = "CONFIG_PATH";

	/**
	 * The INI Configuration Object for the config file. 
	 **/
	public static final String CONFIG = "CONFIG";

	/**
	 * The cluster host name. 
	 **/
	public static final String CLUSTER_HOST_NAME = "CLUSTER_HOST_NAME";

	/**
	 * The cluster port. 
	 **/
	public static final String CLUSTER_PORT = "CLUSTER_PORT";

	/**
	 * The cluster public host name. 
	 **/
	public static final String CLUSTER_PUBLIC_HOST_NAME = "CLUSTER_PUBLIC_HOST_NAME";

	/**
	 * The cluster public port. 
	 **/
	public static final String CLUSTER_PUBLIC_PORT = "CLUSTER_PUBLIC_PORT";

	/**
	 * The hostname environment variable. 
	 **/
	public static final String HOSTNAME = "HOSTNAME";

	/**
	 * The OpenShift project. 
	 **/
	public static final String OPENSHIFT_SERVICE = "OPENSHIFT_SERVICE";

	/**
	 * The name of the principal group of settings of the config for this website. 
	 **/
	public static final String SITE_IDENTIFIER = "SITE_IDENTIFIER";

	/**
	 * The path to the project of the site cloned from git. 
	 **/
	public static final String APP_PATH = "APP_PATH";

	/**
	 * The path to the basic authentication properties file with users and passwords. 
	 **/
	public static final String DOC_ROOT = "DOC_ROOT";

	/**
	 * The path to the docroot for the project. 
	 **/
	public static final String COMPANY_NAME = "COMPANY_NAME";

	/**
	 * The domain name of the site. 
	 **/
	public static final String DOMAIN_NAME = "DOMAIN_NAME";

	/**
	 * The host name of the site. 
	 **/
	public static final String SITE_HOST_NAME = "SITE_HOST_NAME";

	/**
	 * The port of the site. 
	 **/
	public static final String SITE_PORT = "SITE_PORT";

	/**
	 * The number of instances of the Vertx verticle to deploy. 
	 **/
	public static final String SITE_INSTANCES = "SITE_INSTANCES";

	/**
	 * 
	 **/
	public static final String API_COUNTER_RESUME = "API_COUNTER_RESUME";

	/**
	 * 
	 **/
	public static final String API_COUNTER_FETCH = "API_COUNTER_FETCH";

	/**
	 * 
	 **/
	public static final String API_CHECK_TIMER_MILLIS = "API_CHECK_TIMER_MILLIS";

	/**
	 * 
	 **/
	public static final String AUTH_REALM = "AUTH_REALM";

	/**
	 * The Auth client ID of the site. 
	 **/
	public static final String AUTH_RESOURCE = "AUTH_RESOURCE";

	/**
	 * The Auth client secret of the site. 
	 **/
	public static final String AUTH_SECRET = "AUTH_SECRET";

	/**
	 * Whether SSL is required in Auth for the site. 
	 **/
	public static final String AUTH_SSL_REQUIRED = "AUTH_SSL_REQUIRED";

	/**
	 * Enable SSL Passthrough. 
	 **/
	public static final String SSL_PASSTHROUGH = "SSL_PASSTHROUGH";

	/**
	 * The path to the Java keystore for the site. 
	 **/
	public static final String SSL_JKS_PATH = "SSL_JKS_PATH";

	/**
	 * The password for the Java keystore for the site. 
	 **/
	public static final String SSL_JKS_PASSWORD = "SSL_JKS_PASSWORD";

	/**
	 * The port to the Auth server. 
	 **/
	public static final String AUTH_PORT = "AUTH_PORT";

	/**
	 * Whether the Auth server uses SSL. 
	 **/
	public static final String AUTH_SSL = "AUTH_SSL";

	/**
	 * The token URI to the Auth server. 
	 **/
	public static final String AUTH_TOKEN_URI = "AUTH_TOKEN_URI";

	/**
	 * 
	 **/
	public static final String AUTH_HOST_NAME = "AUTH_HOST_NAME";

	/**
	 * The URL of the Auth server. 
	 **/
	public static final String AUTH_URL = "AUTH_URL";

	/**
	 * The base URL for the URLs of the site. 
	 **/
	public static final String SITE_BASE_URL = "SITE_BASE_URL";

	/**
	 * The display name of the site. 
	 **/
	public static final String SITE_DISPLAY_NAME = "SITE_DISPLAY_NAME";

	/**
	 * The class name of the JDBC driver class for the database. 
	 **/
	public static final String JDBC_DRIVER_CLASS = "JDBC_DRIVER_CLASS";

	/**
	 * The username for the database. 
	 **/
	public static final String JDBC_USERNAME = "JDBC_USERNAME";

	/**
	 * The password for the database. 
	 **/
	public static final String JDBC_PASSWORD = "JDBC_PASSWORD";

	/**
	 * The max pool size for the database. 
	 **/
	public static final String JDBC_MAX_POOL_SIZE = "JDBC_MAX_POOL_SIZE";

	/**
	 * Set the maximum connection request allowed in the wait queue, any requests beyond the max size will result in an failure. If the value is set to a negative number then the queue will be unbounded. 
	 **/
	public static final String JDBC_MAX_WAIT_QUEUE_SIZE = "JDBC_MAX_WAIT_QUEUE_SIZE";

	/**
	 * The max pool size for the database. 
	 **/
	public static final String JDBC_MIN_POOL_SIZE = "JDBC_MIN_POOL_SIZE";

	/**
	 * The max statements for the database. 
	 **/
	public static final String JDBC_MAX_STATEMENTS = "JDBC_MAX_STATEMENTS";

	/**
	 * The max statements per connection for the database. 
	 **/
	public static final String JDBC_MAX_STATEMENTS_PER_CONNECTION = "JDBC_MAX_STATEMENTS_PER_CONNECTION";

	/**
	 * The max idle time for the database. 
	 **/
	public static final String JDBC_MAX_IDLE_TIME = "JDBC_MAX_IDLE_TIME";

	/**
	 * The max idle time for the connection to the database. 
	 **/
	public static final String JDBC_CONNECT_TIMEOUT = "JDBC_CONNECT_TIMEOUT";

	/**
	 * The JDBC URL to the database. 
	 **/
	public static final String JDBC_HOST = "JDBC_HOST";

	/**
	 * The JDBC URL to the database. 
	 **/
	public static final String JDBC_PORT = "JDBC_PORT";

	/**
	 * The JDBC URL to the database. 
	 **/
	public static final String JDBC_DATABASE = "JDBC_DATABASE";

	/**
	 * The hostname to the SOLR search engine. 
	 **/
	public static final String SOLR_HOST_NAME = "SOLR_HOST_NAME";

	/**
	 * The port to the SOLR search engine. 
	 **/
	public static final String SOLR_PORT = "SOLR_PORT";

	/**
	 * The Solr collection. 
	 **/
	public static final String SOLR_COLLECTION = "SOLR_COLLECTION";

	/**
	 * The URL to the SOLR search engine for the computate project. 
	 **/
	public static final String SOLR_URL_COMPUTATE = "SOLR_URL_COMPUTATE";

	/**
	 * The Email account for the site. 
	 **/
	public static final String ACCOUNT_EMAIL = "ACCOUNT_EMAIL";

	/**
	 * The OpenID Connect role for an administrator. 
	 **/
	public static final String ROLE_ADMIN = "ROLE_ADMIN";

	/**
	 * The email address for the administrator of the site for the error reports. 
	 **/
	public static final String EMAIL_ADMIN = "EMAIL_ADMIN";

	/**
	 * The version of OpenAPI used with Vert.x which should probably be 3.0. 
	 **/
	public static final String OPEN_API_VERSION = "OPEN_API_VERSION";

	/**
	 * The description of your site API. 
	 **/
	public static final String API_DESCRIPTION = "API_DESCRIPTION";

	/**
	 * The title of your site API. 
	 **/
	public static final String API_TITLE = "API_TITLE";

	/**
	 * The terms of service of your site API. 
	 **/
	public static final String API_TERMS_SERVICE = "API_TERMS_SERVICE";

	/**
	 * The version of your site API. 
	 **/
	public static final String API_VERSION = "API_VERSION";

	/**
	 * The contact email of your site API. 
	 **/
	public static final String API_CONTACT_EMAIL = "API_CONTACT_EMAIL";

	/**
	 * The open source license name of your site API. 
	 **/
	public static final String API_LICENSE_NAME = "API_LICENSE_NAME";

	/**
	 * The open source license URL of your site API. 
	 **/
	public static final String API_LICENSE_URL = "API_LICENSE_URL";

	/**
	 * The host name of your site API. 
	 **/
	public static final String API_HOST_NAME = "API_HOST_NAME";

	/**
	 * The base path of your site API. 
	 **/
	public static final String API_BASE_PATH = "API_BASE_PATH";

	/**
	 * The base URL of your static files. 
	 **/
	public static final String STATIC_BASE_URL = "STATIC_BASE_URL";

	/**
	 * The path to the static files for the site. 
	 **/
	public static final String STATIC_PATH = "STATIC_PATH";

	/**
	 * The path to the handlebars template files for the site. 
	 **/
	public static final String TEMPLATE_PATH = "TEMPLATE_PATH";

	/**
	 * 
	 **/
	public static final String IMPORT_PATH = "IMPORT_PATH";

	/**
	 * The SMTP host name for sending email. 
	 **/
	public static final String EMAIL_HOST = "EMAIL_HOST";

	/**
	 * The SMTP port for sending email. 
	 **/
	public static final String EMAIL_PORT = "EMAIL_PORT";

	/**
	 * The SMTP username for sending email. 
	 **/
	public static final String EMAIL_USERNAME = "EMAIL_USERNAME";

	/**
	 * The SMTP password for sending email. 
	 **/
	public static final String EMAIL_PASSWORD = "EMAIL_PASSWORD";

	/**
	 * The from address for sending email. 
	 **/
	public static final String EMAIL_FROM = "EMAIL_FROM";

	/**
	 * Whether authentication is required for sending email. 
	 **/
	public static final String EMAIL_AUTH = "EMAIL_AUTH";

	/**
	 * Whether SSL is required for sending email. 
	 **/
	public static final String EMAIL_SSL = "EMAIL_SSL";

	/**
	 * The default timezone of the site. 
	 **/
	public static final String SITE_ZONE = "SITE_ZONE";

	/**
	 * 
	 **/
	public static final String TIMER_DB_SOLR_SYNC_IN_SECONDS = "TIMER_DB_SOLR_SYNC_IN_SECONDS";

	/**
	 * 
	 **/
	public static final String ENABLE_DB_SOLR_SYNC = "ENABLE_DB_SOLR_SYNC";

	/**
	 * 
	 **/
	public static final String ENABLE_REFRESH_DATA = "ENABLE_REFRESH_DATA";

	/**
	 * 
	 **/
	public static final String ENABLE_IMPORT_DATA = "ENABLE_IMPORT_DATA";

	/**
	 * 
	 **/
	public static final String WORKER_POOL_SIZE = "WORKER_POOL_SIZE";

	/**
	 * 
	 **/
	public static final String VERTX_WARNING_EXCEPTION_SECONDS = "VERTX_WARNING_EXCEPTION_SECONDS";

	/**
	 * 
	 **/
	public static final String ZOOKEEPER_HOST_NAME = "ZOOKEEPER_HOST_NAME";

	/**
	 * 
	 **/
	public static final String ZOOKEEPER_PORT = "ZOOKEEPER_PORT";

	/**
	 * 
	 **/
	public static final String ZOOKEEPER_HOSTS = "ZOOKEEPER_HOSTS";

	/**
	 * 
	 **/
	public static final String SOLR_WORKER_COMMIT_WITHIN_MILLIS = "SOLR_WORKER_COMMIT_WITHIN_MILLIS";

	/**
	 * 
	 **/
	public static final String VERTX_WORKER_SEND_TIMEOUT_MILLIS = "VERTX_WORKER_SEND_TIMEOUT_MILLIS";
}
