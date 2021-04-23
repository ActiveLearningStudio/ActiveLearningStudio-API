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
            'name' => 'admin',
            'display_name' => 'Administrator'
        ]);

        $courseCreatorId = DB::table('organization_role_types')->insertGetId([
            'name' => 'course_creator',
            'display_name' => 'Course Creator'
        ]);

        DB::table('organization_role_types')->insert([
            'name' => 'member',
            'display_name' => 'Member'
        ]);

        DB::table('organization_role_types')->insert([
            'name' => 'self_registered',
            'display_name' => 'Self Registered'
        ]);

        $users = DB::table('users')->select('id')->get();

        foreach ($users as $user) {
            DB::table('organization_user_roles')->insert([
                'organization_id' => 1,
                'user_id' => $user->id,
                'organization_role_type_id' => $courseCreatorId
            ]);
        }
    }
}
