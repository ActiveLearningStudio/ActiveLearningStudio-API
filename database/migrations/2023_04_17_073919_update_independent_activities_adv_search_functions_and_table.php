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
        DB::statement("create index IF NOT EXISTS activities_title on activities(title)");


        DB::statement("drop function IF EXISTS advindependentactivitysearch(int,varchar)");
        DB::statement("drop function IF EXISTS advindependentactivitysearch(varchar)");

        DB::statement("drop function IF EXISTS regexp_count(varchar, varchar)");

        DB::statement("drop function IF EXISTS advindependentactivitysearch(int,varchar,varchar,varchar,varchar)");
        DB::statement("drop function IF EXISTS advindependentactivitysearch(varchar,varchar,varchar,varchar)");

        DB::statement("drop function IF EXISTS advindependentactivitysearch(int,varchar,varchar,varchar,varchar,varchar)");
        DB::statement("drop function IF EXISTS advindependentactivitysearch(varchar,varchar,varchar,varchar,varchar)");

        DB::statement("drop function IF EXISTS advindependentactivitysearch(int,varchar,varchar,varchar,varchar,varchar,boolean)");
        DB::statement("drop function IF EXISTS advindependentactivitysearch(varchar,varchar,varchar,varchar,varchar,boolean)");

        DB::statement("drop table IF EXISTS advsearchIndependentActivity_dt");

        DB::statement("drop table IF EXISTS advsearchIndependentActivity_dtnew");


        $independentActivityAdvsearchDtSql = <<<'EOL'
        CREATE TABLE public.advsearchIndependentActivity_dtnew
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
            favored boolean,
            activity_title character varying(255) COLLATE pg_catalog."default"
        )
        EOL;

        DB::statement($independentActivityAdvsearchDtSql);


        $independentActivityAdvSearchRegexpCountSql = <<<'EOL'
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

        DB::statement($independentActivityAdvSearchRegexpCountSql);


        $independentActivityAdvSearchSql = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advindependentactivitysearch(_uid integer, _text character varying, _subject character varying, _education character varying, _tag character varying, _h5p character varying, _matchactivitytype boolean)
        RETURNS SETOF advsearchindependentactivity_dtnew
        LANGUAGE plpgsql
        AS $function$
        declare 
        _searchText character varying := concat('%',concat(lower(replace(_text,'''','')),'%'));
        vCnt INTEGER := 0;
        hCnt INTEGER := 0;
        vCntEducation INTEGER := 0;
        vCntTag INTEGER := 0;
        lCnt integer :=1;
        hlCnt integer :=1;
        lCntEducation integer :=1;
        lCntTag integer :=1;
        query  character varying := '';
        cnd character varying := '  ';
        h5p character varying := '  ';
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
            
            if _h5p != '' then 
                select regexp_count(_h5p, ',') into hCnt ;
                hCnt := hCnt;

                if _matchActivityType then
                    h5p := ' and concat(concat(concat(hl.name,'' ''),major_version),concat(''.'',minor_version)) in  (''';
                    if hCnt=0 then 
                        h5p:=  h5p || split_part(_h5p,',',1) || ''') ' ;
                    else
                    for hlCnt in 1..hCnt loop
                        h5p:=  h5p || split_part(_h5p,',',hlCnt) || ''' , ''' ; 
                    end loop;
                        h5p:=  h5p || split_part(_h5p,',',hCnt+1) || ''') ' ;
                    end if;
                    cnd := cnd || h5p;
                else 
                    h5p := ' and hl.name in  (''';
                    if hCnt=0 then 
                        h5p:=  h5p || split_part(split_part(_h5p,',',1),' ',1) || ''') ' ;
                    else
                    for hlCnt in 1..hCnt loop
                        h5p:=  h5p || split_part(split_part(_h5p,',',hlCnt),' ',1) || ''' , ''' ; 
                    end loop;
                        h5p:=  h5p || split_part(split_part(_h5p,',',hCnt+1),' ',1) || ''') ' ;
                    end if;
                    cnd := cnd || h5p;
                end if;
                
            end if;

        query := format($s$ select distinct 1 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0::bigint as subject_id,0::bigint as education_level_id ,0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null::text as team_name,0::bigint as standalone_activity_user_id, null::boolean as favored,hl.title as activity_title
                from activities a 
                    %s     left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id %s
                left join users u on a.user_id=u.id
                left join organizations o on a.organization_id=o.id
                where a.activity_type='INDEPENDENT' and hl.title is not null and  a.user_id = '%s' and lower(replace(a.title,'''','')) like '%s'  %s  
                union all
                select distinct 2 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0::bigint as subject_id,0::bigint as education_level_id ,0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null::text as team_name,0::bigint as standalone_activity_user_id, null::boolean as favored,hl.title as activity_title
                from activities a 
                    %s     left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id %s
                left join users u on a.user_id=u.id
                left join organizations o on a.organization_id=o.id
                where a.activity_type='INDEPENDENT' and hl.title is not null and  a.user_id = '%s' and lower(replace(a.title,'''','')) not like '%s' and lower(replace(a.description,'''','')) like '%s' %s  
                $s$,joinTable,h5p,_uid,_searchText,cnd,joinTable,h5p,_uid,_searchText,_searchText,cnd);
                RETURN QUERY execute query;	
                END;
        $function$
        EOL;

        DB::statement($independentActivityAdvSearchSql);


        $independentActivityAdvsearchSqlOverloading = <<<'EOL'
        CREATE OR REPLACE FUNCTION public.advindependentactivitysearch(_text character varying, _subject character varying, _education character varying, _tag character varying, _h5p character varying, _matchactivitytype boolean)
        RETURNS SETOF advsearchindependentactivity_dtnew
        LANGUAGE plpgsql
        AS $function$
        declare 
        _searchText character varying := concat('%',concat(lower(replace(_text,'''','')),'%'));
        vCnt INTEGER := 0;
        hCnt INTEGER := 0;
        vCntEducation INTEGER := 0;
        vCntTag INTEGER := 0;
        lCnt integer :=1;
        hlCnt integer :=1;
        lCntEducation integer :=1;
        lCntTag integer :=1;
        query  character varying := '';
        cnd character varying := '  ';
        h5p character varying := '  ';
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
            
                if _h5p != '' then 
                select regexp_count(_h5p, ',') into hCnt ;
                hCnt := hCnt;
                
                if _matchActivityType then
                    h5p := ' and concat(concat(concat(hl.name,'' ''),major_version),concat(''.'',minor_version)) in  (''';
                    if hCnt=0 then 
                        h5p:=  h5p || split_part(_h5p,',',1) || ''') ' ;
                    else
                    for hlCnt in 1..hCnt loop
                        h5p:=  h5p || split_part(_h5p,',',hlCnt) || ''' , ''' ; 
                    end loop;
                        h5p:=  h5p || split_part(_h5p,',',hCnt+1) || ''') ' ;
                    end if;
                    cnd := cnd || h5p;
                else 
                    h5p := ' and hl.name in  (''';
                    if hCnt=0 then 
                        h5p:=  h5p || split_part(split_part(_h5p,',',1),' ',1) || ''') ' ;
                    else
                    for hlCnt in 1..hCnt loop
                        h5p:=  h5p || split_part(split_part(_h5p,',',hlCnt),' ',1) || ''' , ''' ; 
                    end loop;
                        h5p:=  h5p || split_part(split_part(_h5p,',',hCnt+1),' ',1) || ''') ' ;
                    end if;
                    cnd := cnd || h5p;
                end if;
                
            end if;

        query := format($s$ select distinct 1 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0::bigint as subject_id,0::bigint as education_level_id ,0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null::text as team_name,0::bigint as standalone_activity_user_id, null::boolean as favored,hl.title as activity_title
                from activities a 
                    %s     left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id %s
                left join users u on a.user_id=u.id
                left join organizations o on a.organization_id=o.id
                where a.activity_type='INDEPENDENT' and hl.title is not null and  lower(replace(a.title,'''','')) like '%s'  %s  
                union all
                select distinct 2 as priority,'Independent Activity' as entity,a.organization_id as org_id,a.id as entity_id,a.user_id as user_id, null::bigint as project_id,
                null::bigint as playlist_id,u.first_name,u.last_name,u.email,a.title as name,a.description as description,a.thumb_url,a.created_at,a.deleted_at,
                a.shared as is_shared,a.is_public,a.indexing,a.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , 0::bigint as subject_id,0::bigint as education_level_id ,0::bigint as author_tag_id,o.name as organization_name,o.description as org_description,o.image as org_image,null::text as team_name,0::bigint as standalone_activity_user_id, null::boolean as favored,hl.title as activity_title
                from activities a 
                    %s     left join h5p_contents hc on a.h5p_content_id=hc.id
                left join h5p_libraries hl on hc.library_id=hl.id %s
                left join users u on a.user_id=u.id
                left join organizations o on a.organization_id=o.id
                where a.activity_type='INDEPENDENT' and hl.title is not null and  lower(replace(a.title,'''','')) not like '%s' and lower(replace(a.description,'''','')) like '%s' %s  
                $s$,joinTable,h5p,_searchText,cnd,joinTable,h5p,_searchText,_searchText,cnd);
                RETURN QUERY execute query;	
                END;
        $function$
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
        DB::statement("drop function IF EXISTS regexp_count(varchar, varchar)");

        DB::statement("drop function IF EXISTS advindependentactivitysearch(int,varchar,varchar,varchar,varchar)");
        DB::statement("drop function IF EXISTS advindependentactivitysearch(varchar,varchar,varchar,varchar)");

        DB::statement("drop table IF EXISTS advsearchIndependentActivity_dt");

        DB::statement("drop table IF EXISTS advsearchIndependentActivity_dtnew");
    }
}