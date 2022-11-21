<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('organization_types')->insertOrIgnore([
            'name' => 'k12',
            'label' => 'K-12',
            'order' => 0
        ]);
        DB::table('organization_types')->insertOrIgnore([
            'name' => 'highered',
            'label' => 'Higher Education',
            'order' => 1
        ]);
        DB::table('organization_types')->insertOrIgnore([
            'name' => 'businesscorp',
            'label' => 'Business/Corporation',
            'order' => 2
        ]);
        DB::table('organization_types')->insertOrIgnore([
            'name' => 'nonprofit',
            'label' => 'Nonprofit',
            'order' => 3
        ]);
        DB::table('organization_types')->insertOrIgnore([
            'name' => 'govedu',
            'label' => 'Government/EDU',
            'order' => 4
        ]);
        DB::table('organization_types')->insertOrIgnore([
            'name' => 'other',
            'label' => 'Other',
            'order' => 5
        ]);
    }
}
