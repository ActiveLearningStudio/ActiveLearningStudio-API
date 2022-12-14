<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->insertOrIgnore([
            'name' => 'Curriki Studio',
            'description' => 'Curriki Studio, default organization.',
            'domain' => 'currikistudio'
        ]);

        DB::table('projects')->whereNull('organization_id')->update(['organization_id' => 1, 'organization_visibility_type_id' => 4]);
    }
}
