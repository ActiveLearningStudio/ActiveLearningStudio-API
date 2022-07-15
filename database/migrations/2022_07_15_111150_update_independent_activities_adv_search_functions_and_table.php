<?php

use Illuminate\Database\Migrations\Migration;

class UpdateIndependentActivitiesAdvSearchFunctionsAndTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("create index IF NOT EXISTS independent_activities_title on independent_activities(title)");


        DB::statement("drop function IF EXISTS advindependentactivitysearch(int,varchar)");
        DB::statement("drop function IF EXISTS advindependentactivitysearch(varchar)");

        DB::statement("drop table IF EXISTS advsearchIndependentActivity_dt");


        $independentActivityAdvsearchDtSql = <<<'EOL'
        CREATE TABLE public.advsearchIndependentActivity_dt
        (
            priority integer,
            entity text COLLATE pg_catalog."default",
            org_id bigint,
            entity_id bigint,
            user_id bigint,
            project_id bigint,
            playlist_id bigint,
            first_name character varying(255) COLLATE pg_catalog."default",
            last_name character varying(255) COLLATE pg_catalog."default",
            email character varying(255) COLLATE pg_catalog."default",
            name character varying(255) COLLATE pg_catalog."default",
            description text COLLATE pg_catalog."default",
            thumb_url character varying COLLATE pg_catalog."default",
            created_at timestamp(0) without time zone,
            deleted_at timestamp(0) without time zone,
            is_shared boolean,
            is_public boolean,
            indexing smallint,
            organization_visibility_type_id bigint,
            h5plib text COLLATE pg_catalog."default",
            subject_id bigint,
            education_level_id bigint,
            author_tag_id bigint,
            organization_name character varying(255) COLLATE pg_catalog."default",
            org_description character varying(255) COLLATE pg_catalog."default",
            org_image character varying(255) COLLATE pg_catalog."default",
            team_name character varying(255) COLLATE pg_catalog."default",
            standalone_activity_user_id bigint,
            favored boolean,
            activity_title character varying(255) COLLATE pg_catalog."default"
        )
        EOL;

        DB::statement($independentActivityAdvsearchDtSql);


        $independentActivityAdvSearchSql = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advindependentactivitysearch(
            _text character varying)
            RETURNS SETOF advsearchIndependentActivity_dt 
            LANGUAGE 'sql'
            COST 100
            VOLATILE PARALLEL UNSAFE
            ROWS 1000
        
        AS $BODY$
        select 1 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , acts.subject_id,ael.education_level_id ,aat.author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null as team_name,0::bigint as standalone_activity_user_id, null::boolean as favored,ai.title as activity_title
                from independent_activities a 
                 left join independent_activity_subject acts on a.id=acts.independent_activity_id
                 left join independent_activity_education_level ael on a.id=ael.independent_activity_id
                 left join independent_activity_author_tag aat on a.id=aat.independent_activity_id
                 left join h5p_contents hc on a.h5p_content_id=hc.id
                 left join h5p_libraries hl on hc.library_id=hl.id
                 left join users u on a.user_id=u.id
                 left join organizations o on a.organization_id=o.id
                 left join activity_items ai on ai."h5pLib"=concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) and a.organization_id=ai.organization_id
                 where lower(a.title) like concat(concat('%',lower(_text)),'%')
                 
                 union all
                 
                 select 2 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , acts.subject_id,ael.education_level_id ,aat.author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null as team_name,0::bigint as standalone_activity_user_id, null::boolean as favored,ai.title as activity_title
                from independent_activities a 
                 left join independent_activity_subject acts on a.id=acts.independent_activity_id
                 left join independent_activity_education_level ael on a.id=ael.independent_activity_id
                 left join independent_activity_author_tag aat on a.id=aat.independent_activity_id
                 left join h5p_contents hc on a.h5p_content_id=hc.id
                 left join h5p_libraries hl on hc.library_id=hl.id
                 left join users u on a.user_id=u.id
                 left join organizations o on a.organization_id=o.id
                 left join activity_items ai on ai."h5pLib"=concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) and a.organization_id=ai.organization_id
                 where lower(a.title) not like concat(concat('%',lower(_text)),'%') and
                 lower(a.description) like concat(concat('%',lower(_text)),'%')
        $BODY$
        EOL;

        DB::statement($independentActivityAdvSearchSql);


        $independentActivityAdvsearchSqlOverloading = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advindependentactivitysearch(
            _uid integer,
            _text character varying)
            RETURNS SETOF advsearchIndependentActivity_dt 
            LANGUAGE 'sql'
            COST 100
            VOLATILE PARALLEL UNSAFE
            ROWS 1000
        
        AS $BODY$
                
                select 1 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , acts.subject_id,ael.education_level_id ,aat.author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null as team_name,0::bigint as standalone_activity_user_id, FALSE  as favored,ai.title as activity_title
                from independent_activities a 
                 left join independent_activity_subject acts on a.id=acts.independent_activity_id
                 left join independent_activity_education_level ael on a.id=ael.independent_activity_id
                 left join independent_activity_author_tag aat on a.id=aat.independent_activity_id
                 left join h5p_contents hc on a.h5p_content_id=hc.id
                 left join h5p_libraries hl on hc.library_id=hl.id
                 left join users u on a.user_id=u.id
                 left join organizations o on a.organization_id=o.id
                 left join activity_items ai on ai."h5pLib"=concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) and a.organization_id=ai.organization_id
                 where lower(a.title) like concat(concat('%',lower(_text)),'%')
                
                union all
                    
                select 2 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , acts.subject_id,ael.education_level_id ,aat.author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null as team_name,0::bigint as standalone_activity_user_id, FALSE  as favored,ai.title as activity_title
                from independent_activities a 
                 left join independent_activity_subject acts on a.id=acts.independent_activity_id
                 left join independent_activity_education_level ael on a.id=ael.independent_activity_id
                 left join independent_activity_author_tag aat on a.id=aat.independent_activity_id
                 left join h5p_contents hc on a.h5p_content_id=hc.id
                 left join h5p_libraries hl on hc.library_id=hl.id
                 left join users u on a.user_id=u.id
                 left join organizations o on a.organization_id=o.id
                 left join activity_items ai on ai."h5pLib"=concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) and a.organization_id=ai.organization_id
                 where lower(a.title) not like concat(concat('%',lower(_text)),'%') and
                 lower(a.description) like concat(concat('%',lower(_text)),'%')
                
                 
        $BODY$
        EOL;

        DB::statement($independentActivityAdvsearchSqlOverloading);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop function IF EXISTS advindependentactivitysearch(int,varchar)");
        DB::statement("drop function IF EXISTS advindependentactivitysearch(varchar)");

        DB::statement("drop table IF EXISTS advsearchIndependentActivity_dt");
    }
}
