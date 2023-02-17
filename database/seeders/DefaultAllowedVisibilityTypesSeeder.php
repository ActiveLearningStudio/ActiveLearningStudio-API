<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\OrganizationVisibilityType;
use Illuminate\Database\Seeder;

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
        $allowedVisibilityTypes = OrganizationVisibilityType::whereIn("name", 
                                                                [
                                                                    "private",
                                                                    "global",
                                                                    "public"
                                                                ]
                                                            )
                                                            ->pluck("id");

        foreach ($organizations as $organization) {
            $organization->allowedVisibilityTypes()->sync($allowedVisibilityTypes);
        }
    }
}
