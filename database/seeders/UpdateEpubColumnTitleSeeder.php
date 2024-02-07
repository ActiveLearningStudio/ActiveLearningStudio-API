<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateEpubColumnTitleSeeder extends Seeder
{
       /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pePubColumnLibParams = ['name' => "H5P.EPubColumn", "major_version" => 1, "minor_version" => 0];
        $h5pePubColumnLib = DB::table('h5p_libraries')->where($h5pePubColumnLibParams)->first();

        if (!empty($h5pePubColumnLib)) {

            $title = 'CEE Interactive Book';
            DB::table('h5p_libraries')->where($h5pePubColumnLibParams)->update([
                'title' => $title
            ]);
            DB::table('activity_layouts')->where('h5pLib', 'H5P.EPubColumn 1.0')->update([
                'title' => $title
            ]);
            DB::table('activity_items')->where('h5pLib','H5P.EPubColumn 1.0')->update([
                'title' => $title
            ]);
        }

    }
}
