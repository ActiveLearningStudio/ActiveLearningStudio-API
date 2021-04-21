<?php

use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organizations')->insert([
            'name' => 'Curriki Studio',
            'description' => 'Curriki Studio, default organization.',
            'domain' => 'currikistudio'
        ]);

        DB::table('projects')->update(['organization_id' => 1, 'organization_visibility_type_id' => 4]);
    }
}
