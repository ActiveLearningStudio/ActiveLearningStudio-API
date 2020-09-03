<?php

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
        DB::table('activity_types')->insert([
            'title' => 'Audio',
            'order' => 0,
            'image' => '/storage/activity-types/audio.png',
        ]);

        DB::table('activity_types')->insert([
            'title' => 'Informational',
            'order' => 0,
            'image' => '/storage/activity-types/informational.png',
        ]);

        DB::table('activity_types')->insert([
            'title' => 'Photo / Images',
            'order' => 0,
            'image' => '/storage/activity-types/images.png',
        ]);

        DB::table('activity_types')->insert([
            'title' => 'Multimedia',
            'order' => 0,
            'image' => '/storage/activity-types/multimedia.png',
        ]);

        DB::table('activity_types')->insert([
            'title' => 'Questions',
            'order' => 0,
            'image' => '/storage/activity-types/questions.png',
        ]);

        DB::table('activity_types')->insert([
            'title' => 'Mathematics',
            'order' => 0,
            'image' => '/storage/activity-types/mathematics.png',
        ]);
    }
}
