<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PSingleChoiceSetSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pSingleChoiceSetLibParams = ['name' => "H5P.SingleChoiceSet", "major_version" => 1, "minor_version" => 11];
        $h5pSingleChoiceSetLib = DB::table('h5p_libraries')->where($h5pSingleChoiceSetLibParams)->first();
        if ($h5pSingleChoiceSetLib) {
            DB::table('h5p_libraries')->where($h5pSingleChoiceSetLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
                  {
                    "name": "choices",
                    "type": "list",
                    "label": "List of questions",
                    "importance": "high",
                    "entity": "question",
                    "min": 1,
                    "defaultNum": 2,
                    "widgets": [
                      {
                        "name": "ListEditor",
                        "label": "Default"
                      },
                      {
                        "name": "SingleChoiceSetTextualEditor",
                        "label": "Textual"
                      }
                    ],
                    "field": {
                      "name": "choice",
                      "type": "group",
                      "isSubContent": true,
                      "label": "Question & alternatives",
                      "importance": "high",
                      "fields": [
                        {
                          "name": "question",
                          "type": "text",
                          "widget": "html",
                          "tags": [
                            "p",
                            "br",
                            "strong",
                            "em",
                            "code"
                          ],
                          "label": "Question",
                          "importance": "high"
                        },
                        {
                          "name": "answers",
                          "type": "list",
                          "label": "Alternatives - first alternative is the correct one.",
                          "importance": "medium",
                          "entity": "answer",
                          "min": 2,
                          "max": 4,
                          "defaultNum": 2,
                          "field": {
                            "name": "answer",
                            "type": "text",
                            "widget": "html",
                            "tags": [
                              "p",
                              "br",
                              "strong",
                              "em",
                              "code"
                            ],
                            "label": "Alternative",
                            "importance": "medium"
                          }
                        }
                      ]
                    }
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
                    "name": "behaviour",
                    "type": "group",
                    "label": "Behavioural settings",
                    "importance": "low",
                    "fields": [
                      {
                        "name": "autoContinue",
                        "type": "boolean",
                        "label": "Auto continue",
                        "description": "Automatically go to next question when alternative is selected",
                        "default": true
                      },
                      {
                        "name": "timeoutCorrect",
                        "type": "number",
                        "label": "Timeout on correct answers",
                        "importance": "low",
                        "description": "Value in milliseconds",
                        "default": 2000,
                        "widget": "showWhen",
                        "showWhen": {
                          "rules": [
                            {
                              "field": "autoContinue",
                              "equals": true
                            }
                          ]
                        }
                      },
                      {
                        "name": "timeoutWrong",
                        "type": "number",
                        "label": "Timeout on wrong answers",
                        "importance": "low",
                        "description": "Value in milliseconds",
                        "default": 3000,
                        "widget": "showWhen",
                        "showWhen": {
                          "rules": [
                            {
                              "field": "autoContinue",
                              "equals": true
                            }
                          ]
                        }
                      },
                      {
                        "name": "soundEffectsEnabled",
                        "type": "boolean",
                        "label": "Enable sound effects",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "enableRetry",
                        "type": "boolean",
                        "label": "Enable retry button",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "enableSolutionsButton",
                        "type": "boolean",
                        "label": "Enable show solution button",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "passPercentage",
                        "type": "number",
                        "label": "Pass percentage",
                        "description": "Percentage of Total score required for passing the quiz.",
                        "min": 0,
                        "max": 100,
                        "step": 1,
                        "default": 100
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
                    "name": "l10n",
                    "type": "group",
                    "label": "Localize single choice set",
                    "importance": "low",
                    "common": true,
                    "fields": [
                      {
                        "name": "nextButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Next\" button",
                        "importance": "low",
                        "default": "Next question"
                      },
                      {
                        "name": "showSolutionButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Show solution\" button",
                        "importance": "low",
                        "default": "Show solution"
                      },
                      {
                        "name": "retryButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Retry\" button",
                        "importance": "low",
                        "default": "Retry"
                      },
                      {
                        "name": "solutionViewTitle",
                        "type": "text",
                        "label": "Title for the show solution view",
                        "importance": "low",
                        "default": "Solution list"
                      },
                      {
                        "name": "correctText",
                        "type": "text",
                        "label": "Readspeaker text for correct answer",
                        "importance": "low",
                        "default": "Correct!"
                      },
                      {
                        "name": "incorrectText",
                        "type": "text",
                        "label": "Readspeaker text for incorrect answer",
                        "importance": "low",
                        "default": "Incorrect!"
                      },
                      {
                        "name": "muteButtonLabel",
                        "type": "text",
                        "label": "Label for the \"mute\" button, to disable feedback sound",
                        "importance": "low",
                        "default": "Mute feedback sound"
                      },
                      {
                        "name": "closeButtonLabel",
                        "type": "text",
                        "label": "Label for the \"Close\" button",
                        "importance": "low",
                        "default": "Close"
                      },
                      {
                        "name": "slideOfTotal",
                        "type": "text",
                        "label": "Slide number text",
                        "importance": "low",
                        "description": "Announces current slide and total number of slides, variables are :num and :total",
                        "default": "Slide :num of :total"
                      },
                      {
                        "name": "scoreBarLabel",
                        "type": "text",
                        "label": "Textual representation of the score bar for those using a readspeaker",
                        "default": "You got :num out of :total points",
                        "importance": "low"
                      },
                      {
                        "name": "solutionListQuestionNumber",
                        "type": "text",
                        "label": "Label for the question number in the solution list",
                        "importance": "low",
                        "description": "Announces current question index in solution list, variables are :num",
                        "default": "Question :num"
                      },
                      
                      {
                        "name": "a11yShowSolution",
                        "type": "text",
                        "label": "Assistive technology description for \"Show Solution\" button",
                        "default": "Show the solution. The task will be marked with its correct solution.",
                        "importance": "low"
                      },
                      {
                        "name": "a11yRetry",
                        "type": "text",
                        "label": "Assistive technology description for \"Retry\" button",
                        "default": "Retry the task. Reset all responses and start the task over again.",
                        "importance": "low"
                      }
                    ]
                  }
                ]';
    }
}
