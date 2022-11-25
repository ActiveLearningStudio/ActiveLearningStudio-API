<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateH5pAudioTitleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pAudioLibParams = ['name' => "H5P.Audio", "major_version" => 1, "minor_version" => 5];
        $h5pAudioLib = DB::table('h5p_libraries')->where($h5pAudioLibParams)->first();
        if ($h5pAudioLib) {
            DB::table('h5p_libraries')->where($h5pAudioLibParams)->update([
                'title' => 'Audio'
            ]);
        }
    }
}
