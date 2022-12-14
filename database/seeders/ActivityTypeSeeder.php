<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ActivityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $activities = [
            [
                'id' => 1,
                'title' => 'Audio changed',
                'order' => 0,
                'image' => '/storage/activity-types/audio.png',
            ],
            [
                'id' => 2,
                'title' => 'Informational',
                'order' => 0,
                'image' => '/storage/activity-types/informational.png',
            ],
            [
                'id' => 3,
                'title' => 'Photo / Images',
                'order' => 0,
                'image' => '/storage/activity-types/images.png',
            ],
            [
                'id' => 4,
                'title' => 'Multimedia',
                'order' => 0,
                'image' => '/storage/activity-types/multimedia.png',
            ],
            [
                'id' => 5,
                'title' => 'Questions',
                'order' => 0,
                'image' => '/storage/activity-types/questions.png',
            ],
            [
                'id' => 6,
                'title' => 'Mathematics',
                'order' => 0,
                'image' => '/storage/activity-types/mathematics.png',
            ]
        ];

        // Using updateOrInsert() is the recommended way.
        foreach ($activities as $activity) {
            DB::table('activity_types')->updateOrInsert(['id' => $activity['id']], $activity);
        }
    }
}
