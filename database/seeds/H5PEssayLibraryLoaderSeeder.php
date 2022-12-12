<?php

use Illuminate\Database\Seeder;

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
            DB::table('h5p_libraries')->where($h5pEssayLibParams)->update(['preloaded_js' => 'scripts/essay.js,scripts/inputfield.js','preloaded_css' => 'scripts/essay.js',]);
        }
    }
}
