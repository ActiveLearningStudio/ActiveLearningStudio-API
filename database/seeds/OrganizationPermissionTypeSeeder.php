<?php

use Illuminate\Database\Seeder;

class OrganizationPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Organizations
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit',
            'display_name' => 'Edit Organization',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete',
            'display_name' => 'Delete Organization',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view',
            'display_name' => 'View Organization',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create',
            'display_name' => 'Create Organization',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:add-user',
            'display_name' => 'Add Organization User',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:invite-members',
            'display_name' => 'Invite Organization Members',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:update-user',
            'display_name' => 'Update Organization User',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-user',
            'display_name' => 'Delete Organization User',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:remove-user',
            'display_name' => 'Remove Organization User',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-user',
            'display_name' => 'View Organization User',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:add-admin',
            'display_name' => 'Add Organization Admin',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-admin',
            'display_name' => 'Delete Organization Admin',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:upload-thumb',
            'display_name' => 'Upload Thumb',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:add-role',
            'display_name' => 'Add Organization Role',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-role',
            'display_name' => 'Edit Organization Role',
            'feature' => 'Organization'
        ]);

        // Projects
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:edit',
            'display_name' => 'Edit Project',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:delete',
            'display_name' => 'Delete Project',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:view',
            'display_name' => 'View Project',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:create',
            'display_name' => 'Create Project',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:share',
            'display_name' => 'Share Project',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:clone',
            'display_name' => 'Clone Project',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:request-indexing',
            'display_name' => 'Request Project Indexing',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:favorite',
            'display_name' => 'Edit Favorite',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:publish',
            'display_name' => 'Publish Project',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:upload-thumb',
            'display_name' => 'Upload Project Thumb',
            'feature' => 'Project'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:recent',
            'display_name' => 'View Recent project',
            'feature' => 'Project'
        ]);

        // Project Import Export
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:export',
            'display_name' => 'Project Export',
            'feature' => 'Export'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'project:import',
            'display_name' => 'Project Import',
            'feature' => 'Import'
        ]);

        // Playlists
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'playlist:edit',
            'display_name' => 'Edit Playlist',
            'feature' => 'Playlist'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'playlist:delete',
            'display_name' => 'Delete Playlist',
            'feature' => 'Playlist'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'playlist:view',
            'display_name' => 'View Playlist',
            'feature' => 'Playlist'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'playlist:create',
            'display_name' => 'Create Playlist',
            'feature' => 'Playlist'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'playlist:clone',
            'display_name' => 'Clone Playlist',
            'feature' => 'Playlist'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'playlist:publish',
            'display_name' => 'publish Playlist',
            'feature' => 'Playlist'
        ]);

        // Activities
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'activity:edit',
            'display_name' => 'Edit Activity',
            'feature' => 'Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'activity:delete',
            'display_name' => 'Delete Activity',
            'feature' => 'Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'activity:view',
            'display_name' => 'View Activity',
            'feature' => 'Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'activity:create',
            'display_name' => 'Create Activity',
            'feature' => 'Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'activity:upload',
            'display_name' => 'Upload Activity',
            'feature' => 'Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'activity:share',
            'display_name' => 'Share Activity',
            'feature' => 'Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'activity:clone',
            'display_name' => 'Clone Activity',
            'feature' => 'Activity'
        ]);

        // Teams
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:edit',
            'display_name' => 'Edit Team',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:delete',
            'display_name' => 'Delete Team',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:view',
            'display_name' => 'View Team',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:create',
            'display_name' => 'Create Team',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:invite-member',
            'display_name' => 'Invite Team Member',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:add-projects',
            'display_name' => 'Add Team Projects ',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:remove-projects',
            'display_name' => 'Remove Team Projects',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:add-project-user',
            'display_name' => 'Add Team Project User',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:remove-project-user',
            'display_name' => 'Remove Team Project User',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:add-user',
            'display_name' => 'Add Team User',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:remove-user',
            'display_name' => 'Remove Team User',
            'feature' => 'Team'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'team:share',
            'display_name' => 'Share Team',
            'feature' => 'Team'
        ]);

        // Groups
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:edit',
            'display_name' => 'Edit Group',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:delete',
            'display_name' => 'Delete Group',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:view',
            'display_name' => 'View Group',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:create',
            'display_name' => 'Create Group',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:invite-member',
            'display_name' => 'Invite Group Member',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:add-projects',
            'display_name' => 'Add Group Projects',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:remove-projects',
            'display_name' => 'Remove Group Projects',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:add-project-user',
            'display_name' => 'Add Group Project User',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:remove-project-user',
            'display_name' => 'Remove Group Project User',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:add-user',
            'display_name' => 'Add Group User',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:remove-user',
            'display_name' => 'Remove Group User',
            'feature' => 'Group'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'group:share',
            'display_name' => 'Share Group',
            'feature' => 'Group'
        ]);

        // Search
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'search:advance',
            'display_name' => 'Advance Search',
            'feature' => 'Search'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'search:dashboard',
            'display_name' => 'Dashboard Search',
            'feature' => 'Search'
        ]);
    }
}
