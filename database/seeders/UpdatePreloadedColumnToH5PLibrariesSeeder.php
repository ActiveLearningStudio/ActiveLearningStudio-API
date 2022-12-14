<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdatePreloadedColumnToH5PLibrariesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('h5p_libraries')
            ->where('name', 'H5P.CurrikiInteractiveVideo')
            ->update([
                'preloaded_js' => 'dist/h5p-interactive-video.js',
                'preloaded_css' => 'dist/h5p-interactive-video.css'
            ]);
    }
}
