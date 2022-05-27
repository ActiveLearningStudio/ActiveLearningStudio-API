<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateH5PVideoLibraryFileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pVideoLibParams = ['name' => "H5P.Video", "major_version" => 1, "minor_version" => 5];
        $h5pVideoLib = DB::table('h5p_libraries')->where($h5pVideoLibParams)->first();
        if ($h5pVideoLib) {
            DB::table('h5p_libraries')->where($h5pVideoLibParams)->update([
                'preloaded_js' => $this->updatedPreLoadedJs()
            ]);
        }
    }

    private function updatedPreLoadedJs() {
        return 'scripts/youtube.js, scripts/panopto.js, scripts/html5.js, scripts/config.js, scripts/komodo.js, scripts/vimeo.js, scripts/flash.js, scripts/video.js, scripts/brightcove.js';
    }
}
