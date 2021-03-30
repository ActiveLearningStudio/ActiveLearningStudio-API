<?php

use Illuminate\Database\Seeder;

class OrganizationRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizationPermissionTypes = DB::table('organization_permission_types')->select('id', 'feature')->get();

        foreach ($organizationPermissionTypes as $organizationPermissionType) {
            DB::table('organization_role_permissions')->insert([
                'organization_role_type_id' => 2,
                'organization_permission_type_id' => $organizationPermissionType->id
            ]);

            if (in_array($organizationPermissionType->feature, ['Project', 'Playlist', 'Activity', 'Team', 'Group', 'Search'])) {
                DB::table('organization_role_permissions')->insert([
                    'organization_role_type_id' => 3,
                    'organization_permission_type_id' => $organizationPermissionType->id
                ]);
            }


            $memberPermissions = ['12', '14', '15', '17', '18', '21', '23', '24', '27', '29', '30', '33', '45', '55', '56'];

            if (in_array($organizationPermissionType->id, $memberPermissions)) {
                DB::table('organization_role_permissions')->insert([
                    'organization_role_type_id' => 4,
                    'organization_permission_type_id' => $organizationPermissionType->id
                ]);
            }
        }
    }
}
