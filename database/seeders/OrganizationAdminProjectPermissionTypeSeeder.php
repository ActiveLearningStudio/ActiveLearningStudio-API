<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationAdminProjectPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-all-project',
            'display_name' => 'View All Organization Projects',
            'feature' => 'Organization'
        ]);
    }
}
