<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateEpubColumnSemanticsSeeder extends Seeder
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
            DB::table('h5p_libraries')->where($h5pePubColumnLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }

        $title = 'CEE-Epub';
        $h5pePubLibParams = ['name' => "H5P.EPubDocument", "major_version" => 1, "minor_version" => 0];
        $h5pePubLib = DB::table('h5p_libraries')->where($h5pePubLibParams)->first();

        if(!empty($h5pePubLib)) {
            DB::table('h5p_libraries')->where($h5pePubLibParams)->update([
                'title' => $title
            ]);
        }


    }

    private function updatedSemantics() {
        return '[
  {
    "name": "content",
    "label": "List of Column Content",
    "importance": "high",
    "type": "list",
    "min": 1,
    "entity": "content",
    "field": {
      "name": "content",
      "type": "group",
      "fields": [
        {
          "name": "content",
          "type": "library",
          "importance": "high",
          "label": "Content",
          "options": [
            "H5P.InteractiveVideo 1.24",
            "H5P.QuestionSet 1.20",
            "H5P.Questionnaire 1.2",
            "H5P.EPubDocument 1.0"
          ]
        },
        {
          "name": "useSeparator",
          "type": "select",
          "importance": "low",
          "label": "Separate content with a horizontal ruler",
          "default": "auto",
          "options": [
            {
              "value": "auto",
              "label": "Automatic (default)"
            },
            {
              "value": "disabled",
              "label": "Never use ruler above"
            },
            {
              "value": "enabled",
              "label": "Always use ruler above"
            }
          ]
        }
      ]
    }
  }
]';
    }
}
