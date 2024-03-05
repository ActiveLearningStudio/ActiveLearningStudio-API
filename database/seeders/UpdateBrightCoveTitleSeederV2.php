<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateBrightCoveTitleSeederV2 extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pBrightcoveLibParams = ['name' => "H5P.BrightcoveInteractiveVideo", "major_version" => 1, "minor_version" => 2];
        $h5pBrightcoveLib = DB::table('h5p_libraries')->where($h5pBrightcoveLibParams)->first();
        if ($h5pBrightcoveLib) {
            DB::table('h5p_libraries')->where($h5pBrightcoveLibParams)->update([
                'title' => 'Media Catalog'
            ]);
        }



    }
}
