<?php

use Illuminate\Database\Seeder;

class OrganizationPermissionTypeUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:edit', 'display_name' => 'Edit Organization'],
                ['feature' => 'admin-organization']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:delete', 'display_name' => 'Delete Organization'],
                ['feature' => 'admin-organization']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:view', 'display_name' => 'View Organization'],
                ['feature' => 'admin-organization']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:create', 'display_name' => 'Create Organization'],
                ['feature' => 'admin-organization']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:edit-role', 'display_name' => 'Edit Organization Role'],
                ['feature' => 'admin-organization']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:add-user', 'display_name' => 'Add Organization User'],
                ['feature' => 'admin-user']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:update-user', 'display_name' => 'Update Organization User'],
                ['feature' => 'admin-user']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:delete-user', 'display_name' => 'Delete Organization User'],
                ['feature' => 'admin-user']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:remove-user', 'display_name' => 'Remove Organization User'],
                ['feature' => 'admin-user']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:view-user', 'display_name' => 'View Organization User'],
                ['feature' => 'admin-user']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:add-role', 'display_name' => 'Add Organization Role'],
                ['feature' => 'admin-user']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:edit-role', 'display_name' => 'Edit Organization Role'],
                ['feature' => 'admin-user']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:edit-project', 'display_name' => 'Edit Organization Project'],
                ['feature' => 'admin-project']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:delete-project', 'display_name' => 'Delete Organization Project'],
                ['feature' => 'admin-project']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:export-project', 'display_name' => 'Export Organization Project'],
                ['feature' => 'admin-project']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:import-project', 'display_name' => 'Import Organization Project'],
                ['feature' => 'admin-project']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:download-project', 'display_name' => 'Download Organization Project'],
                ['feature' => 'admin-project']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:view-library-request-project', 'display_name' => 'View Library Request Organization Project'],
                ['feature' => 'admin-project']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:review-library-request-project', 'display_name' => 'Review Library Request Organization Project'],
                ['feature' => 'admin-project']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:edit-activity', 'display_name' => 'Edit Organization Activity'],
                ['feature' => 'admin-activity']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:delete-activity', 'display_name' => 'Delete Organization Activity'],
                ['feature' => 'admin-activity']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:view-activity', 'display_name' => 'View Organization Activity'],
                ['feature' => 'admin-activity']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:create-activity', 'display_name' => 'Create Organization Activity'],
                ['feature' => 'admin-activity']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:create-default-sso', 'display_name' => 'Create Default Sso Settings'],
                ['feature' => 'admin-sso']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:view-default-sso', 'display_name' => 'View Default Sso Settings'],
                ['feature' => 'admin-sso']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:update-default-sso', 'display_name' => 'Update Default Sso Settings'],
                ['feature' => 'admin-sso']
            );

        DB::table('organization_permission_types')
            ->updateOrInsert(
                ['name' => 'organization:delete-default-sso', 'display_name' => 'Delete Default Sso Settings'],
                ['feature' => 'admin-sso']
            );
    }
}
