<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddH5PDropLibDependency extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pLibLibParams = ['library_id' => 1, 'required_library_id' => 13, 'dependency_type' => 'preloaded'];
        $videoAndTehterLib = DB::table('h5p_libraries_libraries')->where($h5pLibLibParams)->first();

        if (!$videoAndTehterLib) {
            DB::table('h5p_libraries_libraries')->insert($h5pLibLibParams);
        }
    }
}
