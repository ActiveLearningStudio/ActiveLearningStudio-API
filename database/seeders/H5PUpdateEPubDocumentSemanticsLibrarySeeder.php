<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PUpdateEPubDocumentSemanticsLibrarySeeder extends Seeder
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
          DB::table('h5p_libraries')->where($h5pePubLib)->update(['semantics' => $this->updatedSemantics()]);


      }

    }

    private function updatedSemantics() {
        return '[
  {
    "name": "file",
    "type": "file",
    "label": "ePub Document(.epub file)",
    "importance": "high",
    "disableCopyright": true,
    "mimes": [
      "application/epub+zip",
      "application/zip"
    ]
  },
  {
    "name": "chapter",
    "type": "select",
    "widget": "selectEpubChapter",
    "label": "Chapter",
    "importance": "high"
  }
]
';
    }
}
