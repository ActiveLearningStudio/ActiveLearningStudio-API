<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultSubjectsSeeder extends Seeder
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
            $subjects = '';

            $subjects = [
                [
                    'name' => 'Arts',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Career & Technical Education',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Computer Science',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Language Arts',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Mathematics',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Social Studies',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Science',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
            ];

            DB::table('subjects')->insertOrIgnore($subjects);
        }
    }
}
