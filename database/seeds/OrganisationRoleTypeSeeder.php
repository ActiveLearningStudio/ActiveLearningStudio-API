<?php

use Illuminate\Database\Seeder;

class OrganisationRoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organisation_role_types')->insert([
            'name' => 'admin',
            'display_name' => 'Administrator'
        ]);

        DB::table('organisation_role_types')->insert([
            'name' => 'moderator',
            'display_name' => 'Moderator'
        ]);

        DB::table('organisation_role_types')->insert([
            'name' => 'member',
            'display_name' => 'Member'
        ]);
    }
}
