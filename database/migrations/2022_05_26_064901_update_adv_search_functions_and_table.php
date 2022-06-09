<?php

use Illuminate\Database\Migrations\Migration;

class UpdateAdvSearchFunctionsAndTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("create index IF NOT EXISTS project_name on projects(name)");

        DB::statement("create index IF NOT EXISTS playlist_title on playlists(title)");

        DB::statement("create index IF NOT EXISTS activities_title on activities(title)");


        DB::statement("drop function IF EXISTS advSearch(int,varchar)");
        DB::statement("drop function IF EXISTS advSearch(varchar)");

        DB::statement("drop table IF EXISTS advSearch_dt");


        $advsearchDtSql = <<<'EOL'
        CREATE TABLE public.advsearch_dt
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
            favored boolean
        )
        EOL;

        DB::statement($advsearchDtSql);


        $advsearchSql = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advsearch(
            _text character varying)
            RETURNS SETOF advsearch_dt 
            LANGUAGE 'sql'
            COST 100
            VOLATILE PARALLEL UNSAFE
            ROWS 1000
        
        AS $BODY$
        select 1 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,
                case when t.name is null then null::varchar else t.name end as team_name, 0 as standalone_activity_user_id,null::boolean as favored
                 from projects p
                 left join user_project up
                 on p.id=up.project_id
                 left join users u
                 on up.user_id=u.id
                 left join organizations o on p.organization_id=o.id
                 left join teams t on p.team_id=t.id
                 where lower(p.name) like concat(concat('%',lower(_text)),'%')
                 
                
                union all
                
                
                select 2 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,case when t.name is null then null::varchar else t.name end as team_name,0 as standalone_activity_user_id, null::boolean as favored
                 from projects p
                 left join user_project up
                 on p.id=up.project_id
                 left join users u
                 on up.user_id=u.id
                 left join organizations o on p.organization_id=o.id
                 left join teams t on p.team_id=t.id
                 where lower(p.description) like concat(concat('%',lower(_text)),'%')
                 and lower(p.name) not like concat(concat('%',lower(_text)),'%')
                
                
                union all
                
                select 1 as priority,'Playlist' as entity,pr.organization_id as org_id,p.id as entity_id,u.id as user_id, p.project_id as project_id,
                p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,pr.thumb_url,p.created_at,p.deleted_at,
                null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,case when t.name is null then null::varchar else t.name end as team_name, 0 as standalone_activity_user_id,null::boolean as favored
                from playlists p
                left join projects pr
                on p.project_id=pr.id
                 left join user_project up
                 on pr.id=up.project_id
                 left join users u
                 on up.user_id=u.id
                 left join organizations o on pr.organization_id=o.id
                 left join teams t on pr.team_id=t.id
                 where lower(p.title) like concat(concat('%',lower(_text)),'%')
                 
                
                union all
                
                select 1 as priority,'Activity' as entity,pr.organization_id as org_id,a.id as entity_id,u.id as user_id, pr.id as project_id,
                a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , acts.subject_id,ael.education_level_id ,aat.author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,case when t.name is null then null::varchar else t.name end as team_name,a.user_id as standalone_activity_user_id, null::boolean as favored
                from activities a 
                 left join activity_subject acts on a.id=acts.activity_id
                 left join activity_education_level ael on a.id=ael.activity_id
                 left join activity_author_tag aat on a.id=aat.activity_id
                 left join h5p_contents hc on a.h5p_content_id=hc.id
                 left join h5p_libraries hl on hc.library_id=hl.id
                 left join playlists p on a.playlist_id=p.id
                 left join projects pr on p.project_id=pr.id
                 left join user_project up on pr.id=up.project_id
                 left join users u on up.user_id=u.id
                 left join organizations o on pr.organization_id=o.id
                 left join teams t on pr.team_id=t.id
                 where lower(a.title) like concat(concat('%',lower(_text)),'%')
        $BODY$
        EOL;

        DB::statement($advsearchSql);


        $advsearchSqlOverloading = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advsearch(
            _uid integer,
            _text character varying)
            RETURNS SETOF advsearch_dt 
            LANGUAGE 'sql'
            COST 100
            VOLATILE PARALLEL UNSAFE
            ROWS 1000
        
        AS $BODY$
        select distinct sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from
                (
                select 1 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id,0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,case when t.name is null then null::varchar else t.name end as team_name,0 as standalone_activity_user_id
                 from projects p
                 left join user_project up
                 on p.id=up.project_id
                 left join users u
                 on up.user_id=u.id
                 left join organizations o on p.organization_id=o.id
                 left join teams t on p.team_id=t.id
                 where lower(p.name) like concat(concat('%',lower(_text)),'%')
                
                union all
                
                
                select 2 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id,0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,case when t.name is null then null::varchar else t.name end as team_name, 0 as standalone_activity_user_id
                 from projects p
                 left join user_project up
                 on p.id=up.project_id
                 left join users u
                 on up.user_id=u.id
                 left join organizations o on p.organization_id=o.id
                 left join teams t on p.team_id=t.id
                 where lower(p.description) like concat(concat('%',lower(_text)),'%')
                 and lower(p.name) not like concat(concat('%',lower(_text)),'%')
                 )sq1
                 left join
                (select distinct project_id as pid from user_favorite_project
                where user_id=_uid)sq2
                on
                sq1.project_id=sq2.pid
                
                union all
                select sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from
                (
                select 1 as priority,'Playlist' as entity,pr.organization_id as org_id,p.id as entity_id,u.id as user_id, p.project_id as project_id,
                p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,pr.thumb_url,p.created_at,p.deleted_at,
                null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,0 as subject_id,0 as education_level_id,0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,case when t.name is null then null::varchar else t.name end as team_name,0 as standalone_activity_user_id
                from playlists p
                left join projects pr
                on p.project_id=pr.id
                 left join user_project up
                 on pr.id=up.project_id
                 left join users u
                 on up.user_id=u.id
                 left join teams t on pr.team_id=t.id
                 left join organizations o on pr.organization_id=o.id
                 where lower(p.title) like concat(concat('%',lower(_text)),'%')
                 )sq1
                 left join
                (select distinct project_id as pid from user_favorite_project
                where user_id=_uid)sq2
                on
                sq1.project_id=sq2.pid
                union all
                select sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from
                (
                select 1 as priority,'Activity' as entity,pr.organization_id as org_id,a.id as entity_id,u.id as user_id, pr.id as project_id,
                a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , acts.subject_id,ael.education_level_id ,aat.author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,case when t.name is null then null::varchar else t.name end as team_name,a.user_id as standalone_activity_user_id
                from activities a 
                 left join activity_subject acts on a.id=acts.activity_id
                 left join activity_education_level ael on a.id=ael.activity_id
                 left join activity_author_tag aat on a.id=aat.activity_id
                 left join h5p_contents hc on a.h5p_content_id=hc.id
                 left join h5p_libraries hl on hc.library_id=hl.id
                 left join playlists p on a.playlist_id=p.id
                 left join projects pr on p.project_id=pr.id
                 left join user_project up on pr.id=up.project_id
                 left join users u on up.user_id=u.id
                 left join organizations o on pr.organization_id=o.id
                 left join teams t on pr.team_id=t.id
                 where lower(a.title) like concat(concat('%',lower(_text)),'%')
                 )sq1
                 left join
                (select distinct project_id as pid from user_favorite_project
                where user_id=_uid)sq2
                on
                sq1.project_id=sq2.pid
        $BODY$
        EOL;

        DB::statement($advsearchSqlOverloading);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop function IF EXISTS advSearch(int,varchar)");
        DB::statement("drop function IF EXISTS advSearch(varchar)");

        DB::statement("drop table IF EXISTS advSearch_dt");
    }
}