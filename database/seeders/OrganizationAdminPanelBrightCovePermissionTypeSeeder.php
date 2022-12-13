<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationAdminPanelBrightCovePermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin Panel
        DB::table('organization_permission_types')->updateOrInsert([
            'name' => 'organization:edit-brightcove-setting',
            'display_name' => 'Edit BrightCove Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->updateOrInsert([
            'name' => 'organization:delete-brightcove-setting',
            'display_name' => 'Delete BrightCove Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->updateOrInsert([
            'name' => 'organization:view-brightcove-setting',
            'display_name' => 'View BrightCove Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->updateOrInsert([
            'name' => 'organization:create-brightcove-setting',
            'display_name' => 'Create BrightCove Setting',
            'feature' => 'Organization'
        ]);
    }
}
