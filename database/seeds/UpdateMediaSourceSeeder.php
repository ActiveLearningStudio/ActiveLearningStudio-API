<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateMediaSourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('media_sources')->insertOrIgnore([
            'name' => 'Komodo',
            'media_type' => 'Video',
            'created_at' => now()
        ]);
    }
}
