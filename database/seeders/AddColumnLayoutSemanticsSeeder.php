<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddColumnLayoutSemanticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pFibLibParams = ['name' => "H5P.Column", "major_version" => 1, "minor_version" => 15];
        $h5pFibLib = DB::table('h5p_libraries')->where($h5pFibLibParams)->first();

        if (empty($h5pFibLib)) {
            $h5pFibLibId = DB::table('h5p_libraries')->insertGetId([
                            'name' => 'H5P.Column',
                            'title' => 'Column',
                            'major_version' => 1,
                            'minor_version' => 15,
                            'patch_version' => 2,
                            'embed_types' => 'iframe',
                            'runnable' => 1,
                            'restricted' => 0,
                            'fullscreen' => 0,
                            'preloaded_js' => 'scripts/h5p-column.js',
                            'preloaded_css' => 'styles/h5p-column.css,styles/custom-column-layout.css',
                            'drop_library_css' => '',
                            'semantics' => $this->getSemantics(),
                            'tutorial_url' => ' ',
                            'has_icon' => 1
            ]);
            // insert libraries languages
            $this->insertLibrariesLanguages($h5pFibLibId);
        }
    }

    /**
     * Insert Library Language Semantics
     * @param $h5pFibLibId
     */
    private function insertLibrariesLanguages($h5pFibLibId)
    {
        // en.json
         DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pFibLibId,
            'language_code' => 'en',
            'translation' => json_encode(json_decode('{
                "semantics": [
                  {
                    "label": "List of Column Content",
                    "entity": "content",
                    "field": {
                      "fields": [
                        {
                          "label": "Content"
                        },
                        {
                          "label": "Separate content with a horizontal ruler",
                          "options": [
                            {
                              "label": "Automatic (default)"
                            },
                            {
                              "label": "Never use ruler above"
                            },
                            {
                              "label": "Always use ruler above"
                            }
                          ]
                        }
                      ]
                    }
                  }
                ]
              }
              '), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
    }

    private function getSemantics() {
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
                      "H5P.Accordion 1.0",
                      "H5P.Agamotto 1.5",
                      "H5P.Audio 1.5",
                      "H5P.AudioRecorder 1.0",
                      "H5P.Blanks 1.14",
                      "H5P.Chart 1.2",
                      "H5P.Collage 0.3",
                      "H5P.CoursePresentation 1.24",
                      "H5P.Dialogcards 1.9",
                      "H5P.DocumentationTool 1.8",
                      "H5P.DragQuestion 1.14",
                      "H5P.DragText 1.10",
                      "H5P.Essay 1.5",
                      "H5P.GuessTheAnswer 1.5",
                      "H5P.Table 1.1",
                      "H5P.AdvancedText 1.1",
                      "H5P.IFrameEmbed 1.0",
                      "H5P.Image 1.1",
                      "H5P.ImageHotspots 1.10",
                      "H5P.ImageHotspotQuestion 1.8",
                      "H5P.ImageSlider 1.1",
                      "H5P.InteractiveVideo 1.24",
                      "H5P.Link 1.3",
                      "H5P.MarkTheWords 1.11",
                      "H5P.MemoryGame 1.3",
                      "H5P.MultiChoice 1.16",
                      "H5P.Questionnaire 1.3",
                      "H5P.QuestionSet 1.20",
                      "H5P.SingleChoiceSet 1.11",
                      "H5P.Summary 1.10",
                      "H5P.Timeline 1.1",
                      "H5P.TrueFalse 1.8",
                      "H5P.Video 1.6"
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
