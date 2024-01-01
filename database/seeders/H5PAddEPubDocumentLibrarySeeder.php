<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PAddEPubDocumentLibrarySeeder extends Seeder
{
       /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pePubLibParams = ['name' => "H5P.EPubDocument", "major_version" => 1, "minor_version" => 0];
        $h5pePubLib = DB::table('h5p_libraries')->where($h5pePubLibParams)->first();

      if (empty($h5pePubLib)) {
          $h5pFibLibId = DB::table('h5p_libraries')->insertGetId([
                          'name' => 'H5P.EPubDocument',
                          'title' => 'ePub Document',
                          'major_version' => 1,
                          'minor_version' => 0,
                          'patch_version' => 0,
                          'embed_types' => 'iframe',
                          'runnable' => 1,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'epub-library/jszip.min.js,epub-library/epub.min.js,epub-document.js',
                          'preloaded_css' => 'epub-document.css',
                          'drop_library_css' => '',
                          'semantics' => $this->getSemantics(),
                          'tutorial_url' => ' ',
                          'has_icon' => 1
          ]);
      }

    }


    private function getSemantics() {
        return '[
  {
    "name": "file",
    "type": "file",
    "label": "ePub Document(.epub file)",
    "importance": "high",
    "disableCopyright": true
  }
]
';
    }
}
