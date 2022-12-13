<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddQuestionSetOneTwentySemanticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pFibLibParams = ['name' => "H5P.QuestionSet", "major_version" => 1, "minor_version" => 20];
        $h5pFibLib = DB::table('h5p_libraries')->where($h5pFibLibParams)->first();
        if (empty($h5pFibLib)) {
            $h5pFibLibId = DB::table('h5p_libraries')->insertGetId([
                            'name' => 'H5P.QuestionSet',
                            'title' => 'Question Set',
                            'major_version' => 1,
                            'minor_version' => 20,
                            'patch_version' => 7,
                            'embed_types' => 'iframe',
                            'runnable' => 1,
                            'restricted' => 0,
                            'fullscreen' => 0,
                            'preloaded_js' => 'js/questionset.js',
                            'preloaded_css' => 'css/questionset.css',
                            'drop_library_css' => '',
                            'semantics' => $this->getSemantics(),
                            'tutorial_url' => ' ',
                            'has_icon' => 1
            ]);

            // insert dependent libraries
            $this->insertDependentLibraries($h5pFibLibId);

            // insert libraries languages
            $this->insertLibrariesLanguages($h5pFibLibId);
        }
    }

                /**
     * Insert Dependent Libraries
     * @param $h5pFibLibId
     */
    private function insertDependentLibraries($h5pFibLibId)
    {
        //Preloaded Dependencies
        $h5pFontAwesomeParams = ['name' => "FontAwesome", "major_version" => 4, "minor_version" => 5];
        $h5pFontAwesomeLib = DB::table('h5p_libraries')->where($h5pFontAwesomeParams)->first();
        $h5pFontAwesomeLibId = $h5pFontAwesomeLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pFontAwesomeLibId,
            'dependency_type' => 'preloaded'
        ]);

         //Preloaded Dependencies
         $h5pEmbeddedJSParams = ['name' => "EmbeddedJS", "major_version" => 1, "minor_version" => 0];
         $h5pEmbeddedJSParamsLib = DB::table('h5p_libraries')->where($h5pEmbeddedJSParams)->first();
         $h5pEmbeddedJSParamsLibId = $h5pEmbeddedJSParamsLib->id;
 
         DB::table('h5p_libraries_libraries')->insert([
             'library_id' => $h5pFibLibId,
             'required_library_id' => $h5pEmbeddedJSParamsLibId,
             'dependency_type' => 'preloaded'
         ]);

          //Preloaded Dependencies
          $h5pVideoParams = ['name' => "H5P.Video", "major_version" => 1, "minor_version" => 6];
          $h5pVideoParamsLib = DB::table('h5p_libraries')->where($h5pVideoParams)->first();
          $h5pVideoParamsLibId = $h5pVideoParamsLib->id;
  
          DB::table('h5p_libraries_libraries')->insert([
              'library_id' => $h5pFibLibId,
              'required_library_id' => $h5pVideoParamsLibId,
              'dependency_type' => 'preloaded'
          ]);

        //Preloaded Dependencies
          $h5pJoubelUIParams = ['name' => "H5P.JoubelUI", "major_version" => 1, "minor_version" => 3];
          $h5pJoubelUIParamsLib = DB::table('h5p_libraries')->where($h5pJoubelUIParams)->first();
          $h5pJoubelUIParamsLibId = $h5pJoubelUIParamsLib->id;
  
          DB::table('h5p_libraries_libraries')->insert([
              'library_id' => $h5pFibLibId,
              'required_library_id' => $h5pJoubelUIParamsLibId,
              'dependency_type' => 'preloaded'
          ]);


        // Editor Dependencies

        $h5pEditorShowWhenParams = ['name' => "H5PEditor.ShowWhen", "major_version" => 1, "minor_version" => 0];
        $h5pEditorShowWhenLib = DB::table('h5p_libraries')->where($h5pEditorShowWhenParams)->first();
        $h5pEditorShowWhenLibId = $h5pEditorShowWhenLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pEditorShowWhenLibId,
            'dependency_type' => 'editor'
        ]);

        // Editor Dependencies

        $h5pEditorRangeListWhenParams = ['name' => "H5PEditor.RangeList", "major_version" => 1, "minor_version" => 0];
        $h5pEditorRangeListWhenLib = DB::table('h5p_libraries')->where($h5pEditorRangeListWhenParams)->first();
        $h5pEditorRangeListWhenLibId = $h5pEditorRangeListWhenLib->id;

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pFibLibId,
            'required_library_id' => $h5pEditorRangeListWhenLibId,
            'dependency_type' => 'editor'
        ]);

         // Editor Dependencies

         $h5pVerticalTabsParams = ['name' => "H5PEditor.VerticalTabs", "major_version" => 1, "minor_version" => 3];
         $h5pVerticalTabsLib = DB::table('h5p_libraries')->where($h5pVerticalTabsParams)->first();
         $h5pVerticalTabsLibId = $h5pVerticalTabsLib->id;
 
         DB::table('h5p_libraries_libraries')->insert([
             'library_id' => $h5pFibLibId,
             'required_library_id' => $h5pVerticalTabsLibId,
             'dependency_type' => 'editor'
         ]);

           // Editor Dependencies

           $h5pQuestionSetTextualEditorParams = ['name' => "H5PEditor.QuestionSetTextualEditor", "major_version" => 1, "minor_version" => 3];
           $h5pQuestionSetTextualEditorLib = DB::table('h5p_libraries')->where($h5pQuestionSetTextualEditorParams)->first();
           $h5pQuestionSetTextualEditorLibId = $h5pQuestionSetTextualEditorLib->id;
   
           DB::table('h5p_libraries_libraries')->insert([
               'library_id' => $h5pFibLibId,
               'required_library_id' => $h5pQuestionSetTextualEditorLibId,
               'dependency_type' => 'editor'
           ]);

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
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Quiz introduction","fields":[{"label":"Display introduction"},{"label":"Title","description":"This title will be displayed above the introduction text."},{"label":"Introduction text","description":"This text will be displayed before the quiz starts."},{"label":"Start button text","default":"Start Quiz"},{"label":"Background image","description":"An optional background image for the introduction."}]},{"label":"Background image","description":"An optional background image for the Question set."},{"label":"Progress indicator","description":"Question set progress indicator style.","options":[{"label":"Textual"},{"label":"Dots"}]},{"label":"Pass percentage","description":"Percentage of Total score required for passing the quiz."},{"label":"Questions","widgets":[{"label":"Default"},{"label":"Textual"}],"entity":"question","field":{"label":"Question type","description":"Library for this question."}},{"label":"Interface texts in quiz","fields":[{"label":"Back button","default":"Previous question"},{"label":"Next button","default":"Next question"},{"label":"Finish button","default":"Finish"},{"label":"Submit button","default":"Submit"},{"label":"Progress text","description":"Text used if textual progress is selected.","default":"Question: @current of @total questions"},{"label":"Label for jumping to a certain question","description":"You must use the placeholder %d instead of the question number, and %total instead of total amount of questions.","default":"Question %d of %total"},{"label":"Copyright dialog question label","default":"Question"},{"label":"Readspeaker progress","description":"May use @current and @total question variables","default":"Question @current of @total"},{"label":"Unanswered question text","default":"Unanswered"},{"label":"Answered question text","default":"Answered"},{"label":"Current question text","default":"Current question"},{"label":"Navigation label for readspeakers","default":"Questions"}]},{"label":"Disable backwards navigation","description":"This option will only allow you to move forward in Question Set"},{"label":"Randomize questions","description":"Enable to randomize the order of questions on display."},{"label":"Number of questions to be shown:","description":"Create a randomized batch of questions from the total."},{"label":"Quiz finished","fields":[{"label":"Display results"},{"label":"Display solution button"},{"label":"Display retry button"},{"label":"No results message","description":"Text displayed on end page when \"Display results\" is disabled","default":"Finished"},{"label":"Feedback heading","default":"Your result:","description":"This heading will be displayed at the end of the quiz when the user has answered all questions."},{"label":"Score announcer","description":"This label will be used for announcing the final score to the user on the end screen","default":"You got @finals out of @totals points"},{"label":"Overall Feedback","fields":[{"widgets":[{"label":"Default"}],"label":"Define custom feedback for any score range","description":"Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!","entity":"range","field":{"fields":[{"label":"Score Range"},{},{"label":"Feedback for defined score range","placeholder":"Fill in the feedback"}]}}]},{"label":"Old Feedback","fields":[{"label":"Quiz passed greeting","description":"This text will be displayed above the score if the user has successfully passed the quiz."},{"label":"Passed comment","description":"This comment will be displayed after the score if the user has successfully passed the quiz."},{"label":"Quiz failed title","description":"This text will be displayed above the score if the user has failed the quiz."},{"label":"Failed comment","description":"This comment will be displayed after the score if the user has failed the quiz."}]},{"label":"Solution button label","default":"Show solution","description":"Text for the solution button."},{"label":"Retry button label","default":"Retry","description":"Text for the retry button."},{"label":"Finish button text","default":"Finish"},{"label":"Submit button text","default":"Submit"},{"label":"Display video before quiz results"},{"label":"Enable skip video button"},{"label":"Skip video button label","default":"Skip video"},{"label":"Passed video","description":"This video will be played if the user successfully passed the quiz."},{"label":"Fail video","description":"This video will be played if the user fails the quiz."}]},{"label":"Settings for \"Check\", \"Show solution\" and \"Retry\"","fields":[{"label":"Show \"Check\" buttons","description":"This option determines if the \"Check\" button will be shown for all questions."},{"label":"Override \"Show Solution\" button","description":"This option determines if the \"Show Solution\" button will be shown for all questions, disabled for all or configured for each question individually.","options":[{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Override \"Retry\" button","description":"This option determines if the \"Retry\" button will be shown for all questions, disabled for all or configured for each question individually.","options":[{"label":"Enabled"},{"label":"Disabled"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);
    }

    private function getSemantics() {
        return '[
            {
              "name": "introPage",
              "type": "group",
              "label": "Quiz introduction",
              "importance": "medium",
              "fields": [
                {
                  "name": "showIntroPage",
                  "type": "boolean",
                  "label": "Display introduction",
                  "importance": "low"
                },
                {
                  "name": "title",
                  "type": "text",
                  "label": "Title",
                  "importance": "high",
                  "optional": true,
                  "description": "This title will be displayed above the introduction text.",
                  "tags": [
                    "sub",
                    "sup",
                    "strong",
                    "em",
                    "code"
                  ]
                },
                {
                  "name": "introduction",
                  "type": "text",
                  "widget": "html",
                  "label": "Introduction text",
                  "importance": "medium",
                  "optional": true,
                  "description": "This text will be displayed before the quiz starts.",
                  "enterMode": "p",
                  "tags": [
                    "sub",
                    "sup",
                    "strong",
                    "em",
                    "p",
                    "code"
                  ]
                },
                {
                  "name": "startButtonText",
                  "type": "text",
                  "label": "Start button text",
                  "importance": "low",
                  "optional": true,
                  "default": "Start Quiz"
                },
                {
                  "name": "backgroundImage",
                  "type": "image",
                  "label": "Background image",
                  "importance": "low",
                  "optional": true,
                  "description": "An optional background image for the introduction."
                }
              ]
            },
            {
              "name": "backgroundImage",
              "type": "image",
              "label": "Background image",
              "importance": "low",
              "optional": true,
              "description": "An optional background image for the Question set."
            },
            {
              "name": "progressType",
              "type": "select",
              "label": "Progress indicator",
              "importance": "low",
              "description": "Question set progress indicator style.",
              "options": [
                {
                  "value": "textual",
                  "label": "Textual"
                },
                {
                  "value": "dots",
                  "label": "Dots"
                }
              ],
              "default": "dots"
            },
            {
              "name": "passPercentage",
              "type": "number",
              "label": "Pass percentage",
              "importance": "low",
              "description": "Percentage of Total score required for passing the quiz.",
              "min": 0,
              "max": 100,
              "step": 1,
              "default": 50
            },
            {
              "name": "questions",
              "label": "Questions",
              "importance": "high",
              "type": "list",
              "widgets": [
                {
                  "name": "VerticalTabs",
                  "label": "Default"
                },
                {
                  "name": "QuestionSetTextualEditor",
                  "label": "Textual"
                }
              ],
              "min": 1,
              "entity": "question",
              "field": {
                "name": "question",
                "type": "library",
                "label": "Question type",
                "importance": "high",
                "description": "Library for this question.",
                "options": [
                  "H5P.MultiChoice 1.16",
                  "H5P.DragQuestion 1.14",
                  "H5P.Blanks 1.14",
                  "H5P.MarkTheWords 1.11",
                  "H5P.DragText 1.10",
                  "H5P.TrueFalse 1.8",
                  "H5P.Essay 1.5"
                ]
              }
            },
            {
              "name": "texts",
              "type": "group",
              "label": "Interface texts in quiz",
              "importance": "low",
              "common": true,
              "fields": [
                {
                  "name": "prevButton",
                  "type": "text",
                  "label": "Back button",
                  "importance": "low",
                  "default": "Previous question"
                },
                {
                  "name": "nextButton",
                  "type": "text",
                  "label": "Next button",
                  "importance": "low",
                  "default": "Next question"
                },
                {
                  "name": "finishButton",
                  "type": "text",
                  "label": "Finish button",
                  "importance": "low",
                  "default": "Finish"
                },
                {
                  "name": "submitButton",
                  "type": "text",
                  "label": "Submit button",
                  "importance": "low",
                  "default": "Submit"
                },
                {
                  "name": "textualProgress",
                  "type": "text",
                  "label": "Progress text",
                  "importance": "low",
                  "description": "Text used if textual progress is selected.",
                  "default": "Question: @current of @total questions",
                  "tags": [
                    "strong",
                    "em",
                    "code"
                  ]
                },
                {
                  "name": "jumpToQuestion",
                  "type": "text",
                  "label": "Label for jumping to a certain question",
                  "importance": "low",
                  "description": "You must use the placeholder %d instead of the question number, and %total instead of total amount of questions.",
                  "default": "Question %d of %total"
                },
                {
                  "name": "questionLabel",
                  "type": "text",
                  "label": "Copyright dialog question label",
                  "importance": "low",
                  "default": "Question"
                },
                {
                  "name": "readSpeakerProgress",
                  "type": "text",
                  "label": "Readspeaker progress",
                  "importance": "low",
                  "description": "May use @current and @total question variables",
                  "default": "Question @current of @total"
                },
                {
                  "name": "unansweredText",
                  "type": "text",
                  "label": "Unanswered question text",
                  "importance": "low",
                  "default": "Unanswered"
                },
                {
                  "name": "answeredText",
                  "type": "text",
                  "label": "Answered question text",
                  "importance": "low",
                  "default": "Answered"
                },
                {
                  "name": "currentQuestionText",
                  "type": "text",
                  "label": "Current question text",
                  "importance": "low",
                  "default": "Current question"
                },
                {
                  "name": "navigationLabel",
                  "type": "text",
                  "label": "Navigation label for readspeakers",
                  "importance": "low",
                  "default": "Questions"
                }
              ]
            },
            {
              "name": "disableBackwardsNavigation",
              "type": "boolean",
              "label": "Disable backwards navigation",
              "importance": "low",
              "description": "This option will only allow you to move forward in Question Set",
              "optional": true,
              "default": false
            },
            {
              "name": "randomQuestions",
              "type": "boolean",
              "label": "Randomize questions",
              "importance": "low",
              "description": "Enable to randomize the order of questions on display.",
              "default": false
            },
            {
              "name": "poolSize",
              "type": "number",
              "min": 1,
              "label": "Number of questions to be shown:",
              "importance": "low",
              "description": "Create a randomized batch of questions from the total.",
              "optional": true
            },
            {
              "name": "endGame",
              "type": "group",
              "label": "Quiz finished",
              "importance": "medium",
              "fields": [
                {
                  "name": "showResultPage",
                  "type": "boolean",
                  "label": "Display results",
                  "importance": "low",
                  "default": true
                },
                {
                  "name": "showSolutionButton",
                  "type": "boolean",
                  "label": "Display solution button",
                  "default": true
                },
                {
                  "name": "showRetryButton",
                  "type": "boolean",
                  "label": "Display retry button",
                  "default": true
                },
                {
                  "name": "noResultMessage",
                  "type": "text",
                  "label": "No results message",
                  "importance": "low",
                  "description": "Text displayed on end page when \"Display results\" is disabled",
                  "default": "Finished",
                  "optional": true
                },
                {
                  "name": "message",
                  "type": "text",
                  "label": "Feedback heading",
                  "importance": "low",
                  "default": "Your result:",
                  "description": "This heading will be displayed at the end of the quiz when the user has answered all questions.",
                  "tags": [
                    "strong",
                    "em",
                    "code"
                  ]
                },
                {
                  "name": "scoreBarLabel",
                  "type": "text",
                  "label": "Score announcer",
                  "importance": "low",
                  "description": "This label will be used for announcing the final score to the user on the end screen",
                  "default": "You got @finals out of @totals points"
                },
                {
                  "name": "overallFeedback",
                  "type": "group",
                  "label": "Overall Feedback",
                  "importance": "low",
                  "expanded": true,
                  "fields": [
                    {
                      "name": "overallFeedback",
                      "type": "list",
                      "widgets": [
                        {
                          "name": "RangeList",
                          "label": "Default"
                        }
                      ],
                      "importance": "high",
                      "label": "Define custom feedback for any score range",
                      "description": "Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!",
                      "entity": "range",
                      "min": 1,
                      "defaultNum": 1,
                      "optional": true,
                      "field": {
                        "name": "overallFeedback",
                        "type": "group",
                        "importance": "low",
                        "fields": [
                          {
                            "name": "from",
                            "type": "number",
                            "label": "Score Range",
                            "min": 0,
                            "max": 100,
                            "default": 0,
                            "unit": "%"
                          },
                          {
                            "name": "to",
                            "type": "number",
                            "min": 0,
                            "max": 100,
                            "default": 100,
                            "unit": "%"
                          },
                          {
                            "name": "feedback",
                            "type": "text",
                            "label": "Feedback for defined score range",
                            "importance": "low",
                            "placeholder": "Fill in the feedback",
                            "optional": true
                          }
                        ]
                      }
                    }
                  ]
                },
                {
                  "name": "oldFeedback",
                  "type": "group",
                  "label": "Old Feedback",
                  "importance": "low",
                  "deprecated": true,
                  "fields": [
                    {
                      "name": "successGreeting",
                      "type": "text",
                      "label": "Quiz passed greeting",
                      "importance": "low",
                      "optional": true,
                      "description": "This text will be displayed above the score if the user has successfully passed the quiz.",
                      "tags": [
                        "strong",
                        "em",
                        "code"
                      ]
                    },
                    {
                      "name": "successComment",
                      "type": "text",
                      "widget": "html",
                      "label": "Passed comment",
                      "importance": "low",
                      "optional": true,
                      "description": "This comment will be displayed after the score if the user has successfully passed the quiz.",
                      "tags": [
                        "sub",
                        "sup",
                        "strong",
                        "em",
                        "a",
                        "p",
                        "code"
                      ]
                    },
                    {
                      "name": "failGreeting",
                      "type": "text",
                      "label": "Quiz failed title",
                      "importance": "low",
                      "optional": true,
                      "description": "This text will be displayed above the score if the user has failed the quiz.",
                      "tags": [
                        "strong",
                        "em",
                        "code"
                      ]
                    },
                    {
                      "name": "failComment",
                      "type": "text",
                      "widget": "html",
                      "label": "Failed comment",
                      "importance": "low",
                      "optional": true,
                      "description": "This comment will be displayed after the score if the user has failed the quiz.",
                      "tags": [
                        "sub",
                        "sup",
                        "strong",
                        "em",
                        "a",
                        "p",
                        "code"
                      ]
                    }
                  ]
                },
                {
                  "name": "solutionButtonText",
                  "type": "text",
                  "label": "Solution button label",
                  "importance": "low",
                  "default": "Show solution",
                  "description": "Text for the solution button."
                },
                {
                  "name": "retryButtonText",
                  "type": "text",
                  "label": "Retry button label",
                  "importance": "low",
                  "default": "Retry",
                  "description": "Text for the retry button."
                },
                {
                  "name": "finishButtonText",
                  "type": "text",
                  "label": "Finish button text",
                  "importance": "low",
                  "default": "Finish"
                },
                {
                  "name": "submitButtonText",
                  "type": "text",
                  "label": "Submit button text",
                  "importance": "low",
                  "default": "Submit"
                },
                {
                  "name": "showAnimations",
                  "type": "boolean",
                  "label": "Display video before quiz results",
                  "importance": "low"
                },
                {
                  "name": "skippable",
                  "type": "boolean",
                  "label": "Enable skip video button",
                  "importance": "low"
                },
                {
                  "name": "skipButtonText",
                  "type": "text",
                  "label": "Skip video button label",
                  "importance": "low",
                  "default": "Skip video"
                },
                {
                  "name": "successVideo",
                  "type": "video",
                  "label": "Passed video",
                  "importance": "low",
                  "optional": true,
                  "description": "This video will be played if the user successfully passed the quiz."
                },
                {
                  "name": "failVideo",
                  "type": "video",
                  "label": "Fail video",
                  "importance": "low",
                  "optional": true,
                  "description": "This video will be played if the user fails the quiz."
                }
              ]
            },
            {
              "name": "override",
              "type": "group",
              "label": "Settings for \"Check\", \"Show solution\" and \"Retry\"",
              "importance": "low",
              "optional": true,
              "fields": [
                {
                  "name": "checkButton",
                  "type": "boolean",
                  "label": "Show \"Check\" buttons",
                  "importance": "low",
                  "description": "This option determines if the \"Check\" button will be shown for all questions.",
                  "optional": true,
                  "default": true
                },
                {
                  "name": "showSolutionButton",
                  "type": "select",
                  "label": "Override \"Show Solution\" button",
                  "importance": "low",
                  "description": "This option determines if the \"Show Solution\" button will be shown for all questions, disabled for all or configured for each question individually.",
                  "optional": true,
                  "widget": "showWhen",
                  "showWhen": {
                    "rules": [
                      {
                        "field": "checkButton",
                        "equals": true
                      }
                    ]
                  },
                  "options": [
                    {
                      "value": "on",
                      "label": "Enabled"
                    },
                    {
                      "value": "off",
                      "label": "Disabled"
                    }
                  ]
                },
                {
                  "name": "retryButton",
                  "type": "select",
                  "label": "Override \"Retry\" button",
                  "importance": "low",
                  "description": "This option determines if the \"Retry\" button will be shown for all questions, disabled for all or configured for each question individually.",
                  "optional": true,
                  "widget": "showWhen",
                  "showWhen": {
                    "rules": [
                      {
                        "field": "checkButton",
                        "equals": true
                      }
                    ]
                  },
                  "options": [
                    {
                      "value": "on",
                      "label": "Enabled"
                    },
                    {
                      "value": "off",
                      "label": "Disabled"
                    }
                  ]
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
                      "name": "submitAnswer",
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
