<?php

use Illuminate\Database\Seeder;

class AdvSearchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("create index IF NOT EXISTS project_name on projects(name)");

        DB::statement("create index IF NOT EXISTS playlist_title on playlists(title)");

        DB::statement("create index IF NOT EXISTS activities_title on activities(title)");


        DB::statement("drop function IF EXISTS advSearch");

        DB::statement("drop table IF EXISTS advSearch_dt");


        DB::statement("create table advSearch_dt as 
        select sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from
        (
        select 1 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
        p.name, p.description,p.thumb_url,p.created_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
        , null as h5pLib,null as subject_id,null as education_level_id
         from projects p
         left join user_project up
         on p.id=up.project_id
         left join users u
         on up.user_id=u.id
         )sq1
         left join
        (select distinct project_id as pid from user_favorite_project
        where user_id=1)sq2
        on
        sq1.project_id=sq2.pid
        union all
        select sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from
        (
        select 1 as priority,'Playlist' as entity,pr.organization_id as org_id,p.id as entity_id,u.id as user_id, p.project_id as project_id,
        p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,null as thumb_url,p.created_at,
        null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,null as subject_id,null as education_level_id
        from playlists p
        left join projects pr
        on p.project_id=pr.id
         left join user_project up
         on pr.id=up.project_id
         left join users u
         on up.user_id=u.id
         )sq1
         left join
        (select distinct project_id as pid from user_favorite_project
        where user_id=1)sq2
        on
        sq1.project_id=sq2.pid
        union all
        select sq1.*, case when sq2.pid is not null then TRUE else FALSE end as favored from
        (
        select 1 as priority,'Activity' as entity,pr.organization_id as org_id,a.id as entity_id,u.id as user_id, pr.id as project_id,
        a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,
        a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
        , a. subject_id,a.education_level_id 
        from activities a 
         left join h5p_contents hc on a.h5p_content_id=hc.id
         left join h5p_libraries hl on hc.library_id=hl.id
         left join playlists p on a.playlist_id=p.id
         left join projects pr on p.project_id=pr.id
         left join user_project up on pr.id=up.project_id
         left join users u on up.user_id=u.id
         )sq1
         left join
        (select distinct project_id as pid from user_favorite_project
        where user_id=1)sq2
        on
        sq1.project_id=sq2.pid");


        DB::statement("truncate table advSearch_dt");


        $advsearchSql = <<<'EOL'
        CREATE OR REPLACE FUNCTION advsearch(
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
                p.name, p.description,p.thumb_url,p.created_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,null as subject_id,null as education_level_id
                 from projects p
                 left join user_project up
                 on p.id=up.project_id
                 left join users u
                 on up.user_id=u.id
                 where lower(p.name) like concat(concat('%',lower(_text)),'%')
                union all
                select 2 as priority,'Project' as entity,p.organization_id as org_id,p.id as entity_id,u.id as user_id, p.id as project_id,null::bigint as playlist_id,u.first_name,u.last_name,u.email,
                p.name, p.description,p.thumb_url,p.created_at,p.shared as is_shared,p.is_public,p.indexing,p.organization_visibility_type_id
                , null as h5pLib,null as subject_id,null as education_level_id
                 from projects p
                 left join user_project up
                 on p.id=up.project_id
                 left join users u
                 on up.user_id=u.id
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
                p.id as playlist_id,u.first_name,u.last_name,u.email,p.title as name,null as description,null as thumb_url,p.created_at,
                null::boolean as is_shared,p.is_public,pr.indexing,pr.organization_visibility_type_id, null as h5pLib,null as subject_id,null as education_level_id
                from playlists p
                left join projects pr
                on p.project_id=pr.id
                 left join user_project up
                 on pr.id=up.project_id
                 left join users u
                 on up.user_id=u.id
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
                a.playlist_id as playlist_id,u.first_name,u.last_name,u.email,a.title as name,null as description,a.thumb_url,a.created_at,
                a.shared as is_shared,a.is_public,pr.indexing,pr.organization_visibility_type_id, concat(concat(concat(hl.name,' '),major_version),concat('.',minor_version)) as h5pLib
                , a. subject_id,a.education_level_id
                from activities a
                 left join h5p_contents hc on a.h5p_content_id=hc.id
                 left join h5p_libraries hl on hc.library_id=hl.id
                 left join playlists p on a.playlist_id=p.id
                 left join projects pr on p.project_id=pr.id
                 left join user_project up on pr.id=up.project_id
                 left join users u on up.user_id=u.id
                 where lower(a.title) like concat(concat('%',lower(_text)),'%')
                 )sq1
                 left join
                (select distinct project_id as pid from user_favorite_project
                where user_id=_uid)sq2
                on
                sq1.project_id=sq2.pid
        $BODY$
        EOL;


        DB::statement($advsearchSql);
    }
}
