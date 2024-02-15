<?php

namespace Database\Seeders\C2E\Publisher;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class C2ePublisherOrganizationPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'c2e-publisher:view-author',
            'display_name' => 'Author View Of C2E Publisher',
            'feature' => 'C2E Publisher'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'c2e-publisher:view-stores',
            'display_name' => 'Author View Of C2E Publisher Stores',
            'feature' => 'C2E Publisher'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'c2e-publisher:publish-independent-activity',
            'display_name' => 'Publish Independent Activity to Publisher Store',
            'feature' => 'C2E Publisher'
        ]);
    }
}
