<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultAuthorTagSeeder extends Seeder
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
            $authorTags = '';
            $authorTags = [
                [
                    'name' => 'Homework/Assignment',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Diagram/Illustration',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Game',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Simulation',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Primary Source',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Reading/eBookTry',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Case Study',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Warmup',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Unit of Study (for comprehensive layouts?)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'PBL (Project Based Learning)',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Language acquisition? Language learning',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'name' => 'Vocabulary practice',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
            ];

            DB::table('author_tags')->insertOrIgnore($authorTags);
        }
    }
}
