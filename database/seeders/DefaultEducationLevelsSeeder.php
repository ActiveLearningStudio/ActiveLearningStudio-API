<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultEducationLevelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $organizations = DB::table('organizations')->pluck('id');

        foreach ($organizations as $key => $organization) {
            $educationLevels = '';

            $educationLevels = [
                [
                    'name' => 'Preschool (Ages 0-4)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Kindergarten-Grade 2 (Ages 5-7)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Grades 3-5 (Ages 8-10)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Grades 6-8 (Ages 11-13)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Grades 9-10 (Ages 14-16)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Grades 11-12 (Ages 16-18)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'College & Beyond',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Professional Development',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Special Education',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
            ];

            DB::table('education_levels')->insertOrIgnore($educationLevels);
        }
    }
}
