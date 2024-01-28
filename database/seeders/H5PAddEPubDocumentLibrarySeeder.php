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

          $h5pEpubSelectChapterId = DB::table('h5p_libraries')->insertGetId([
              'name' => 'H5PEditor.SelectEpubChapter',
              'title' => 'Select Epub Chapter',
              'major_version' => 1,
              'minor_version' => 0,
              'patch_version' => 0,
              'embed_types' => '',
              'runnable' => 0,
              'restricted' => 0,
              'fullscreen' => 0,
              'preloaded_js' => 'select-epub-chapter.js',
              'preloaded_css' => 'select-epub-chapter.css',
              'drop_library_css' => '',
              'semantics' => '',
              'tutorial_url' => ' ',
              'has_icon' => 0
          ]);


          $h5pEpubDocumentLibId = DB::table('h5p_libraries')->insertGetId([
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

          $this->insertDependentLibraries($h5pEpubDocumentLibId, $h5pEpubSelectChapterId);

      }

    }

    /**
     * Insert Dependent Libraries
     * @param $h5pEpubDocumentLibId
     * @param $h5pEpubSelectChapterId
     */
    private function insertDependentLibraries($h5pEpubDocumentLibId, $h5pEpubSelectChapterId)
    {


        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pEpubDocumentLibId,
            'required_library_id' => $h5pEpubSelectChapterId,
            'dependency_type' => 'editor'
        ]);

    }


    private function getSemantics() {
        return '[
  {
    "name": "file",
    "type": "file",
    "label": "ePub Document(.epub file)",
    "importance": "high",
    "disableCopyright": true,
    "mimes": [
      "application/epub+zip"
    ]
  },
  {
    "name": "chapter",
    "type": "select",
    "widget": "selectEpubChapter",
    "label": "Chapter",
    "importance": "high"
  }
]';
    }
}
