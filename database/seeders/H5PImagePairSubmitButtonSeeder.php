<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PImagePairSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pImagePairLibParams = ['name' => "H5P.ImagePair", "major_version" => 1, "minor_version" => 4];
        $h5pImagePairLib = DB::table('h5p_libraries')->where($h5pImagePairLibParams)->first();
        if ($h5pImagePairLib) {
            DB::table('h5p_libraries')->where($h5pImagePairLibParams)->update([
                'semantics' => $this->updatedSemantics(),
                'preloaded_js' => 'h5p-image-pair-stop-watch.js, h5p-image-pair.js, h5p-image-pair-card.js'
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
              {
                "label": "Task Description",
                "name": "taskDescription",
                "type": "text",
                "default": "Drag images from the left to match them with corresponding images on the right",
                "description": "A guide telling the user how to solve this task.",
                "importance": "high"
              },
              {
                "name": "cards",
                "type": "list",
                "widgets": [
                  {
                    "name": "VerticalTabs",
                    "label": "Default"
                  }
                ],
                "label": "Cards",
                "importance": "high",
                "entity": "card",
                "min": 2,
                "max": 100,
                "field": {
                  "type": "group",
                  "name": "card",
                  "label": "Card",
                  "importance": "high",
                  "fields": [
                    {
                      "name": "image",
                      "type": "image",
                      "label": "Image",
                      "importance": "high",
                      "ratio": 1
                    },
                    {
                      "name": "imageAlt",
                      "type": "text",
                      "label": "Alternative text for Image",
                      "importance": "high",
                      "description": "Describe what can be seen in the photo. The text is read by text-to-speech tools needed by visually impaired users."
                    },
                    {
                      "name": "match",
                      "type": "image",
                      "label": "Matching Image",
                      "importance": "low",
                      "optional": true,
                      "description": "An optional image to match against instead of using two cards with the same image.",
                      "ratio": 1
                    },
                    {
                      "name": "matchAlt",
                      "type": "text",
                      "label": "Alternative text for Matching Image",
                      "importance": "low",
                      "optional": true,
                      "description": "Describe what can be seen in the photo. The text is read by text-to-speech tools needed by visually impaired users."
                    }
                  ]
                }
              },
              {
                "name": "behaviour",
                "type": "group",
                "label": "Behavioural settings",
                "importance": "low",
                "description": "These options will let you control how the game behaves.",
                "optional": true,
                "fields": [
                  {
                    "name": "allowRetry",
                    "type": "boolean",
                    "label": "Add button for retrying when the game is over",
                    "importance": "low",
                    "default": true
                  }
                ]
              },
              {
                "name": "currikisettings",
                "type": "group",
                "label": "Curriki settings",
                "importance": "low",
                "description": "These options will let you control how the curriki studio behaves.",
                "optional": true,
                "fields": [
                  {
                    "label": "Do not Show Submit Button",
                    "importance": "low",
                    "name": "disableSubmitButton",
                    "type": "boolean",
                    "default": false,
                    "optional": true,
                    "description": "This option only applies to a standalone activity. The Submit button is required for grade passback to an LMS."
                  },
                  {
                    "label": "Placeholder",
                    "importance": "low",
                    "name": "placeholder",
                    "type": "boolean",
                    "default": false,
                    "optional": true,
                    "description": "This option is a place holder. will be used in future"
                  },
                  {
                    "label": "Curriki Localization",
                    "description": "Here you can edit settings or translate texts used in curriki settings",
                    "importance": "low",
                    "name": "currikil10n",
                    "type": "group",
                    "fields": [
                      {
                        "label": "Text for \"Submit\" button",
                        "name": "submitAnswer",
                        "type": "text",
                        "default": "Submit",
                        "optional": true
                      },
                      {
                        "label": "Text for \"Placeholder\" button",
                        "importance": "low",
                        "name": "placeholderButton",
                        "type": "text",
                        "default": "Placeholder",
                        "optional": true
                      }
                    ]
                  }
                ]
              },
              {
                "label": "Localization",
                "importance": "low",
                "name": "l10n",
                "type": "group",
                "common": true,
                "fields": [
                  {
                    "label": "Text for \"Check\" button",
                    "importance": "low",
                    "name": "checkAnswer",
                    "type": "text",
                    "default": "Check"
                  },
                  {
                    "label": "Text for \"Retry\" button",
                    "importance": "low",
                    "name": "tryAgain",
                    "type": "text",
                    "default": "Retry"
                  },
                  {
                    "label": "Text for \"ShowSolution\" button",
                    "importance": "low",
                    "name": "showSolution",
                    "type": "text",
                    "default": "Show Solution"
                  },
                  {
                    "label": "Feedback text",
                    "importance": "low",
                    "name": "score",
                    "type": "text",
                    "default": "You got @score of @total points",
                    "description": "Feedback text, variables available: @score and @total. Example: \'You got @score of @total possible points\'"
                  }
                ]
              }
            ]';
    }
}
