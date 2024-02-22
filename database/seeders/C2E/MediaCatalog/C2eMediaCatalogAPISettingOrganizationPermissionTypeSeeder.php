<?php

namespace Database\Seeders\C2E\MediaCatalog;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class C2eMediaCatalogAPISettingOrganizationPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'c2e-media-catalog:view-author',
            'display_name' => 'Author View Of C2E Media Catalog',
            'feature' => 'C2E Media Catalog'
        ]);
    }
}
