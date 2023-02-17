<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DefaultMediaSource extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for video sources
        DB::table('media_sources')->insertOrIgnore([
            'name' => 'My device',
            'media_type' => 'Video',
            'created_at' => now()
        ]);

        DB::table('media_sources')->insertOrIgnore([
            'name' => 'YouTube',
            'media_type' => 'Video',
            'created_at' => now()
        ]);

        DB::table('media_sources')->insertOrIgnore([
            'name' => 'Kaltura',
            'media_type' => 'Video',
            'created_at' => now()
        ]);

        DB::table('media_sources')->insertOrIgnore([
            'name' => 'Safari Montage',
            'media_type' => 'Video',
            'created_at' => now()
        ]);

        DB::table('media_sources')->insertOrIgnore([
            'name' => 'BrightCove',
            'media_type' => 'Video',
            'created_at' => now()
        ]);

        DB::table('media_sources')->insertOrIgnore([
            'name' => 'Vimeo',
            'media_type' => 'Video',
            'created_at' => now()
        ]);

        // for image sources
        DB::table('media_sources')->insertOrIgnore([
            'name' => 'My device',
            'media_type' => 'Image',
            'created_at' => now()
        ]);

        DB::table('media_sources')->insertOrIgnore([
            'name' => 'Pexels',
            'media_type' => 'Image',
            'created_at' => now()
        ]);

        DB::table('media_sources')->insertOrIgnore([
            'name' => 'Safari Montage',
            'media_type' => 'Image',
            'created_at' => now()
        ]);

    }
}
