<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class H5PMarkTheWordsLibSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pMarkTheWordsLibParams = ['name' => "H5P.MarkTheWords", "major_version" => 1, "minor_version" => 9];
        $h5pMarkTheWordsLib = DB::table('h5p_libraries')->where($h5pMarkTheWordsLibParams)->first();
        if ($h5pMarkTheWordsLib) {
            DB::table('h5p_libraries')->where($h5pMarkTheWordsLibParams)->update(['semantics' => $this->updatedSemantics()]);
        }
    }

    private function updatedSemantics() {
        return '[
                  {
                    "label": "Task description",
                    "importance": "high",
                    "name": "taskDescription",
                    "type": "text",
                    "widget": "html",
                    "description": "Describe how the user should solve the task.",
                    "placeholder": "Click on all the verbs in the text that follows.",
                    "enterMode": "p",
                    "tags": [
                      "strong",
                      "em",
                      "u",
                      "a",
                      "ul",
                      "ol",
                      "h2",
                      "h3",
                      "hr",
                      "pre",
                      "code"
                    ]
                  },
                  {
                    "label": "Textfield",
                    "importance": "high",
                    "name": "textField",
                    "type": "text",
                    "widget": "html",
                    "tags": [
                      "p",
                      "br",
                      "strong",
                      "em",
                      "code"
                    ],
                    "placeholder": "This is an answer: *answer*.",
                    "description": "",
                    "important": {
                      "description": "<ul><li>Correct words are marked with asterisks (*) before and after the word.</li><li>Asterisks can be added within marked words by adding another asterisk, *correctword*** =&gt; correctword*.</li><li>Only words may be marked as correct. Not phrases.</li></ul>",
                      "example": "The correct words are marked like this: *correctword*, an asterisk is written like this: *correctword***."
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
                    "label": "Text for \"Check\" button",
                    "importance": "low",
                    "name": "checkAnswerButton",
                    "type": "text",
                    "default": "Check",
                    "common": true
                  },
                  {
                    "label": "Text for \"Retry\" button",
                    "importance": "low",
                    "name": "tryAgainButton",
                    "type": "text",
                    "default": "Retry",
                    "common": true
                  },
                  {
                    "label": "Text for \"Show solution\" button",
                    "importance": "low",
                    "name": "showSolutionButton",
                    "type": "text",
                    "default": "Show solution",
                    "common": true
                  },
                  {
                    "name": "behaviour",
                    "importance": "low",
                    "type": "group",
                    "label": "Behavioural settings.",
                    "description": "These options will let you control how the task behaves.",
                    "optional": true,
                    "fields": [
                      {
                        "name": "enableRetry",
                        "type": "boolean",
                        "label": "Enable \"Retry\"",
                        "importance": "low",
                        "default": true
                      },
                      {
                        "name": "enableSolutionsButton",
                        "type": "boolean",
                        "label": "Enable \"Show solution\" button",
                        "importance": "low",
                        "default": true
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
                        "name": "showScorePoints",
                        "type": "boolean",
                        "label": "Show score points",
                        "description": "Show points earned for each answer.",
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
                    "label": "Correct answer text",
                    "importance": "low",
                    "name": "correctAnswer",
                    "type": "text",
                    "default": "Correct!",
                    "description": "Text used to indicate that an answer is correct",
                    "common": true
                  },
                  {
                    "label": "Incorrect answer text",
                    "importance": "low",
                    "name": "incorrectAnswer",
                    "type": "text",
                    "default": "Incorrect!",
                    "description": "Text used to indicate that an answer is incorrect",
                    "common": true
                  },
                  {
                    "label": "Missed answer text",
                    "importance": "low",
                    "name": "missedAnswer",
                    "type": "text",
                    "default": "Answer not found!",
                    "description": "Text used to indicate that an answer is missing",
                    "common": true
                  },
                  {
                    "label": "Description for Display Solution",
                    "importance": "low",
                    "name": "displaySolutionDescription",
                    "type": "text",
                    "default": "Task is updated to contain the solution.",
                    "description": "This text tells the user that the tasks has been updated with the solution.",
                    "common": true
                  },
                  {
                    "name": "scoreBarLabel",
                    "type": "text",
                    "label": "Textual representation of the score bar for those using a readspeaker",
                    "default": "You got :num out of :total points",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yFullTextLabel",
                    "type": "text",
                    "label": "Label for the full readable text for assistive technologies",
                    "default": "Full readable text",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yClickableTextLabel",
                    "type": "text",
                    "label": "Label for the text where words can be marked for assistive technologies",
                    "default": "Full text where words can be marked",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11ySolutionModeHeader",
                    "type": "text",
                    "label": "Solution mode header for assistive technologies",
                    "default": "Solution mode",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yCheckingHeader",
                    "type": "text",
                    "label": "Checking mode header for assistive technologies",
                    "default": "Checking mode",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yCheck",
                    "type": "text",
                    "label": "Assistive technology description for \"Check\" button",
                    "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yShowSolution",
                    "type": "text",
                    "label": "Assistive technology description for \"Show Solution\" button",
                    "default": "Show the solution. The task will be marked with its correct solution.",
                    "importance": "low",
                    "common": true
                  },
                  {
                    "name": "a11yRetry",
                    "type": "text",
                    "label": "Assistive technology description for \"Retry\" button",
                    "default": "Retry the task. Reset all responses and start the task over again.",
                    "importance": "low",
                    "common": true
                  }
                ]';
    }
}
