<?php

use Illuminate\Database\Seeder;

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
    }
}
