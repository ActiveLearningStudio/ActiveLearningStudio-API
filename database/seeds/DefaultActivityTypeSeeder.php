<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultActivityTypeSeeder extends Seeder
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
            $activity_types = '';

            $activity_types = [
                [
                    'title' => 'Audio changed',
                    'order' => 0,
                    'image' => '/storage/activity-types/audio.png',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Informational',
                    'order' => 0,
                    'image' => '/storage/activity-types/informational.png',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Photo / Images',
                    'order' => 0,
                    'image' => '/storage/activity-types/images.png',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Multimedia',
                    'order' => 0,
                    'image' => '/storage/activity-types/multimedia.png',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Questions',
                    'order' => 0,
                    'image' => '/storage/activity-types/questions.png',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ],
                [
                    'title' => 'Mathematics',
                    'order' => 0,
                    'image' => '/storage/activity-types/mathematics.png',
                    'created_at' => now(),
                    'organization_id' => $organization,
                ]
            ];

            DB::table('activity_types')->insertOrIgnore($activity_types);
        }

        

        
    }
}
