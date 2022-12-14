<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PQuestionnaireSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pQuestionnaireLibParams = ['name' => "H5P.Questionnaire", "major_version" => 1, "minor_version" => 3];
        $h5pQuestionnaireLib = DB::table('h5p_libraries')->where($h5pQuestionnaireLibParams)->first();
        if ($h5pQuestionnaireLib) {
            DB::table('h5p_libraries')->where($h5pQuestionnaireLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
          {
            "name": "questionnaireElements",
            "label": "Questionnaire elements",
            "importance": "high",
            "type": "list",
            "widgets": [
              {
                "name": "VerticalTabs",
                "label": "Default",
                "importance": "high"
              }
            ],
            "entity": "element",
            "min": 1,
            "defaultNum": 1,
            "field": {
              "name": "libraryGroup",
              "label": "Choose library",
              "importance": "high",
              "type": "group",
              "fields": [
                {
                  "name": "library",
                  "type": "library",
                  "label": "Library",
                  "importance": "high",
                  "description": "Choose a library",
                  "options": [
                    "H5P.OpenEndedQuestion 1.0",
                    "H5P.SimpleMultiChoice 1.1"
                  ]
                },
                {
                  "name": "requiredField",
                  "type": "boolean",
                  "label": "Required field",
                  "importance": "low",
                  "default": false
                }
              ]
            }
          },
          {
            "name": "successScreenOptions",
            "label": "Success screen options",
            "importance": "low",
            "type": "group",
            "fields": [
              {
                "name": "enableSuccessScreen",
                "label": "Enable success screen",
                "importance": "low",
                "type": "boolean",
                "default": true
              },
              {
                "name": "successScreenImage",
                "label": "Add success screen image",
                "importance": "low",
                "type": "group",
                "fields": [
                  {
                    "name": "successScreenImage",
                    "label": "Replace success icon with image",
                    "importance": "low",
                    "type": "library",
                    "optional": true,
                    "options": [
                      "H5P.Image 1.1"
                    ]
                  }
                ]
              },
              {
                "name": "successMessage",
                "type": "text",
                "label": "Text to display on submit",
                "importance": "low",
                "default": "You have completed the questionnaire."
              }
            ]
          },
          {
            "name": "uiElements",
            "label": "UI Elements",
            "importance": "low",
            "type": "group",
            "fields": [
              {
                "name": "buttonLabels",
                "type": "group",
                "label": "Button labels",
                "importance": "low",
                "fields": [
                  {
                    "name": "prevLabel",
                    "type": "text",
                    "label": "Previous button label",
                    "importance": "low",
                    "default": "Back"
                  },
                  {
                    "name": "continueLabel",
                    "type": "text",
                    "label": "Continue button label",
                    "importance": "low",
                    "default": "Continue"
                  },
                  {
                    "name": "nextLabel",
                    "type": "text",
                    "label": "Next button label",
                    "importance": "low",
                    "default": "Next"
                  },
                  {
                    "name": "submitLabel",
                    "type": "text",
                    "label": "Submit button label",
                    "importance": "low",
                    "default": "Submit"
                  }
                ]
              },
              {
                "name": "accessibility",
                "type": "group",
                "label": "Accessibility",
                "importance": "low",
                "fields": [
                  {
                    "name": "requiredTextExitLabel",
                    "type": "text",
                    "label": "Required message exit button label",
                    "importance": "low",
                    "default": "Close error message"
                  },
                  {
                    "name": "progressBarText",
                    "type": "text",
                    "label": "Progress bar text",
                    "importance": "low",
                    "default": "Question %current of %max",
                    "description": "Used to tell assistive technologies what question it is. Variables: [ %current, %max ]"
                  }
                ]
              },
              {
                "name": "requiredMessage",
                "type": "text",
                "label": "Required message",
                "importance": "low",
                "default": "This question requires an answer",
                "description": "Will display if this field is unanswered and required by a wrapper content type"
              },
              {
                "name": "requiredText",
                "type": "text",
                "label": "Required symbol text",
                "importance": "low",
                "default": "required",
                "description": "Text that will accompany an asterisk to signal that a question is required"
              },
              {
                "name": "submitScreenTitle",
                "type": "text",
                "label": "Title for the submit screen",
                "importance": "low",
                "default": "You successfully answered all of the questions"
              },
              {
                "name": "submitScreenSubtitle",
                "type": "text",
                "label": "Subtitle for the submit screen",
                "importance": "low",
                "default": "Click below to submit your answers"
              }
            ]
          },{
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
                    "name": "submitLabel",
                    "importance": "low",
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
          }
        ]';
    }
}
