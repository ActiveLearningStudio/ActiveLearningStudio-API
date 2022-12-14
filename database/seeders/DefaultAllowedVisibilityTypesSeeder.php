<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationVisibilityType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultAllowedVisibilityTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = Organization::all();
        $allowedVisibilityTypes = OrganizationVisibilityType::whereIn("name", ["private", "protected", "public"])
                                                            ->pluck("id");

        foreach ($organizations as $organization) {
            
            $organization->allowedVisibilityTypes()->sync($allowedVisibilityTypes);
        }
    }
}
