<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class H5pUpgradeEditorThreeImagePreloadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pThreeImageLibParams = ['name' => "H5PEditor.ThreeImage", "major_version" => 0, "minor_version" => 5];
        $h5pThreeImage = DB::table('h5p_libraries')->where($h5pThreeImageLibParams)->first();
        if($h5pThreeImage) {
            $h5pThreeImageParams = ['name' => "H5P.ThreeImage", "major_version" => 0, "minor_version" => 5];
            $h5pThreeImageLib = DB::table('h5p_libraries')->where($h5pThreeImageParams)->first();
            $h5pThreeImageLibId = $h5pThreeImageLib->id;
    
            DB::table('h5p_libraries_libraries')->insert([
                'library_id' => $h5pThreeImageLibId,
                'required_library_id' => $h5pThreeImage->id,
                'dependency_type' => 'preloaded'
            ]);
        }
    }
}
