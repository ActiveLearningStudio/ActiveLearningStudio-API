-- hackathonTitle: create a db-create.sql script to create the database tables required. 
-- hackathonColumn: Develop Base Resources
-- hackathonLabels: PostgreSQL,SQL
-- TODO: Define the SQL to create all the tables. 

CREATE TABLE SiteUser(
	pk bigserial primary key
	, inheritPk text
	, created timestamp with time zone
	, archived boolean
	, deleted boolean
	, sessionId text
	, userKey bigint
	, userId text
	, userName text
	, userEmail text
	, userFirstName text
	, userLastName text
	, userFullName text
	);
