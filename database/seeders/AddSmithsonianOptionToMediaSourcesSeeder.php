<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddSmithsonianOptionToMediaSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkAlreadyAdded = DB::table('media_sources')
                                ->where('name', 'Smithsonian')
                                ->where('media_type', 'Image')
                                ->first();
        if ( empty($checkAlreadyAdded) ) {
            DB::table('media_sources')->insert([
                'name' => 'Smithsonian',
                'media_type' => 'Image',
                'created_at' => now()           
            ]);
        }                                
    }
}
