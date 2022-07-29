<?php

use Illuminate\Database\Migrations\Migration;

class CreatePerformanceOptimizationIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("create index IF NOT EXISTS activities_description ON public.activities (description)");
        DB::statement("create index IF NOT EXISTS activities_h5p_content_id ON public.activities (h5p_content_id)");
        DB::statement("create index IF NOT EXISTS activities_playlist_id ON public.activities (playlist_id)");
        DB::statement("create index IF NOT EXISTS h5p_contents_library_id on public.h5p_contents (library_id)");
        DB::statement("create index IF NOT EXISTS playlists_project_id on public.playlists (project_id)");
        DB::statement("create index IF NOT EXISTS user_project_project_id on public.user_project(project_id)");
        DB::statement("create index IF NOT EXISTS user_project_USER_id on public.user_project(user_id)");
        DB::statement("create index IF NOT EXISTS projects_organization_id on public.projects(organization_id)");
        DB::statement("create index IF NOT EXISTS projects_team_id on public.projects(team_id)");
        DB::statement("create index IF NOT EXISTS independent_activity_author_tag_independent_activity_id on public.independent_activity_author_tag(independent_activity_id)");
        DB::statement("create index IF NOT EXISTS independent_activity_education_level_independent_activity_id on public.independent_activity_education_level(independent_activity_id)");
        DB::statement("create index IF NOT EXISTS independent_activity_subject_independent_activity_id on public.independent_activity_subject(independent_activity_id)");
        DB::statement("create index IF NOT EXISTS independent_activities_description ON public.independent_activities (description)");
        DB::statement("create index IF NOT EXISTS independent_activities_h5p_content_id ON public.independent_activities (h5p_content_id)");
        DB::statement("create index IF NOT EXISTS independent_activities_user_id ON public.independent_activities (user_id)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("drop index IF EXISTS activities_description");
        DB::statement("drop index IF EXISTS activities_h5p_content_id");
        DB::statement("drop index IF EXISTS activities_playlist_id");
        DB::statement("drop index IF EXISTS h5p_contents_library_id");
        DB::statement("drop index IF EXISTS playlists_project_id");
        DB::statement("drop index IF EXISTS user_project_project_id");
        DB::statement("drop index IF EXISTS user_project_USER_id");
        DB::statement("drop index IF EXISTS projects_organization_id");
        DB::statement("drop index IF EXISTS projects_team_id");
        DB::statement("drop index IF EXISTS independent_activity_author_tag_independent_activity_id");
        DB::statement("drop index IF EXISTS independent_activity_education_level_independent_activity_id");
        DB::statement("drop index IF EXISTS independent_activity_subject_independent_activity_id");
        DB::statement("drop index IF EXISTS independent_activities_description");
        DB::statement("drop index IF EXISTS independent_activities_h5p_content_id");
        DB::statement("drop index IF EXISTS independent_activities_user_id");
    }
}
