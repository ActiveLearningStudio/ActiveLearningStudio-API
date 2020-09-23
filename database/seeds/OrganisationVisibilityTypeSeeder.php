<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganisationVisibilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organisation_visibility_types')->insert([
            'name' => 'private',
            'display_name' => 'Private'
        ]);

        DB::table('organisation_visibility_types')->insert([
            'name' => 'protected',
            'display_name' => 'Protected'
        ]);

        DB::table('organisation_visibility_types')->insert([
            'name' => 'global',
            'display_name' => 'Global'
        ]);

        DB::table('organisation_visibility_types')->insert([
            'name' => 'public',
            'display_name' => 'Public'
        ]);
    }
}
