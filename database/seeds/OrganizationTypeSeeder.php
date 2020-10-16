<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationTypeSeeder extends Seeder
{
    public function run()
    {
        DB::table('organization_types')->insert([
            'name' => 'k12',
            'label' => 'K-12',
            'order' => 0
        ]);
        DB::table('organization_types')->insert([
            'name' => 'highered',
            'label' => 'Higher Education',
            'order' => 1
        ]);
        DB::table('organization_types')->insert([
            'name' => 'businesscorp',
            'label' => 'Business/Corporation',
            'order' => 2
        ]);
        DB::table('organization_types')->insert([
            'name' => 'nonprofit',
            'label' => 'Nonprofit',
            'order' => 3
        ]);
        DB::table('organization_types')->insert([
            'name' => 'govedu',
            'label' => 'Government/EDU',
            'order' => 4
        ]);
        DB::table('organization_types')->insert([
            'name' => 'other',
            'label' => 'Other',
            'order' => 5
        ]);
    }
}
