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
        $organizationPermissionTypes = DB::table('organization_permission_types')
                                                ->select('id', 'name', 'feature')
                                                ->where('feature', '<>', 'Group')
                                                ->get();
        $courseCreatorRole = DB::table('organization_role_types')->where('name', 'course_creator')->first();
        $selfRegisteredRole = DB::table('organization_role_types')->where('name', 'self_registered')->first();
        $adminRole = DB::table('organization_role_types')->where('name', 'admin')->first();
        $memberRole = DB::table('organization_role_types')->where('name', 'member')->first();

        foreach ($organizationPermissionTypes as $organizationPermissionType) {
            DB::table('organization_role_permissions')->insertOrIgnore([
                'organization_role_type_id' => $adminRole->id,
                'organization_permission_type_id' => $organizationPermissionType->id
            ]);

            if (in_array($organizationPermissionType->feature, ['Project', 'Playlist', 'Activity', 'Group', 'Search'])) {
                DB::table('organization_role_permissions')->insertOrIgnore([
                    'organization_role_type_id' => $courseCreatorRole->id,
                    'organization_permission_type_id' => $organizationPermissionType->id
                ]);

                DB::table('organization_role_permissions')->insertOrIgnore([
                    'organization_role_type_id' => $selfRegisteredRole->id,
                    'organization_permission_type_id' => $organizationPermissionType->id
                ]);
            }

            $courseCreatorPermissions = [
                'team:view',
            ];

            if (in_array($organizationPermissionType->name, $courseCreatorPermissions)) {
                DB::table('organization_role_permissions')->insertOrIgnore([
                    'organization_role_type_id' => $courseCreatorRole->id,
                    'organization_permission_type_id' => $organizationPermissionType->id
                ]);

                DB::table('organization_role_permissions')->insertOrIgnore([
                    'organization_role_type_id' => $selfRegisteredRole->id,
                    'organization_permission_type_id' => $organizationPermissionType->id
                ]);
            }

            $memberPermissions = [
                'project:view',
                'project:share',
                'project:clone',
                'project:favorite',
                'project:publish',
                'playlist:view',
                'playlist:duplicate',
                'playlist:publish',
                'activity:view',
                'activity:share',
                'activity:duplicate',
                'team:view',
                'group:view',
                'search:advance',
                'search:dashboard'
            ];

            if (in_array($organizationPermissionType->name, $memberPermissions)) {
                DB::table('organization_role_permissions')->insertOrIgnore([
                    'organization_role_type_id' => $memberRole->id,
                    'organization_permission_type_id' => $organizationPermissionType->id
                ]);
            }
        }
    }
}
