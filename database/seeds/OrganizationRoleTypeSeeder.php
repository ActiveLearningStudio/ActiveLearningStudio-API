<?php

use Illuminate\Database\Seeder;

class OrganizationRoleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_role_types')->insert([
            'name' => 'super_admin',
            'display_name' => 'Super Admin'
        ]);

        DB::table('organization_role_types')->insert([
            'name' => 'admin',
            'display_name' => 'Administrator'
        ]);

        DB::table('organization_role_types')->insert([
            'name' => 'course_creator',
            'display_name' => 'Course Creator'
        ]);

        DB::table('organization_role_types')->insert([
            'name' => 'member',
            'display_name' => 'Member'
        ]);
    }
}
