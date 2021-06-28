<?php

use Illuminate\Database\Seeder;

class OrganizationUserRoleSeeder extends Seeder
{
    /**
    * The permission used by the admin.
    *
    * @var string
    */
    protected $adminPermission = 'organization:create';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::transaction(function () {
            $organizations = DB::table('organizations')->get();
            
            foreach ($organizations as $organization) {
                $organizationId = $organization->id;
                $organizationRoleTypes = DB::table('organization_role_types')->where('organization_id', $organizationId)->get();
                $organizationRoleTypeIds = $organizationRoleTypes->pluck('id')->toArray();
                $organizationRoleTypeNames = $organizationRoleTypes->pluck('name')->toArray();
                $organizationUserRoles = DB::table('organization_user_roles')->where('organization_id', $organizationId)->whereNotIn('organization_role_type_id', $organizationRoleTypeIds)->get();
                $otherOrganizationRoleTypeIds = $organizationUserRoles->pluck('organization_role_type_id')->toArray();
                $otherOrganizationRoleTypes = DB::table('organization_role_types')->whereIn('id', $otherOrganizationRoleTypeIds)->get();
                foreach ($otherOrganizationRoleTypes as $otherOrganizationRoleType) {
                    if (in_array($otherOrganizationRoleType->name, $organizationRoleTypeNames)) {
                        foreach ($organizationRoleTypes as $organizationRoleType) {
                            if ($otherOrganizationRoleType->name === $organizationRoleType->name) {
                                $affected = DB::table('organization_user_roles')
                                                ->where([
                                                    ['organization_id', '=', $organizationId],
                                                    ['organization_role_type_id', '=', $otherOrganizationRoleType->id],
                                                ])
                                                ->update(['organization_role_type_id' => $organizationRoleType->id]);
                            }
                        }
                    } else {
                        $organizationRoleTypeId = DB::table('organization_role_types')->insertGetId([
                            'name' => $otherOrganizationRoleType->name,
                            'display_name' => $otherOrganizationRoleType->display_name,
                            'organization_id' => $organizationId,
                            'created_at' => 'NOW()',
                            'updated_at' => 'NOW()'
                        ]);

                        $otherOrganizationRolePermissions = DB::table('organization_role_permissions')
                                                                ->where('organization_role_type_id', $otherOrganizationRoleType->id)
                                                                ->get();

                        foreach ($otherOrganizationRolePermissions as $otherOrganizationRolePermission) {
                            DB::table('organization_role_permissions')->insert([
                                'organization_role_type_id' => $organizationRoleTypeId,
                                'organization_permission_type_id' => $otherOrganizationRolePermission->organization_permission_type_id,
                                'created_at' => 'NOW()',
                                'updated_at' => 'NOW()'
                            ]);
                        }

                        $affected = DB::table('organization_user_roles')
                                        ->where([
                                            ['organization_id', '=', $organizationId],
                                            ['organization_role_type_id', '=', $otherOrganizationRoleType->id],
                                        ])
                                        ->update(['organization_role_type_id' => $organizationRoleTypeId]);
                    }
                }
            }
        });
    }
}
