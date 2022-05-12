<?php

use Illuminate\Database\Seeder;

class IndependentActivitiesOrganizationPermissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:create',
            'display_name' => 'Create Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:view',
            'display_name' => 'View Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:edit',
            'display_name' => 'Edit Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:delete',
            'display_name' => 'Delete Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:share',
            'display_name' => 'Share Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:duplicate',
            'display_name' => 'Duplicate Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:export',
            'display_name' => 'Export Independent Activity',
            'feature' => 'Independent Activity'
        ]);

        DB::table('organization_permission_types')->insertOrIgnore([
            'name' => 'independent-activity:import',
            'display_name' => 'Export Independent Activity',
            'feature' => 'Independent Activity'
        ]);
    }
}
