<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationPermissionTypeRemovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissionsToRemove = ['project:export', 'project:import', 'search:advance', 'search:dashboard'];

        $permissionsToRemoveIds = DB::table('organization_permission_types')->whereIn('name', $permissionsToRemove)->pluck('id');

        return DB::transaction(function () use ($permissionsToRemoveIds) {
            DB::table('organization_role_permissions')->whereIn('organization_permission_type_id', $permissionsToRemoveIds)->delete();

            DB::table('organization_permission_types')->whereIn('id', $permissionsToRemoveIds)->delete();
        });
    }
}
