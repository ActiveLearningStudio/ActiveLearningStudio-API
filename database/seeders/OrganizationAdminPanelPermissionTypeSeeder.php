<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationAdminPanelPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin Panel
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'video:view',
            'display_name' => 'View Interactive Video',
            'feature' => 'Video'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-exported-project',
            'display_name' => 'View Exported Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-exported-project',
            'display_name' => 'View Exported Project',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-activity-type',
            'display_name' => 'Edit Organization Activity Type',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-activity-type',
            'display_name' => 'Delete Organization Activity Type',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-activity-type',
            'display_name' => 'View Organization Activity Type',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-activity-type',
            'display_name' => 'Create Organization Activity Type',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-activity-item',
            'display_name' => 'Edit Organization Activity Item',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-activity-item',
            'display_name' => 'Delete Organization Activity Item',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-activity-item',
            'display_name' => 'View Organization Activity Item',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-activity-item',
            'display_name' => 'Create Organization Activity Item',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-activity-layout',
            'display_name' => 'Edit Organization Activity Layout',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-activity-layout',
            'display_name' => 'Delete Organization Activity Layout',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-activity-layout',
            'display_name' => 'View Organization Activity Layout',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-activity-layout',
            'display_name' => 'Create Organization Activity Layout',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-subject',
            'display_name' => 'Edit Organization Subject',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-subject',
            'display_name' => 'Delete Organization Subject',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-subject',
            'display_name' => 'View Organization Subject',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-subject',
            'display_name' => 'Create Organization Subject',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-education-level',
            'display_name' => 'Edit Organization Education Level',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-education-level',
            'display_name' => 'Delete Organization Education Level',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-education-level',
            'display_name' => 'View Organization Education Level',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-education-level',
            'display_name' => 'Create Organization Education Level',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-author-tag',
            'display_name' => 'Edit Organization Author Tag',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-author-tag',
            'display_name' => 'Delete Organization Author Tag',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-author-tag',
            'display_name' => 'View Organization Author Tag',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-author-tag',
            'display_name' => 'Create Organization Author Tag',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-role',
            'display_name' => 'View Organization Role',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-all-setting',
            'display_name' => 'Edit All Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-all-setting',
            'display_name' => 'Delete All Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-all-setting',
            'display_name' => 'View All Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-all-setting',
            'display_name' => 'Create All Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-lms-setting',
            'display_name' => 'Edit LMS Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-lms-setting',
            'display_name' => 'Delete LMS Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-lms-setting',
            'display_name' => 'View LMS Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-lms-setting',
            'display_name' => 'Create LMS Organization Setting',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-media',
            'display_name' => 'Edit Organization Media',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-media',
            'display_name' => 'Delete Organization Media',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-media',
            'display_name' => 'View Organization Media',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-media',
            'display_name' => 'Create Organization Media',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-google-classroom',
            'display_name' => 'Edit Organization Google Classroom',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-google-classroom',
            'display_name' => 'Delete Organization Google Classroom',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-google-classroom',
            'display_name' => 'View Organization Google Classroom',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-google-classroom',
            'display_name' => 'Create Organization Google Classroom',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:edit-microsoft-team',
            'display_name' => 'Edit Organization Microsoft Team',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:delete-microsoft-team',
            'display_name' => 'Delete Organization Microsoft Team',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:view-microsoft-team',
            'display_name' => 'View Organization Microsoft Team',
            'feature' => 'Organization'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'organization:create-microsoft-team',
            'display_name' => 'Create Organization Microsoft Team',
            'feature' => 'Organization'
        ]);
    }
}
