<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndependentActivityPermissionsViewEditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $defaultPermissions = [
            'independent-activity:view-author', 
            'independent-activity:edit-author'
        ];

        $viewEditPermissions = DB::table('organization_permission_types')
                                                        ->select('id')
                                                        ->whereIn('name', $defaultPermissions)
                                                        ->get();

        $organizationRoleTypes = DB::table('organization_role_types')
                                    ->select('id')
                                    ->get();

        return DB::transaction(function () use ($viewEditPermissions, $organizationRoleTypes) {
            foreach ($viewEditPermissions as $viewEditPermission) {
                foreach ($organizationRoleTypes as $organizationRoleType) {
                    DB::table('organization_role_permissions')->insertOrIgnore([
                        'organization_role_type_id' => $organizationRoleType->id,
                        'organization_permission_type_id' => $viewEditPermission->id,
                        'created_at' => 'NOW()',
                        'updated_at' => 'NOW()'
                    ]);
                }
            }
        });
    }
}
