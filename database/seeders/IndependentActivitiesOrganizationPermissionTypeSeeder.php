<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndependentActivitiesOrganizationPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:create',
            'display_name' => 'Create Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view',
            'display_name' => 'View Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:edit',
            'display_name' => 'Edit Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:delete',
            'display_name' => 'Delete Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:share',
            'display_name' => 'Share Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:duplicate',
            'display_name' => 'Duplicate Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:export',
            'display_name' => 'Export Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:import',
            'display_name' => 'Import Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-export',
            'display_name' => 'View Exported Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-author',
            'display_name' => 'Author View Of Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:edit-author',
            'display_name' => 'Author Edit Of Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-library-preference-options',
            'display_name' => 'Update Library Preference',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-copy-to-my-activities-option',
            'display_name' => 'Copy to My Activities',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-copy-to-my-projects-option',
            'display_name' => 'Copy to My projects',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-export-xapi-option',
            'display_name' => 'Export xAPI',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-export-h5p-option',
            'display_name' => 'Export H5P',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view-existing-activity-search-option',
            'display_name' => 'Search Existing Activity',
            'feature' => 'Independent Activity'
        ]);
    }
}
