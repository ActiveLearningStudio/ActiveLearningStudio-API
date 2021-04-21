<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ActivityTypeSeeder::class,
            H5pElasticsearchFieldsTableSeeder::class,
            MembershipTypeSeeder::class,
            OrganizationSeeder::class,
            OrganizationRoleTypeSeeder::class,
            OrganizationVisibilityTypeSeeder::class,
            OrganizationPermissionTypeSeeder::class,
            OrganizationRolePermissionSeeder::class,
        ]);
    }
}
