<?php

use Illuminate\Database\Seeder;

class TeamPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:add-team-user',
            'display_name' => 'Add users to team',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:add-project',
            'display_name' => 'Add Project',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:remove-project',
            'display_name' => 'Remove Project',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:add-member-project',
            'display_name' => 'Add Member Project',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:remove-member-project',
            'display_name' => 'Remove Member Project',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:add-playlist',
            'display_name' => 'Add Playlist',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:edit-playlist',
            'display_name' => 'Edit Playlist',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:delete-playlist',
            'display_name' => 'Delete Playlist',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:add-activity',
            'display_name' => 'Add Activity',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:edit-activity',
            'display_name' => 'Edit Activity',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:delete-activity',
            'display_name' => 'Delete Activity',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:share-project',
            'display_name' => 'Share Project',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:share-activity',
            'display_name' => 'Share Activity',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:share-playlist',
            'display_name' => 'Share Playlist',
            'feature' => 'Team'
        ]);

        DB::table('team_permission_types')->insertOrIgnore([
            'name' => 'team:assign-team-role',
            'display_name' => 'Assign Team Role',
            'feature' => 'Team'
        ]);
    }
}
