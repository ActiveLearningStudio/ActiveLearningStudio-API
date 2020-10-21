<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationVisibilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_visibility_types')->insert([
            'name' => 'private',
            'display_name' => 'Private'
        ]);

        DB::table('organization_visibility_types')->insert([
            'name' => 'protected',
            'display_name' => 'Protected'
        ]);

        DB::table('organization_visibility_types')->insert([
            'name' => 'global',
            'display_name' => 'Global'
        ]);

        DB::table('organization_visibility_types')->insert([
            'name' => 'public',
            'display_name' => 'Public'
        ]);
    }
}
