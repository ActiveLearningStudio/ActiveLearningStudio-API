<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateEpubDocumentLibraryV2Seeder extends Seeder
{
       /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pePubLibParams = ['name' => "H5PEditor.SelectEpubChapter", "major_version" => 1, "minor_version" => 0];
        $h5pePubLib = DB::table('h5p_libraries')->where($h5pePubLibParams)->first();

      if (!empty($h5pePubLib)) {
          DB::table('h5p_libraries')->where($h5pePubLibParams)->update([
              'preloaded_js' => 'epub-library/jszip.min.js,epub-library/epub.min.js,select-epub-chapter.js',
              'preloaded_css' => 'select-epub-chapter.css',
          ]);

      }

    }
}
