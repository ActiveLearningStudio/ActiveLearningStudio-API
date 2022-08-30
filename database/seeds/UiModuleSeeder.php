<?php

use Illuminate\Database\Seeder;

class UiModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            // Organiziations
            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Organiziations'],
                    ['created_at' => 'NOW()']
                );

            $organiziationsUiModule = DB::table('ui_modules')
                                            ->where('title', 'Organiziations')
                                            ->first();

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Organiziation', 'parent_id' => $organiziationsUiModule->id],
                    ['created_at' => 'NOW()']
                );

            // Projects
            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Projects'],
                    ['created_at' => 'NOW()']
                );

            $projectsUiModule = DB::table('ui_modules')
                                    ->where('title', 'Projects')
                                    ->first();

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'All Projects', 'parent_id' => $projectsUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Import/Export Projects', 'parent_id' => $projectsUiModule->id],
                    ['created_at' => 'NOW()']
                );

            // Ref. Tables
            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Ref. Tables'],
                    ['created_at' => 'NOW()']
                );

            $refTablesUiModule = DB::table('ui_modules')
                                    ->where('title', 'Ref. Tables')
                                    ->first();

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Activity Types', 'parent_id' => $refTablesUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Activity Items', 'parent_id' => $refTablesUiModule->id],
                    ['created_at' => 'NOW()']
                );

            // Integrations
            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Integrations'],
                    ['created_at' => 'NOW()']
                );

            $integrationsUiModule = DB::table('ui_modules')
                                        ->where('title', 'Integrations')
                                        ->first();

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'LMS Settings', 'parent_id' => $integrationsUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'LTI Tools', 'parent_id' => $integrationsUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'BrightCove', 'parent_id' => $integrationsUiModule->id],
                    ['created_at' => 'NOW()']
                );

            // Users
            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Users'],
                    ['created_at' => 'NOW()']
                );

            $usersUiModule = DB::table('ui_modules')
                                ->where('title', 'Users')
                                ->first();

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Manage Users', 'parent_id' => $usersUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Manage Roles', 'parent_id' => $usersUiModule->id],
                    ['created_at' => 'NOW()']
                );

            // Independent Activities
            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Independent Activities'],
                    ['created_at' => 'NOW()']
                );

            $independentActivitiesUiModule = DB::table('ui_modules')
                                                ->where('title', 'Independent Activities')
                                                ->first();

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'All Independent Activities', 'parent_id' => $independentActivitiesUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Export/Import Activities', 'parent_id' => $independentActivitiesUiModule->id],
                    ['created_at' => 'NOW()']
                );

            // Authoring
            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Authoring'],
                    ['created_at' => 'NOW()']
                );

            $authoringUiModule = DB::table('ui_modules')
                                    ->where('title', 'Authoring')
                                    ->first();

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Project', 'parent_id' => $authoringUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Playlist', 'parent_id' => $authoringUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Activity', 'parent_id' => $authoringUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Team', 'parent_id' => $authoringUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'Independent Activity', 'parent_id' => $authoringUiModule->id],
                    ['created_at' => 'NOW()']
                );

            DB::table('ui_modules')
                ->updateOrInsert(
                    ['title' => 'My Interactive Video', 'parent_id' => $authoringUiModule->id],
                    ['created_at' => 'NOW()']
                );
        });
    }
}
