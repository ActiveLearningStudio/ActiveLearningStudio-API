<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSsoPermissionTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default Sso Permission under Organization
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-default-sso',
            'display_name' => 'Create Default Sso Settings',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-default-sso',
            'display_name' => 'View Default Sso Settings',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:update-default-sso',
            'display_name' => 'Update Default Sso Settings',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-default-sso',
            'display_name' => 'Delete Default Sso Settings',
            'feature' => 'Organization'
        ]);
    }
}
