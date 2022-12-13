<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationAdminPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin Panel
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-project',
            'display_name' => 'Edit Organization Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-project',
            'display_name' => 'Delete Organization Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:export-project',
            'display_name' => 'Export Organization Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:import-project',
            'display_name' => 'Import Organization Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:download-project',
            'display_name' => 'Download Organization Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-library-request-project',
            'display_name' => 'View Library Request Organization Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:review-library-request-project',
            'display_name' => 'Review Library Request Organization Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-activity',
            'display_name' => 'Edit Organization Activity',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-activity',
            'display_name' => 'Delete Organization Activity',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-activity',
            'display_name' => 'View Organization Activity',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-activity',
            'display_name' => 'Create Organization Activity',
            'feature' => 'Organization'
        ]);
    }
}
