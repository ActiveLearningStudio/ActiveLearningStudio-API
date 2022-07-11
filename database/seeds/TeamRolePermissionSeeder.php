<?php

use Illuminate\Database\Seeder;

class TeamRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contributorPermissions = [
            'team:add-project',
            'team:add-playlist',
            'team:edit-playlist',
            'team:delete-playlist',
            'team:add-activity',
            'team:edit-activity',
            'team:delete-activity',
            'team:share-project',
            'team:share-playlist',
            'team:share-activity',
            'team:add-project-user',
            'team:remove-project-user',
            'team:edit-project',
            'team:view-project',
            'team:publish-project',
            'team:view-playlist',
            'team:view-activity',
            'team:publish-playlist',
            'team:publish-activity',
            'team:edit',
        ];

        $memberPermissions = [
            'team:share-project',
            'team:share-playlist',
            'team:share-activity',
            'team:view-project',
            'team:publish-project',
            'team:view-playlist',
            'team:view-activity',
            'team:publish-playlist',
            'team:publish-activity',
        ];

        $TeamPermissionTypes = DB::table('team_permission_types')->select('id', 'name', 'feature')->get();
        $teamRoles = DB::table('team_role_types')->pluck('id', 'name');

        foreach ($TeamPermissionTypes as $teamPermissionType) {

            if (isset($teamRoles['admin'])) {
                DB::table('team_role_permissions')->insertOrIgnore([
                    'team_role_type_id' => $teamRoles['admin'],
                    'team_permission_type_id' => $teamPermissionType->id
                ]);
            }

            if (in_array($teamPermissionType->name, $contributorPermissions) && isset($teamRoles['contributor'])) {
                DB::table('team_role_permissions')->insertOrIgnore([
                    'team_role_type_id' => $teamRoles['contributor'],
                    'team_permission_type_id' => $teamPermissionType->id
                ]);
            }

            if (in_array($teamPermissionType->name, $memberPermissions) && isset($teamRoles['member'])) {
                DB::table('team_role_permissions')->insertOrIgnore([
                    'team_role_type_id' => $teamRoles['member'],
                    'team_permission_type_id' => $teamPermissionType->id
                ]);
            }
        }
    }
}
