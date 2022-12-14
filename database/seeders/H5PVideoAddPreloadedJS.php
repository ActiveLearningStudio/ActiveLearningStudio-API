<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PVideoAddPreloadedJS extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pVideoParams = ['name' => "H5P.Video", "major_version" => 1, "minor_version" => 5];
        $h5pVidoeLib = DB::table('h5p_libraries')->where($h5pVideoParams)->first();
        $requiredPreloadedJS = "scripts/youtube.js, scripts/panopto.js, scripts/html5.js, scripts/flash.js, scripts/brightcove.js, scripts/video.js";
        if ($h5pVidoeLib && $h5pVidoeLib->preloaded_js !== $requiredPreloadedJS) {
            DB::table('h5p_libraries')->where($h5pVideoParams)->update(['preloaded_js' => $requiredPreloadedJS]);
        }
    }
}
