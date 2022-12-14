<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PEssayLibraryLoaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pFibLibParams = ['name' => "H5P.Essay", "major_version" => 1, "minor_version" => 5];
        $h5pFibLib = DB::table('h5p_libraries')->where($h5pFibLibParams)->first();
        if ($h5pFibLib) {
            DB::table('h5p_libraries')->where($h5pFibLibParams)->update(['preloaded_js' => 'scripts/essay.js,scripts/inputfield.js','preloaded_css' => 'styles/essay.css']);
        }
    }
}
