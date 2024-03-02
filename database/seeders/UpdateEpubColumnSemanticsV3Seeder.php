<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateEpubColumnSemanticsV3Seeder extends Seeder
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
            "H5P.BrightcoveInteractiveVideo 1.1",
            "H5P.QuestionSet 1.20",
            "H5P.Questionnaire 1.2",
            "H5P.EPubDocument 1.0",
            "H5P.Image 1.1",
            "H5P.IFrameEmbed 1.0"
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
