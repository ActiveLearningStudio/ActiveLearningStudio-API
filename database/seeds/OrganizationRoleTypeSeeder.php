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
        DB::table('organization_role_types')->insertOrIgnore([
            'id' => 1,
            'name' => 'admin',
            'display_name' => 'Administrator'
        ]);

        DB::table('organization_role_types')->insertOrIgnore([
            'id' => 2,
            'name' => 'course_creator',
            'display_name' => 'Course Creator'
        ]);
        // This maybe a redundant query, but is okay
        $courseCreator = DB::table('organization_role_types')->select('id')->where('name', 'course_creator')->first();
        
        DB::table('organization_role_types')->insertOrIgnore([
            'id' => 3,
            'name' => 'member',
            'display_name' => 'Member'
        ]);

        DB::table('organization_role_types')->insertOrIgnore([
            'id' => 4,
            'name' => 'self_registered',
            'display_name' => 'Self Registered'
        ]);

        $orgUserRoles = DB::table('organization_user_roles')->count();
        
        // If there are no user roles associations yet, then we patch user.
        if ($courseCreator && !$orgUserRoles) {
            $users = DB::table('users')->select('id')->get();
            foreach ($users as $user) {
                DB::table('organization_user_roles')->insertOrIgnore([
                    'organization_id' => 1,
                    'user_id' => $user->id,
                    'organization_role_type_id' => $courseCreator->id
                ]);
            }
        }
    }
}
