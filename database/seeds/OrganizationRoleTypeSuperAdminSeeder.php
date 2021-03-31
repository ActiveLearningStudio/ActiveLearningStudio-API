<?php

use Illuminate\Database\Seeder;

class OrganizationRoleTypeSuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdminRole = DB::table('organization_role_types')->where('name', 'super_admin')->first();

        if (!$superAdminRole) {
            DB::table('organization_role_types')->insert([
                'name' => 'super_admin',
                'display_name' => 'Super Admin'
            ]);
        }
    }
}
