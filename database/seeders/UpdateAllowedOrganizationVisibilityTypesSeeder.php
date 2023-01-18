<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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

        $protectedAllowedOrganizationVisibilityTypes = DB::table('allowed_organization_visibility_types')
            ->where('organization_visibility_type_id', $protected->id)
            ->whereNotIn('organization_id', function ($query) use ($global) {
                $query->select('organization_id')
                    ->from('allowed_organization_visibility_types')
                    ->where('organization_visibility_type_id', $global->id);
            })->get();

        DB::transaction(function () use ($protected, $global, $protectedAllowedOrganizationVisibilityTypes) {
            $affected = DB::table('organization_visibility_types')
                ->where('id', $protected->id)
                ->update(['display_name' => 'Protected']);

            $affected = DB::table('organization_visibility_types')
                ->where('id', $global->id)
                ->update(['display_name' => 'My Organization']);

            foreach ($protectedAllowedOrganizationVisibilityTypes as $protectedAllowedOrganizationVisibilityType) {
                DB::table('allowed_organization_visibility_types')->insert([
                    'organization_id' => $protectedAllowedOrganizationVisibilityType->organization_id,
                    'organization_visibility_type_id' => $global->id
                ]);
            }

            $deleted = DB::table('allowed_organization_visibility_types')->where('organization_visibility_type_id', $protected->id)->delete();
        });
    }
}
