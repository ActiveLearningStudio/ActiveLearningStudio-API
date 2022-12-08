<?php

use Illuminate\Database\Seeder;

class UpdateAllowedOrganizationVisibilityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $protected = DB::table('organization_visibility_types')->where('name', 'protected')->first();

        $global = DB::table('organization_visibility_types')->where('name', 'global')->first();

        DB::transaction(function () use ($protected, $global) {
            $affected = DB::table('organization_visibility_types')
                ->where('id', $protected->id)
                ->update(['display_name' => 'Protected']);

            $affected = DB::table('organization_visibility_types')
                ->where('name', $global->id)
                ->update(['display_name' => 'My Organization']);

            $affected = DB::table('allowed_organization_visibility_types')
                ->where('organization_visibility_type_id', $protected->id)
                ->update(['organization_visibility_type_id' => $global->id]);
        });
    }
}
