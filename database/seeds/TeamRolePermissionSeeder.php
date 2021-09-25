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
        $TeamPermissionTypes = DB::table('team_permission_types')->select('id', 'name', 'feature')->get();

        $adminRole = DB::table('team_role_types')->where('name', 'admin')->first();
        $contributorRole = DB::table('team_role_types')->where('name', 'contributor')->first();
        $memberRole = DB::table('team_role_types')->where('name', 'member')->first();

        foreach ($TeamPermissionTypes as $teamPermissionType) {

            DB::table('team_role_permissions')->insertOrIgnore([
                'team_role_type_id' => $adminRole->id,
                'team_permission_type_id' => $teamPermissionType->id
            ]);

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
            ];

            if (in_array($teamPermissionType->name, $contributorPermissions)) {
                DB::table('team_role_permissions')->insertOrIgnore([
                    'team_role_type_id' => $contributorRole->id,
                    'team_permission_type_id' => $teamPermissionType->id
                ]);
            }

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

            if (in_array($teamPermissionType->name, $memberPermissions)) {
                DB::table('team_role_permissions')->insertOrIgnore([
                    'team_role_type_id' => $memberRole->id,
                    'team_permission_type_id' => $teamPermissionType->id
                ]);
            }
        }
    }
}
