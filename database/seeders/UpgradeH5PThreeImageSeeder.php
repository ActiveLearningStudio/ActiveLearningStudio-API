<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;
class UpgradeH5PThreeImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pThreeImageLibParams = ['name' => "H5P.ThreeImage", "major_version" => 0, "minor_version" => 5];
        $h5pThreeImageLib = DB::table('h5p_libraries')->where($h5pThreeImageLibParams)->first();

        if (empty($h5pThreeImageLib)) {
          $h5pThreeImageLibId = DB::table('h5p_libraries')->insertGetId([
                          'name' => 'H5P.ThreeImage',
                          'title' => 'Virtual Tour (360)',
                          'major_version' => 0,
                          'minor_version' => 5,
                          'patch_version' => 6,
                          'embed_types' => 'iframe',
                          'runnable' => 1,
                          'restricted' => 0,
                          'fullscreen' => 1,
                          'preloaded_js' => 'dist/h5p-three-image.js',
                          'preloaded_css' => 'dist/h5p-three-image.css',
                          'drop_library_css' => '',
                          'semantics' => $this->getSemantics(),
                          'tutorial_url' => ' ',
                          'has_icon' => 1
          ]);

          // insert dependent libraries
          $this->insertDependentLibraries($h5pThreeImageLibId);

          // insert libraries languages
          $this->insertLibrariesLanguages($h5pThreeImageLibId);
        }
    }

        /**
     * Insert Dependent Libraries
     * @param $h5pThreeImageLibId
     */
    private function insertDependentLibraries($h5pThreeImageLibId)
    {
        //Preloaded Dependencies
        $h5pThreeSixtyParams = ['name' => "H5P.ThreeSixty", "major_version" => 0, "minor_version" => 5];
        $h5pThreeSixtyLib = DB::table('h5p_libraries')->where($h5pThreeSixtyParams)->first();
        $h5pThreeSixtyLibId = $h5pThreeSixtyLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pThreeSixtyLibId,
            'dependency_type' => 'preloaded'
        ]);

        // Editor Dependencies
        $h5pH5PEditorThreeImageParams = ['name' => "H5PEditor.ThreeImage", "major_version" => 0, "minor_version" => 5];
        $h5pH5PEditorThreeImageLib = DB::table('h5p_libraries')->where($h5pH5PEditorThreeImageParams)->first();
        $h5pH5PEditorThreeImageId = $h5pH5PEditorThreeImageLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pEditorRadioGroupLibId,
            'dependency_type' => 'editor'
        ]);

        $h5pGoToSceneParams = ['name' => "H5P.GoToScene", "major_version" => 0, "minor_version" => 1];
        $h5pGoToSceneLib = DB::table('h5p_libraries')->where($h5pGoToSceneParams)->first();
        $h5pGoToSceneId = $h5pGoToSceneLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pGoToSceneId,
            'dependency_type' => 'editor'
        ]);
        
        $h5pEditorRadioGroupParams = ['name' => "H5PEditor.RadioGroup", "major_version" => 1, "minor_version" => 1];
        $h5pEditorRadioGroupLib = DB::table('h5p_libraries')->where($h5pEditorRadioGroupParams)->first();
        $h5pEditorRadioGroupLibId = $h5pEditorRadioGroupLib->id;
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pEditorRadioGroupLibId,
            'dependency_type' => 'editor'
        ]);

        $h5pEditorShowWhenParams = ['name' => "H5PEditor.ShowWhen", "major_version" => 1, "minor_version" => 0];
        $h5pEditorShowWhenLib = DB::table('h5p_libraries')->where($h5pEditorShowWhenParams)->first();
        $h5pEditorShowWhenLibId = $h5pEditorShowWhenLib->id;
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pEditorShowWhenLibId,
            'dependency_type' => 'editor'
        ]);

        $h5pEditorAudioRecorderParams = ['name' => "H5PEditor.AudioRecorder", "major_version" => 1, "minor_version" => 0];
        $h5pEditorAudioRecorderLib = DB::table('h5p_libraries')->where($h5pEditorAudioRecorderParams)->first();
        $h5pEditorAudioRecorderLibId = $h5pEditorAudioRecorderLib->id;
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pThreeImageLibId,
            'required_library_id' => $h5pEditorAudioRecorderLibId,
            'dependency_type' => 'editor'
        ]);
    }

    /**
     * Insert Library Language Semantics
     * @param $h5pThreeImageLibId
     */
    private function insertLibrariesLanguages($h5pThreeImageLibId)
    {

        // en.json
        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pThreeImageLibId,
            'language_code' => 'en',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Three Image Editor","fields":[{"label":"Scenes","entity":"Scene","field":{"fields":[{"label":"Scene type","options":[{"label":"360 image"},{"label":"Static image"}]},{"label":"Display \"Back\" button","description":"Display button for navigating back to your previous scene"},{},{"label":"Scene Title","description":"Used to identify the scene"},{"label":"Scene Background"},{"label":"Scene Description","description":"A text that can describe the scene for the end-user"},{},{"field":{"fields":[{"label":"Label","description":"If left blank no label will be displayed and we will try to use the title field for screen readers"},{"label":"Label Settings","fields":[{"label":"Label position","description":"Choose where the label should appear","options":[{"label":"Use behavioral setting"},{"label":"Right aligned"},{"label":"Left aligned"},{"label":"Top aligned"},{"label":"Bottom aligned"}]},{"label":"Display Label","description":"If hidden - labels will show only on mouse over","options":[{"label":"Use behavioral setting"},{"label":"Show"},{"label":"Hide"}]}]},{},{}]}},{"label":"Button style","description":"Decide how buttons pointing to this scene should look. For scenes that are static and does not lead to new scenes, we recommend the \"More information\" button.","options":[{"label":"New scene (arrow)"},{"label":"More information (plus)"}]},{"label":"Audio Track","description":"Add an audio track thats specific for this scene."}]}},{},{"label":"Audio track"}]},{"label":"Behavioral settings","description":"These options will let you control how the world behaves.","fields":[{"label":"Global Audio","description":"Add an audio track thats available for all of the scenes by default."},{"label":"Scene rendering quality","description":"Choose the amount of width and height segments used to render a scene. This directly affects the quality level of the scene, try increasing the quality if a scene looks \"blocky\" or \"waves\" are seen within the scenes. Note that higher quality rendering takes more time to load.","options":[{"label":"High Quality (128x128)"},{"label":"Medium Quality (64x64)"},{"label":"Low Quality (16x16)"}]},{"label":"Label settings","fields":[{"label":"Label position","description":"The default label position. The position may be overriden per interaction","options":[{"label":"Right aligned"},{"label":"Left aligned"},{"label":"Top aligned"},{"label":"Bottom aligned"}]},{"label":"Display Labels","description":"If unchecked - labels will show only on mouse over"}]}]},{"label":"Localize","fields":[{"label":"Aria label for content type","default":"Virtual Tour"},{"label":"Label for to play audio","default":"Play Audio Track"},{"label":"Label to pause audio","default":"Pause Audio Track"},{"label":"Title to scene dialog","default":"Scene Description"},{"label":"Label for button to reset camera","default":"Reset Camera"},{"label":"Label for the submit dialog button","default":"Submit Dialog"},{"label":"Label for the close dialog button","default":"Close Dialog"},{"label":"Aria label for the expand label button","default":"Expand the visual label"},{"label":"Label for when background image is loading in 360 scene","default":"Loading background image..."},{"label":"Label for when there is no content","default":"No content"}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
    }

    private function getSemantics() {
      return '[
        {
          "name": "threeImage",
          "type": "group",
          "widget": "threeImage",
          "label": "Three Image Editor",
          "importance": "high",
          "fields": [
            {
              "name": "scenes",
              "type": "list",
              "label": "Scenes",
              "entity": "Scene",
              "min": 0,
              "field": {
                "name": "scene",
                "type": "group",
                "fields": [
                  {
                    "name": "sceneType",
                    "type": "select",
                    "label": "Scene type",
                    "widget": "radioGroup",
                    "alignment": "horizontal",
                    "options": [
                      {
                        "value": "360",
                        "label": "360 image"
                      },
                      {
                        "value": "static",
                        "label": "Static image"
                      }
                    ],
                    "default": "360"
                  },
                  {
                    "name": "showBackButton",
                    "type": "boolean",
                    "label": "Display \"Back\" button",
                    "description": "Display button for navigating back to your previous scene",
                    "default": true,
                    "widget": "showWhen",
                    "showWhen": {
                      "rules": [
                        {
                          "field": "sceneType",
                          "equals": "static"
                        }
                      ]
                    }
                  },
                  {
                    "name": "sceneId",
                    "type": "number"
                  },
                  {
                    "name": "scenename",
                    "type": "text",
                    "label": "Scene Title",
                    "description": "Used to identify the scene"
                  },
                  {
                    "name": "scenesrc",
                    "type": "image",
                    "label": "Scene Background"
                  },
                  {
                    "name": "scenedescription",
                    "type": "text",
                    "widget": "html",
                    "label": "Scene Description",
                    "description": "A text that can describe the scene for the end-user",
                    "optional": true,
                    "tags": [
                      "p",
                      "br",
                      "strong",
                      "em",
                      "code"
                    ]
                  },
                  {
                    "name": "cameraStartPosition",
                    "type": "text"
                  },
                  {
                    "name": "interactions",
                    "type": "list",
                    "min": 0,
                    "field": {
                      "name": "interaction",
                      "type": "group",
                      "fields": [
                        {
                          "name": "labelText",
                          "type": "text",
                          "label": "Label",
                          "optional": true,
                          "description": "If left blank no label will be displayed and we will try to use the title field for screen readers"
                        },
                        {
                          "name": "label",
                          "type": "group",
                          "label": "Label Settings",
                          "importance": "low",
                          "fields": [
                            {
                              "name": "labelPosition",
                              "type": "select",
                              "label": "Label position",
                              "description": "Choose where the label should appear",
                              "options": [
                                {
                                  "value": "inherit",
                                  "label": "Use behavioral setting"
                                },
                                {
                                  "value": "right",
                                  "label": "Right aligned"
                                },
                                {
                                  "value": "left",
                                  "label": "Left aligned"
                                },
                                {
                                  "value": "top",
                                  "label": "Top aligned"
                                },
                                {
                                  "value": "bottom",
                                  "label": "Bottom aligned"
                                }
                              ],
                              "default": "inherit"
                            },
                            {
                              "name": "showLabel",
                              "type": "select",
                              "label": "Display Label",
                              "description": "If hidden - labels will show only on mouse over",
                              "options": [
                                {
                                  "value": "inherit",
                                  "label": "Use behavioral setting"
                                },
                                {
                                  "value": "show",
                                  "label": "Show"
                                },
                                {
                                  "value": "hide",
                                  "label": "Hide"
                                }
                              ],
                              "default": "inherit"
                            }
                          ]
                        },
                        {
                          "name": "action",
                          "type": "library",
                          "options": [
                            "H5P.GoToScene 0.1",
                            "H5P.AdvancedText 1.1",
                            "H5P.Image 1.1",
                            "H5P.Audio 1.5",
                            "H5P.Video 1.6",
                            "H5P.Summary 1.10",
                            "H5P.SingleChoiceSet 1.11"
                          ]
                        },
                        {
                          "name": "interactionpos",
                          "type": "text"
                        }
                      ]
                    }
                  },
                  {
                    "name": "iconType",
                    "type": "select",
                    "label": "Button style",
                    "description": "Decide how buttons pointing to this scene should look. For scenes that are static and does not lead to new scenes, we recommend the \"More information\" button.",
                    "widget": "radioGroup",
                    "alignment": "horizontal",
                    "options": [
                      {
                        "value": "arrow",
                        "label": "New scene (arrow)"
                      },
                      {
                        "value": "plus",
                        "label": "More information (plus)"
                      }
                    ],
                    "default": "arrow"
                  },
                  {
                    "name": "audio",
                    "type": "audio",
                    "label": "Audio Track",
                    "description": "Add an audio track thats specific for this scene.",
                    "optional": true,
                    "widgetExtensions": [
                      "AudioRecorder"
                    ]
                  }
                ]
              }
            },
            {
              "name": "startSceneId",
              "type": "number",
              "default": 0
            },
            {
              "name": "audio",
              "type": "audio",
              "label": "Audio track",
              "optional": true
            }
          ]
        },
        {
          "name": "behaviour",
          "type": "group",
          "label": "Behavioral settings",
          "importance": "low",
          "description": "These options will let you control how the world behaves.",
          "fields": [
            {
              "name": "audio",
              "type": "audio",
              "label": "Global Audio",
              "description": "Add an audio track thats available for all of the scenes by default.",
              "optional": true,
              "widgetExtensions": [
                "AudioRecorder"
              ]
            },
            {
              "name": "sceneRenderingQuality",
              "type": "select",
              "label": "Scene rendering quality",
              "description": "Choose the amount of width and height segments used to render a scene. This directly affects the quality level of the scene, try increasing the quality if a scene looks \"blocky\" or \"waves\" are seen within the scenes. Note that higher quality rendering takes more time to load.",
              "options": [
                {
                  "value": "high",
                  "label": "High Quality (128x128)"
                },
                {
                  "value": "medium",
                  "label": "Medium Quality (64x64)"
                },
                {
                  "value": "low",
                  "label": "Low Quality (16x16)"
                }
              ],
              "default": "high"
            },
            {
              "name": "label",
              "type": "group",
              "label": "Label settings",
              "importance": "low",
              "expanded": true,
              "fields": [
                {
                  "name": "labelPosition",
                  "type": "select",
                  "label": "Label position",
                  "description": "The default label position. The position may be overriden per interaction",
                  "options": [
                    {
                      "value": "right",
                      "label": "Right aligned"
                    },
                    {
                      "value": "left",
                      "label": "Left aligned"
                    },
                    {
                      "value": "top",
                      "label": "Top aligned"
                    },
                    {
                      "value": "bottom",
                      "label": "Bottom aligned"
                    }
                  ],
                  "default": "right"
                },
                {
                  "name": "showLabel",
                  "type": "boolean",
                  "label": "Display Labels",
                  "description": "If unchecked - labels will show only on mouse over",
                  "default": true
                }
              ]
            }
          ]
        },
        {
          "name": "l10n",
          "type": "group",
          "common": true,
          "label": "Localize",
          "fields": [
            {
              "name": "title",
              "type": "text",
              "label": "Aria label for content type",
              "default": "Virtual Tour"
            },
            {
              "name": "playAudioTrack",
              "type": "text",
              "label": "Label for to play audio",
              "default": "Play Audio Track"
            },
            {
              "name": "pauseAudioTrack",
              "type": "text",
              "label": "Label to pause audio",
              "default": "Pause Audio Track"
            },
            {
              "name": "sceneDescription",
              "type": "text",
              "label": "Title to scene dialog",
              "default": "Scene Description"
            },
            {
              "name": "resetCamera",
              "type": "text",
              "label": "Label for button to reset camera",
              "default": "Reset Camera"
            },
            {
              "name": "submitDialog",
              "type": "text",
              "label": "Label for the submit dialog button",
              "default": "Submit Dialog"
            },
            {
              "name": "closeDialog",
              "type": "text",
              "label": "Label for the close dialog button",
              "default": "Close Dialog"
            },
            {
              "name": "expandButtonAriaLabel",
              "type": "text",
              "label": "Aria label for the expand label button",
              "default": "Expand the visual label"
            },
            {
              "name": "backgroundLoading",
              "type": "text",
              "label": "Label for when background image is loading in 360 scene",
              "default": "Loading background image..."
            },
            {
              "name": "noContent",
              "type": "text",
              "label": "Label for when there is no content",
              "default": "No content"
            }
          ]
        }
      ]';
    }
}
