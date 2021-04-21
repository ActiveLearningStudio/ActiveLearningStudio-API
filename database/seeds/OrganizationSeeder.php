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

        $users = DB::table('users')->select('id')->get();

        foreach ($users as $user) {
            DB::table('organization_user_roles')->insert([
                'organization_id' => 1,
                'user_id' => $user->id,
                'organization_role_type_id' => 3
            ]);
        }
    }
}
