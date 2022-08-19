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

        DB::statement("drop function IF EXISTS regexp_count(varchar, varchar)");

        DB::statement("drop function IF EXISTS advSearch(int,varchar,varchar,varchar,varchar)");
        DB::statement("drop function IF EXISTS advSearch(varchar,varchar,varchar,varchar)");

        DB::statement("drop table IF EXISTS advSearch_dt");

        DB::statement("drop table IF EXISTS advsearch_dtnew");


        $advsearchDtSql = <<<'EOL'
        CREATE TABLE public.advsearch_dtnew
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
            team_name text,
            standalone_activity_user_id bigint,
            favored boolean
        )
        EOL;

        DB::statement($advsearchDtSql);


        $advsearchRegexpCountSql = <<<'EOL'
        CREATE OR REPLACE FUNCTION regexp_count(pexpression character varying, pphrase character varying)
        RETURNS numeric
        LANGUAGE plpgsql
        AS $function$
        DECLARE
        vRET 	integer := 0;
        vPHRASE_LENGTH integer := 0;
        vCOUNTER integer := 0;
        vEXPRESSION VARCHAR(4000);
        vTEMP VARCHAR(4000);
        BEGIN
        vEXPRESSION := pEXPRESSION;
        vPHRASE_LENGTH := LENGTH( pPHRASE );
        LOOP
            vCOUNTER := vCOUNTER + 1;
            vTEMP := SUBSTR( vEXPRESSION, 1, vPHRASE_LENGTH);
            IF (vTEMP = pPHRASE) THEN        
                vRET := vRET + 1;
            END IF;
            vEXPRESSION := SUBSTR( vEXPRESSION, 2, LENGTH( vEXPRESSION ) - 1);
        EXIT WHEN ( LENGTH( vEXPRESSION ) = 0 ) OR (vEXPRESSION IS NULL);
        END LOOP;
        RETURN vRET;
        end $function$
        EOL;

        DB::statement($advsearchRegexpCountSql);


        $advsearchSql = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advsearch(_uid integer, _text character varying, _subject character varying, _education character varying, _tag character varying)
        RETURNS SETOF advsearch_dtnew
        LANGUAGE plpgsql
        AS $function$
        declare 
        _searchText character varying := concat('%',concat(lower(_text),'%'));
        vCnt INTEGER := 0;
        vCntEducation INTEGER := 0;
        vCntTag INTEGER := 0;
        lCnt integer :=1;
        lCntEducation integer :=1;
        lCntTag integer :=1;
        query  character varying := '';
        cnd character varying := '  ';
        joinTable character varying := '  ';
        begin
            if _subject != '' then 
                cnd := cnd || ' and acts.subject_id in ' || _subject;
                joinTable := joinTable || ' left join activity_subject acts on a.id=acts.activity_id ';
            end if;

            if _education != '' then 
                cnd := cnd || ' and ael.education_level_id in ' || _education;
                joinTable := joinTable || ' left join activity_education_level ael on a.id=ael.activity_id ';
            end if;

            if _tag != '' then 
                cnd := cnd || ' and aat.author_tag_id in ' || _tag ;
                joinTable := joinTable || ' left join activity_author_tag aat on a.id=aat.activity_id ';
            end if;

            if _tag != '' or _education != '' or _subject != '' then
                
                query := format($s$ 
                select distinct sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from(
                
                select 1 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,
                t.name::text  as team_name, 0 as standalone_activity_user_id
                from projects p
                left join user_project up
                on p.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on p.organization_id=o.id
                left join teams t on p.team_id=t.id
                where p.id in (select project_id from playlists pl where pl.id in (select playlist_id from activities a %s where lower(a.title) like '%s' and a.id is not null %s) )
                
                
                union all
                
                select 1 as priority,'Playlist' as entity,pr.organization_id as org_id,p.id as entity_id,u.id as user_id, p.project_id as project_id,
                p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,pr.thumb_url,p.created_at,p.deleted_at,
                null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image, t.name::text  as team_name, 0 as standalone_activity_user_id
                from playlists p
                left join projects pr
                on p.project_id=pr.id
                left join user_project up
                on pr.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where p.id in  (select playlist_id from activities a %s where lower(a.title) like '%s' and a.id is not null %s)
                
                union all
                
                select 1 as priority,'Activity' as entity,pr.organization_id as org_id,a.id as entity_id,u.id as user_id, pr.id as project_id,
                a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0::bigint as subject_id,0::bigint as education_level_id ,0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,t.name::text  as team_name,a.user_id as standalone_activity_user_id
                from activities a  
                %s
                left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id
                left join playlists p on a.playlist_id=p.id
                left join projects pr on p.project_id=pr.id
                left join user_project up on pr.id=up.project_id
                left join users u on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where lower(a.title) like '%s'  %s
                )sq1
                left join
                (select distinct project_id as pid from user_favorite_project
                where user_id=%s)sq2
                on
                sq1.project_id=sq2.pid
                $s$,joinTable,_searchText,cnd,joinTable,_searchText,cnd,joinTable,_searchText,cnd,_uid);
                
                RETURN QUERY execute query;
            else 
                RETURN QUERY 
                select distinct sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from(
                select 1 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,
                t.name::text  as team_name, 0 as standalone_activity_user_id
                from projects p
                left join user_project up
                on p.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on p.organization_id=o.id
                left join teams t on p.team_id=t.id
                where lower(p.name) like _searchText
                
                
                union all
                
                
                select 2 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image, t.name::text  as team_name,0 as standalone_activity_user_id
                from projects p
                left join user_project up
                on p.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on p.organization_id=o.id
                left join teams t on p.team_id=t.id
                where lower(p.description) like _searchText
                and lower(p.name) not like _searchText
                
                
                union all
                
                select 1 as priority,'Playlist' as entity,pr.organization_id as org_id,p.id as entity_id,u.id as user_id, p.project_id as project_id,
                p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,pr.thumb_url,p.created_at,p.deleted_at,
                null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,t.name::text  as team_name, 0 as standalone_activity_user_id
                from playlists p
                left join projects pr
                on p.project_id=pr.id
                left join user_project up
                on pr.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where lower(p.title) like _searchText
                
                
                union all
                
                select 1 as priority,'Activity' as entity,pr.organization_id as org_id,a.id as entity_id,u.id as user_id, pr.id as project_id,
                a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0::bigint as subject_id,0::bigint as education_level_id ,0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,t.name::text  as team_name,a.user_id as standalone_activity_user_id
                from activities a  
                left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id
                left join playlists p on a.playlist_id=p.id
                left join projects pr on p.project_id=pr.id
                left join user_project up on pr.id=up.project_id
                left join users u on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where lower(a.title) like _searchText
                )sq1
                left join
                (select distinct project_id as pid from user_favorite_project
                where user_id=_uid)sq2
                on
                sq1.project_id=sq2.pid
                
                ;	
            end if;
            
                END;
        $function$
        EOL;

        DB::statement($advsearchSql);


        $advsearchSqlOverloading = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advsearch(_text character varying, _subject character varying, _education character varying, _tag character varying)
        RETURNS SETOF advsearch_dtnew
        LANGUAGE plpgsql
        AS $function$
        declare 
        _searchText character varying := concat('%',concat(lower(_text),'%'));
        vCnt INTEGER := 0;
        vCntEducation INTEGER := 0;
        vCntTag INTEGER := 0;
        lCnt integer :=1;
        lCntEducation integer :=1;
        lCntTag integer :=1;
        query  character varying := '';
        cnd character varying := '  ';
        joinTable character varying := '  ';
        begin
            if _subject != '' then 
                cnd := cnd || ' and acts.subject_id in ' || _subject;
                joinTable := joinTable || ' left join activity_subject acts on a.id=acts.activity_id ';
            end if;

            if _education != '' then 
                cnd := cnd || ' and ael.education_level_id in ' || _education;
                joinTable := joinTable || ' left join activity_education_level ael on a.id=ael.activity_id ';
            end if;

            if _tag != '' then 
                cnd := cnd || ' and aat.author_tag_id in ' || _tag ;
                joinTable := joinTable || ' left join activity_author_tag aat on a.id=aat.activity_id ';
            end if;

            if _tag != '' or _education != '' or _subject != '' then
                
                query := format($s$ 
                
                select 1 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,0::bigint as playlist_id,
                u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,
                t.name::text  as team_name, 0 as standalone_activity_user_id,null::boolean as favored
                from projects p
                left join user_project up
                on p.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on p.organization_id=o.id
                left join teams t on p.team_id=t.id
                where p.id in (select project_id from playlists pl where pl. id in (select playlist_id from activities a %s where lower(a.title) like '%s' and a.id is not null %s) )
                
                
                union all
                
                select 1 as priority,'Playlist' as entity,pr.organization_id as org_id,p.id as entity_id,u.id as user_id, p.project_id as project_id,
                p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,pr.thumb_url,p.created_at,p.deleted_at,
                null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,t.name::text as team_name, 0 as standalone_activity_user_id,null::boolean as favored
                from playlists p
                left join projects pr
                on p.project_id=pr.id
                left join user_project up
                on pr.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where p.id in  (select playlist_id from activities a %s where lower(a.title) like '%s' and a.id is not null %s)
                
                union all
                
                select 1 as priority,'Activity' as entity,pr.organization_id as org_id,a.id as entity_id,u.id as user_id, pr.id as project_id,
                a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0::bigint as subject_id,0::bigint as education_level_id ,0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image, t.name::text  as team_name,a.user_id as standalone_activity_user_id, null::boolean as favored
                from activities a  
                %s
                left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id
                left join playlists p on a.playlist_id=p.id
                left join projects pr on p.project_id=pr.id
                left join user_project up on pr.id=up.project_id
                left join users u on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where lower(a.title) like '%s'  %s
                $s$,joinTable,_searchText,cnd,joinTable,_searchText,cnd,joinTable,_searchText,cnd);
                
                RETURN QUERY execute query;
            else 
                RETURN QUERY 
                select 1 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,0::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0::bigint as subject_id,0::bigint as education_level_id, 0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,
                case when t.name::text is null then null::varchar else t.name::text end as team_name, 0 as standalone_activity_user_id,null::boolean as favored
                from projects p
                left join user_project up
                on p.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on p.organization_id=o.id
                left join teams t on p.team_id=t.id
                where lower(p.name) like _searchText
                
                
                union all
                
                
                select 2 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,0::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.deleted_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image, t.name::text  as team_name,0 as standalone_activity_user_id, null::boolean as favored
                from projects p
                left join user_project up
                on p.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on p.organization_id=o.id
                left join teams t on p.team_id=t.id
                where lower(p.description) like _searchText
                and lower(p.name) not like _searchText
                
                
                union all
                
                select 1 as priority,'Playlist' as entity,pr.organization_id as org_id,p.id as entity_id,u.id as user_id, p.project_id as project_id,
                p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,pr.thumb_url,p.created_at,p.deleted_at,
                null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,0 as subject_id,0 as education_level_id, 0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,t.name::text  as team_name, 0 as standalone_activity_user_id,null::boolean as favored
                from playlists p
                left join projects pr
                on p.project_id=pr.id
                left join user_project up
                on pr.id=up.project_id
                left join users u
                on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where lower(p.title) like _searchText
                
                
                union all
                
                select 1 as priority,'Activity' as entity,pr.organization_id as org_id,a.id as entity_id,u.id as user_id, pr.id as project_id,
                a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0 as subject_id,0 as education_level_id ,0 as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,t.name::text  as team_name,a.user_id as standalone_activity_user_id, null::boolean as favored
                from activities a  
                left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id
                left join playlists p on a.playlist_id=p.id
                left join projects pr on p.project_id=pr.id
                left join user_project up on pr.id=up.project_id
                left join users u on up.user_id=u.id
                left join organizations o on pr.organization_id=o.id
                left join teams t on pr.team_id=t.id
                where lower(a.title) like _searchText
                ;	
            end if;
            
                END;
        $function$
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
        DB::statement("drop function IF EXISTS regexp_count(varchar, varchar)");

        DB::statement("drop function IF EXISTS advSearch(int,varchar,varchar,varchar,varchar)");
        DB::statement("drop function IF EXISTS advSearch(varchar,varchar,varchar,varchar)");

        DB::statement("drop table IF EXISTS advSearch_dt");

        DB::statement("drop table IF EXISTS advsearch_dtnew");
    }
}