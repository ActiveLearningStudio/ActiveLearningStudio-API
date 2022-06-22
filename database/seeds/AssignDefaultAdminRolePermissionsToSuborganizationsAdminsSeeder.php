<?php

use App\Models\OrganizationRoleType;
use Illuminate\Database\Seeder;

class AssignDefaultAdminRolePermissionsToSuborganizationsAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultAdminPermissions = DB::table('organization_role_permissions')
                                        ->where('organization_role_type_id', 1)
                                        ->pluck('organization_permission_type_id')
                                        ->toArray();

        $allAdminRoles = DB::table('organization_role_types')
                            ->where('name', 'admin')
                            ->where('id', '<>', 1)
                            ->pluck('id')
                            ->toArray();

        if (count($defaultAdminPermissions) > 0 && count($allAdminRoles) > 0) {

            foreach ($allAdminRoles as $roleId) {
                $role = OrganizationRoleType::find($roleId);
                if ($role) {
                    $role->permissions()->sync($defaultAdminPermissions);
                }
            }

        }
    }
}
