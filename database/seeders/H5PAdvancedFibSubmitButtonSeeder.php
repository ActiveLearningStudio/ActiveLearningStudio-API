<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PAdvancedFibSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pAdvancedFibLibParams = ['name' => "H5P.AdvancedBlanks", "major_version" => 1, "minor_version" => 0];
        $h5pAdvancedFibLib = DB::table('h5p_libraries')->where($h5pAdvancedFibLibParams)->first();
        if ($h5pAdvancedFibLib) {
            DB::table('h5p_libraries')->where($h5pAdvancedFibLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
                      {
                        "name": "media",
                        "type": "group",
                        "label": "Media",
                        "importance": "medium",
                        "fields": [
                          {
                            "name": "type",
                            "type": "library",
                            "label": "Type",
                            "options": [
                              "H5P.Image 1.1",
                              "H5P.Video 1.5"
                            ],
                            "optional": true,
                            "description": "Optional media to display above the question."
                          }
                        ]
                      },
                      {
                        "name": "content",
                        "type": "group",
                        "label": "Blank content",
                        "importance": "medium",
                        "expanded": true,
                        "fields": [
                          {
                            "label": "Task description",
                            "importance": "high",
                            "name": "task",
                            "type": "text",
                            "widget": "html",
                            "default": "Fill in the missing words.",
                            "description": "A guide telling the user how to answer this task.",
                            "enterMode": "p",
                            "tags": [
                              "strong",
                              "em",
                              "u",
                              "a",
                              "ul",
                              "ol",
                              "h1",
                              "h2",
                              "h3",
                              "hr"
                            ]
                          },
                          {
                            "name": "blanksText",
                            "type": "text",
                            "widget": "html",
                            "label": "Text with blanks",
                            "importance": "high",
                            "enterMode": "p",
                            "important": {
                              "description": "<ul><li>Blanks must be marked with __________ (three or more underscores).</li><li>Do not put the solutions into the text.</li></ul>",
                              "example": "I have _________ wanted to visit Norway."
                            },
                            "description": "",
                            "tags": [
                              "strong",
                              "em",
                              "u",
                              "strike",
                              "ul",
                              "ol",
                              "table",
                              "h1",
                              "h2",
                              "h3",
                              "hr",
                              "sub",
                              "sup"
                            ],
                            "font": {
                              "size": true,
                              "family": true,
                              "color": true,
                              "background": true
                            }
                          },
                          {
                            "name": "blanksList",
                            "type": "list",
                            "label": "Blanks used in the text",
                            "importance": "high",
                            "entity": "blank",
                            "min": 1,
                            "widgets": [
                              {
                                "name": "VerticalTabs",
                                "label": "Default"
                              }
                            ],
                            "field": {
                              "name": "blankGroup",
                              "type": "group",
                              "label": "Blank",
                              "fields": [
                                {
                                  "name": "correctAnswerText",
                                  "type": "text",
                                  "label": "Correct answer",
                                  "description": "You can separate alternative answers with a slash (/)."
                                },
                                {
                                  "name": "hint",
                                  "type": "text",
                                  "label": "Hint",
                                  "optional": true
                                },
                                {
                                  "name": "incorrectAnswersList",
                                  "type": "list",
                                  "label": "Incorrect answers",
                                  "entity": "incorrect answer",
                                  "optional": true,
                                  "min": 0,
                                  "field": {
                                    "name": "incorrectAnswerGroup",
                                    "type": "group",
                                    "label": "Incorrect answer",
                                    "description": "You can separate alternative answers with a slash (/).",
                                    "fields": [
                                      {
                                        "name": "incorrectAnswerText",
                                        "type": "text",
                                        "label": "Incorrect answer text"
                                      },
                                      {
                                        "name": "incorrectAnswerFeedback",
                                        "type": "text",
                                        "label": "Feedback",
                                        "optional": true,
                                        "description": "The feedback should help the user find the correct answer and ideally be tailored to the answer the user has entered.",
                                        "widget": "html",
                                        "tags": [
                                          "strong",
                                          "em",
                                          "u",
                                          "a",
                                          "ul",
                                          "ol",
                                          "sub",
                                          "sup"
                                        ]
                                      },
                                      {
                                        "name": "showHighlight",
                                        "type": "boolean",
                                        "label": "Show highlight in the text when feedback is shown",
                                        "description": "Put !! before and after a piece of text to mark it as a highlight. (e.g. This is !!a highlight!!)",
                                        "default": false,
                                        "optional": false
                                      },
                                      {
                                        "name": "highlight",
                                        "type": "select",
                                        "label": "Highlight position relative to blank:",
                                        "widget": "showWhen",
                                        "showWhen": {
                                          "rules": [
                                            {
                                              "field": "showHighlight",
                                              "equals": true
                                            }
                                          ]
                                        },
                                        "options": [
                                          {
                                            "value": -1,
                                            "label": "- 1 (right before the blank)"
                                          },
                                          {
                                            "value": -2,
                                            "label": "- 2 (two highlights before the blank)"
                                          },
                                          {
                                            "value": -3,
                                            "label": "- 3 (three highlights before the blank)"
                                          },
                                          {
                                            "value": 1,
                                            "label": "+ 1 (right after the blank)"
                                          },
                                          {
                                            "value": 2,
                                            "label": "+ 2 (two highlights after the blank)"
                                          },
                                          {
                                            "value": 3,
                                            "label": "+ 3 (three highlights after the blank)"
                                          }
                                        ],
                                        "default": -1
                                      }
                                    ]
                                  }
                                }
                              ]
                            }
                          }
                        ]
                      },
                      {
                        "name": "snippets",
                        "type": "group",
                        "label": "Snippets",
                        "importance": "medium",
                        "fields": [
                          {
                            "name": "list",
                            "type": "list",
                            "label": "Snippet list",
                            "importance": "high",
                            "description": "Snippets are texts that can be reused in feedback texts by inserting @snippetname into the it.",
                            "entity": "snippet",
                            "optional": true,
                            "min": 0,
                            "field": {
                              "name": "snippetGroup",
                              "type": "group",
                              "label": "Snippet",
                              "fields": [
                                {
                                  "name": "snippetName",
                                  "type": "text",
                                  "label": "Name",
                                  "description": "You can only use letters and numbers for the snippet name.",
                                  "regexp": {
                                    "pattern": "^[\\\\w\\\\d]*$"
                                  }
                                },
                                {
                                  "name": "snippetText",
                                  "type": "text",
                                  "label": "Text",
                                  "widget": "html",
                                  "tags": [
                                    "strong",
                                    "em",
                                    "u",
                                    "a",
                                    "ul",
                                    "ol",
                                    "sub",
                                    "sup"
                                  ]
                                }
                              ]
                            }
                          }
                        ]
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
                        "description": "These options will let you control how the task behaves.",
                        "optional": false,
                        "fields": [
                          {
                            "name": "mode",
                            "type": "select",
                            "label": "Answer mode",
                            "description": "Indicates in what way the user gives answers.",
                            "optional": false,
                            "options": [
                              {
                                "value": "selection",
                                "label": "The users selects from options."
                              },
                              {
                                "value": "typing",
                                "label": "The users types in the answers."
                              }
                            ],
                            "default": "typing"
                          },
                          {
                            "name": "selectAlternatives",
                            "type": "select",
                            "label": "Alternatives offered for selection",
                            "optional": false,
                            "widget": "showWhen",
                            "showWhen": {
                              "rules": [
                                {
                                  "field": "mode",
                                  "equals": "selection"
                                }
                              ]
                            },
                            "options": [
                              {
                                "value": "alternatives",
                                "label": "The incorrect answers entered for the blank"
                              },
                              {
                                "value": "all",
                                "label": "The correct answers of all other blanks"
                              }
                            ],
                            "default": "alternatives"
                          },
                          {
                            "name": "selectAlternativeRestriction",
                            "type": "number",
                            "label": "Maximum number of alternatives of other blanks offered for selection",
                            "description": "Enter 0 to set no limit.",
                            "min": 0,
                            "step": 1,
                            "default": 5,
                            "widget": "showWhen",
                            "showWhen": {
                              "rules": [
                                {
                                  "field": "mode",
                                  "equals": "selection"
                                }
                              ]
                            }
                          },
                          {
                            "name": "spellingErrorBehaviour",
                            "type": "select",
                            "label": "Behaviour when user makes a spelling error",
                            "importance": "low",
                            "options": [
                              {
                                "value": "accept",
                                "label": "Accept it as a correct answer"
                              },
                              {
                                "value": "warn",
                                "label": "Warn the user about the error"
                              },
                              {
                                "value": "mistake",
                                "label": "Consider it a regular mistake"
                              }
                            ],
                            "default": "mistake",
                            "widget": "showWhen",
                            "showWhen": {
                              "rules": [
                                {
                                  "field": "mode",
                                  "equals": "typing"
                                }
                              ]
                            }
                          },
                          {
                            "name": "caseSensitive",
                            "importance": "low",
                            "type": "boolean",
                            "default": false,
                            "label": "Case sensitive",
                            "description": "If enabled, capitalization that is different from the solution is considered an error.",
                            "widget": "showWhen",
                            "showWhen": {
                              "rules": [
                                {
                                  "field": "mode",
                                  "equals": "typing"
                                }
                              ]
                            }
                          },
                          {
                            "label": "Automatically check answers after input",
                            "importance": "low",
                            "name": "autoCheck",
                            "type": "boolean",
                            "default": false,
                            "optional": true
                          },
                          {
                            "label": "Enable \"Show solutions\" button",
                            "importance": "low",
                            "name": "enableSolutionsButton",
                            "type": "boolean",
                            "default": true,
                            "optional": true
                          },
                          {
                            "label": "Require all fields to be answered before the solution can be viewed",
                            "importance": "low",
                            "name": "showSolutionsRequiresInput",
                            "type": "boolean",
                            "default": true,
                            "optional": true
                          },
                          {
                            "label": "Enable \"Retry\"",
                            "importance": "low",
                            "name": "enableRetry",
                            "type": "boolean",
                            "default": true,
                            "optional": true
                          },
                          {
                            "name": "enableCheckButton",
                            "type": "boolean",
                            "label": "Enable \"Check\" button",
                            "widget": "none",
                            "importance": "low",
                            "default": true,
                            "optional": true
                          },
                          {
                            "label": "Disable image zooming for question image",
                            "importance": "low",
                            "name": "disableImageZooming",
                            "type": "boolean",
                            "default": false,
                            "optional": true
                          },
                          {
                            "label": "Show confirmation dialog on \"Check\"",
                            "importance": "low",
                            "name": "confirmCheckDialog",
                            "type": "boolean",
                            "description": "This options is not compatible with the \"Automatically check answers after input\" option",
                            "default": false
                          },
                          {
                            "label": "Show confirmation dialog on \"Retry\"",
                            "importance": "low",
                            "name": "confirmRetryDialog",
                            "type": "boolean",
                            "default": false
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
                        "label": "Text for \"Show solutions\" button",
                        "name": "showSolutions",
                        "type": "text",
                        "default": "Show solution",
                        "common": true,
                        "optional": true
                      },
                      {
                        "label": "Text for \"Retry\" button",
                        "importance": "low",
                        "name": "tryAgain",
                        "type": "text",
                        "default": "Retry",
                        "common": true,
                        "optional": true
                      },
                      {
                        "label": "Text for \"Check\" button",
                        "importance": "low",
                        "name": "checkAnswer",
                        "type": "text",
                        "default": "Check",
                        "common": true,
                        "optional": true
                      },
                      {
                        "label": "Text for \"Not filled out\" message",
                        "importance": "low",
                        "name": "notFilledOut",
                        "type": "text",
                        "default": "Please fill in all blanks to view solution",
                        "common": true,
                        "optional": true
                      },
                      {
                        "label": "Tip icon label",
                        "importance": "low",
                        "name": "tipLabel",
                        "type": "text",
                        "default": "Tip",
                        "common": true,
                        "optional": true
                      },
                      {
                        "label": "Spelling mistake warning",
                        "description": "This is the message shown to users when they make a spelling mistake. Insert @mistake to show them what they did wrong.",
                        "importance": "low",
                        "name": "spellingMistakeWarning",
                        "type": "text",
                        "default": "Check your spelling: @mistake",
                        "common": true,
                        "optional": true
                      },
                      {
                        "label": "Check confirmation dialog",
                        "importance": "low",
                        "name": "confirmCheck",
                        "type": "group",
                        "common": true,
                        "fields": [
                          {
                            "label": "Header text",
                            "importance": "low",
                            "name": "header",
                            "type": "text",
                            "default": "Finish?"
                          },
                          {
                            "label": "Body text",
                            "importance": "low",
                            "name": "body",
                            "type": "text",
                            "default": "Are you sure you wish to finish?",
                            "widget": "html",
                            "enterMode": "p",
                            "tags": [
                              "strong",
                              "em",
                              "del",
                              "u"
                            ]
                          },
                          {
                            "label": "Cancel button label",
                            "importance": "low",
                            "name": "cancelLabel",
                            "type": "text",
                            "default": "Cancel"
                          },
                          {
                            "label": "Confirm button label",
                            "importance": "low",
                            "name": "confirmLabel",
                            "type": "text",
                            "default": "Finish"
                          }
                        ]
                      },
                      {
                        "label": "Retry confirmation dialog",
                        "importance": "low",
                        "name": "confirmRetry",
                        "type": "group",
                        "common": true,
                        "fields": [
                          {
                            "label": "Header text",
                            "importance": "low",
                            "name": "header",
                            "type": "text",
                            "default": "Retry?"
                          },
                          {
                            "label": "Body text",
                            "importance": "low",
                            "name": "body",
                            "type": "text",
                            "default": "Are you sure you wish to retry?",
                            "widget": "html",
                            "enterMode": "p",
                            "tags": [
                              "strong",
                              "em",
                              "del",
                              "u"
                            ]
                          },
                          {
                            "label": "Cancel button label",
                            "importance": "low",
                            "name": "cancelLabel",
                            "type": "text",
                            "default": "Cancel"
                          },
                          {
                            "label": "Confirm button label",
                            "importance": "low",
                            "name": "confirmLabel",
                            "type": "text",
                            "default": "Confirm"
                          }
                        ]
                      },
                      {
                        "name": "scoreBarLabel",
                        "type": "text",
                        "label": "Textual representation of the score bar for those using a readspeaker",
                        "default": "You got :num out of :total points",
                        "importance": "low",
                        "common": true
                      }
                    ]';
    }
}
