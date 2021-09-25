<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrganizationVisibilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('organization_visibility_types')
            ->updateOrInsert(
                ['name' => 'private'],
                ['display_name' => 'Private']
            );

        DB::table('organization_visibility_types')
            ->updateOrInsert(
                ['name' => 'protected'],
                ['display_name' => 'My Org']
            );

        DB::table('organization_visibility_types')
            ->updateOrInsert(
                ['name' => 'global'],
                ['display_name' => 'Parent/Child Org']
            );

        DB::table('organization_visibility_types')
            ->updateOrInsert(
                ['name' => 'public'],
                ['display_name' => 'Instance Wide']
            );
    }
}
