<?php

use Illuminate\Database\Seeder;

class changeBehaviourLabel extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //H5P.DragText-1.8
        $h5pDragTextLibParams = ['name' => "H5P.DragText", "major_version" =>1, "minor_version" => 8];
        $h5pDragTextLib = DB::table('h5p_libraries')->where($h5pDragTextLibParams)->first();
        
        if ($h5pDragTextLib) {
            DB::table('h5p_libraries')->where($h5pDragTextLibParams)->update([
                'semantics' => $this->updatedDragTextSemantics(),
            ]);
        }

        //H5P.DragQuestion-1.14
        $h5pDragQuestionLibParams = ['name' => "H5P.DragQuestion", "major_version" =>1, "minor_version" => 14];
        $h5pDragQuestionLib = DB::table('h5p_libraries')->where($h5pDragQuestionLibParams)->first();
        
        if ($h5pDragQuestionLib) {
            DB::table('h5p_libraries')->where($h5pDragQuestionLibParams)->update([
                'semantics' => $this->updatedDragQuestionSemantics(),
            ]);
        }

        //H5P.AdvancedBlanks-1.0
        $h5pAdvancedBlanksLibParams = ['name' => "H5P.AdvancedBlanks", "major_version" =>1, "minor_version" => 0];
        $h5pAdvancedBlanksLib = DB::table('h5p_libraries')->where($h5pAdvancedBlanksLibParams)->first();
        
        if ($h5pAdvancedBlanksLib) {
            DB::table('h5p_libraries')->where($h5pAdvancedBlanksLibParams)->update([
                'semantics' => $this->updatedAdvancedBlanksSemantics(),
            ]);
        }
    }

    //H5P.DragText-1.8
    private function updatedDragTextSemantics() { 
        return '[
          {
            "label": "Task description",
            "importance": "high",
            "name": "taskDescription",
            "type": "text",
            "widget": "html",
            "description": "Describe how the user should solve the task.",
            "default": "Drag the words into the correct boxes",
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
            "label": "Text",
            "importance": "high",
            "name": "textField",
            "type": "text",
            "widget": "textarea",
            "placeholder": "*Oslo* is the capital of Norway, *Stockholm* is the capital of Sweden and *Copenhagen* is the capital of Denmark. All cities are located in the *Scandinavian:Northern Part of Europe* peninsula.",
            "description": "",
            "important": {
              "description": "<ul><li>Droppable words are added with an asterisk (*) in front and behind the correct word/phrase.</li><li>You may add a textual tip, using a colon (:) in front of the tip.</li><li>For every empty spot there is only one correct word.</li><li>You may add feedback to be displayed when a task is completed. Use \\\+ for correct and \\\- for incorrect feedback.</li></ul>",
              "example": "H5P content may be edited using a *browser:What type of program is Chrome?*. </br> H5P content is *interactive\\\+Correct! \\\-Incorrect, try again!*"
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
            "name": "checkAnswer",
            "type": "text",
            "default": "Check",
            "common": true
          },
          {
            "label": "Text for \"Retry\" button",
            "importance": "low",
            "name": "tryAgain",
            "type": "text",
            "default": "Retry",
            "common": true
          },
          {
            "label": "Text for \"Show Solution\" button",
            "importance": "low",
            "name": "showSolution",
            "type": "text",
            "default": "Show solution",
            "common": true
          },
          {
            "label": "Numbered Drop zone label",
            "importance": "low",
            "name": "dropZoneIndex",
            "type": "text",
            "default": "Drop Zone @index.",
            "description": "Label used for accessibility, where the Read speaker will read the index of a drop zone. Variable available: @index",
            "common": true
          },
          {
            "label": "Empty Drop Zone label",
            "importance": "low",
            "name": "empty",
            "type": "text",
            "default": "Drop Zone @index is empty.",
            "description": "Label used for accessibility, where the Read speaker will read that the drop zone is empty",
            "common": true
          },
          {
            "label": "Contains Drop Zone label",
            "importance": "low",
            "name": "contains",
            "type": "text",
            "default": "Drop Zone @index contains draggable @draggable.",
            "description": "Label used for accessibility, where the Read speaker will read that the drop zone contains a draggable",
            "common": true
          },
          {
            "label": "Draggable elements label",
            "importance": "low",
            "name": "ariaDraggableIndex",
            "type": "text",
            "default": "@index of @count draggables.",
            "description": "Label used for accessibility, where the Read speaker reads that this is a draggable element. Variable available: @index, @count",
            "common": true
          },
          {
            "label": "Label for show tip button",
            "importance": "low",
            "name": "tipLabel",
            "type": "text",
            "default": "Show tip",
            "description": "Label used for accessibility, where the Read speaker reads it before the tip is read out",
            "common": true
          },
          {
            "name": "correctText",
            "type": "text",
            "label": "Readspeaker text for correct answer",
            "importance": "low",
            "default": "Correct!",
            "common": true
          },
          {
            "name": "incorrectText",
            "type": "text",
            "label": "Readspeaker text for incorrect answer",
            "importance": "low",
            "default": "Incorrect!",
            "common": true
          },
          {
            "name": "resetDropTitle",
            "type": "text",
            "label": "Confirmation dialog title that user wants to reset a droppable",
            "importance": "low",
            "default": "Reset drop",
            "common": true
          },
          {
            "name": "resetDropDescription",
            "type": "text",
            "label": "Confirmation dialog description that user wants to reset a droppable",
            "importance": "low",
            "default": "Are you sure you want to reset this drop zone?",
            "common": true
          },
          {
            "name": "grabbed",
            "type": "text",
            "label": "Label used for accessibility, where the read speaker indicates that dragging is initiated",
            "importance": "low",
            "default": "Draggable is grabbed.",
            "common": true
          },
          {
            "name": "cancelledDragging",
            "type": "text",
            "label": "Label used for accessibility, where the read speaker indicates that dragging is canceled",
            "importance": "low",
            "default": "Cancelled dragging.",
            "common": true
          },
          {
            "name": "correctAnswer",
            "type": "text",
            "label": "Label used for accessibility, where the read speaker indicates that a text is the correct answer",
            "importance": "low",
            "default": "Correct answer:",
            "common": true
          },
          {
            "name": "feedbackHeader",
            "type": "text",
            "label": "Header for panel containing feedback for correct/incorrect answers",
            "importance": "low",
            "default": "Feedback",
            "common": true
          },
          {
            "name": "behaviour",
            "type": "group",
            "label": "Behaviour settings.",
            "importance": "low",
            "description": "These options will let you control how the task behaves.",
            "optional": true,
            "fields": [
              {
                "label": "Enable \"Retry\"",
                "importance": "low",
                "name": "enableRetry",
                "type": "boolean",
                "default": true
              },
              {
                "label": "Enable \"Show Solution\" button",
                "importance": "low",
                "name": "enableSolutionsButton",
                "type": "boolean",
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
                "label": "Instant feedback",
                "importance": "low",
                "name": "instantFeedback",
                "type": "boolean",
                "default": false,
                "optional": true
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
          },
          {
            "name": "a11yCheck",
            "type": "text",
            "label": "Assistive technology label for \"Check\" button",
            "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
            "importance": "low",
            "common": true
          },
          {
            "name": "a11yShowSolution",
            "type": "text",
            "label": "Assistive technology label for \"Show Solution\" button",
            "default": "Show the solution. The task will be marked with its correct solution.",
            "importance": "low",
            "common": true
          },
          {
            "name": "a11yRetry",
            "type": "text",
            "label": "Assistive technology label for \"Retry\" button",
            "default": "Retry the task. Reset all responses and start the task over again.",
            "importance": "low",
            "common": true
          }
        ]';
    }

    //H5P.DragQuestion-1.14
    private function updatedDragQuestionSemantics() { 
        return '[
          {
            "name": "scoreShow",
            "type": "text",
            "label": "Check answer button",
            "importance": "low",
            "default": "Check",
            "common": true
          },
          {
            "name": "submitAnswers",
            "type": "text",
            "label": "Submit answer button",
            "importance": "low",
            "default": "Submit Answers",
            "common": true
          },
          {
            "name": "tryAgain",
            "type": "text",
            "label": "Retry button",
            "importance": "low",
            "default": "Retry",
            "common": true,
            "optional": true
          },
          {
            "label": "Score explanation text",
            "importance": "low",
            "name": "scoreExplanation",
            "type": "text",
            "default": "Correct answers give +1 point. Incorrect answers give -1 point. The lowest possible score is 0.",
            "common": true,
            "optional": true
          },
          {
            "name": "question",
            "importance": "high",
            "type": "group",
            "widget": "wizard",
            "fields": [
              {
                "name": "settings",
                "type": "group",
                "label": "Settings",
                "importance": "high",
                "fields": [
                  {
                    "name": "background",
                    "type": "image",
                    "label": "Background image",
                    "importance": "low",
                    "optional": true,
                    "description": "Optional. Select an image to use as background for your drag and drop task."
                  },
                  {
                    "name": "size",
                    "type": "group",
                    "widget": "dimensions",
                    "label": "Task size",
                    "importance": "low",
                    "description": "Specify how large (in px) the play area should be.",
                    "default": {
                      "width": 620,
                      "height": 310,
                      "field": "background"
                    },
                    "fields": [
                      {
                        "name": "width",
                        "type": "number"
                      },
                      {
                        "name": "height",
                        "type": "number"
                      }
                    ]
                  }
                ]
              },
              {
                "name": "task",
                "type": "group",
                "widget": "dragQuestion",
                "label": "Task",
                "importance": "high",
                "description": "Start by placing your drop zones.<br/>Next, place your droppable elements and check off the appropriate drop zones.<br/>Last, edit your drop zone again and check off the correct answers.",
                "fields": [
                  {
                    "name": "elements",
                    "type": "list",
                    "label": "Elements",
                    "importance": "high",
                    "entity": "element",
                    "field": {
                      "type": "group",
                      "label": "Element",
                      "importance": "high",
                      "fields": [
                        {
                          "name": "type",
                          "type": "library",
                          "description": "Choose the type of content you would like to add.",
                          "importance": "medium",
                          "options": [
                            "H5P.AdvancedText 1.1",
                            "H5P.Image 1.1"
                          ]
                        },
                        {
                          "name": "x",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "y",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "height",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "width",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "dropZones",
                          "type": "select",
                          "widget": "dynamicCheckboxes",
                          "label": "Select drop zones",
                          "importance": "high",
                          "multiple": true
                        },
                        {
                          "name": "backgroundOpacity",
                          "type": "number",
                          "label": "Background Opacity",
                          "importance": "low",
                          "min": 0,
                          "max": 100,
                          "step": 5,
                          "default": 100,
                          "optional": true
                        },
                        {
                          "name": "multiple",
                          "type": "boolean",
                          "label": "Infinite number of element instances",
                          "importance": "low",
                          "description": "Clones this element so that it can be dragged to multiple drop zones.",
                          "default": false
                        }
                      ]
                    }
                  },
                  {
                    "name": "dropZones",
                    "type": "list",
                    "label": "Drop Zones",
                    "importance": "high",
                    "entity": "Drop Zone",
                    "field": {
                      "type": "group",
                      "label": "Drop Zone",
                      "importance": "high",
                      "fields": [
                        {
                          "name": "label",
                          "type": "text",
                          "widget": "html",
                          "label": "Label",
                          "importance": "medium",
                          "enterMode": "div",
                          "tags": [
                            "strong",
                            "em",
                            "del",
                            "code"
                          ]
                        },
                        {
                          "name": "showLabel",
                          "type": "boolean",
                          "label": "Show label",
                          "importance": "low"
                        },
                        {
                          "name": "x",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "y",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "height",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "width",
                          "type": "number",
                          "widget": "none"
                        },
                        {
                          "name": "correctElements",
                          "type": "select",
                          "widget": "dynamicCheckboxes",
                          "label": "Select correct elements",
                          "importance": "low",
                          "multiple": true
                        },
                        {
                          "name": "backgroundOpacity",
                          "type": "number",
                          "label": "Background Opacity",
                          "importance": "low",
                          "min": 0,
                          "max": 100,
                          "step": 5,
                          "default": 100,
                          "optional": true
                        },
                        {
                          "name": "tipsAndFeedback",
                          "type": "group",
                          "label": "Tips and feedback",
                          "importance": "low",
                          "optional": true,
                          "fields": [
                            {
                              "name": "tip",
                              "label": "Tip text",
                              "importance": "low",
                              "type": "text",
                              "widget": "html",
                              "tags": [
                                "p",
                                "br",
                                "strong",
                                "em",
                                "code"
                              ],
                              "optional": true
                            },
                            {
                              "name": "feedbackOnCorrect",
                              "type": "text",
                              "label": "Message displayed on correct match",
                              "importance": "low",
                              "description": "Message will appear below the task on \"check\" if correct droppable is matched.",
                              "optional": true
                            },
                            {
                              "name": "feedbackOnIncorrect",
                              "type": "text",
                              "label": "Message displayed on incorrect match",
                              "importance": "low",
                              "description": "Message will appear below the task on \"check\" if the match is incorrect.",
                              "optional": true
                            }
                          ]
                        },
                        {
                          "name": "single",
                          "type": "boolean",
                          "label": "This drop zone can only contain one element",
                          "description": "Make sure there is only one correct answer for this dropzone",
                          "importance": "low",
                          "default": false
                        },
                        {
                          "name": "autoAlign",
                          "type": "boolean",
                          "label": "Enable Auto-Align",
                          "importance": "low",
                          "description": "Will auto-align all draggables dropped in this zone."
                        }
                      ]
                    }
                  }
                ]
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
            "label": "Behaviour settings",
            "importance": "low",
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
                "name": "showSubmitAnswersButton",
                "type": "boolean",
                "label": "Enable \"Submit Answers\"",
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
                "name": "singlePoint",
                "type": "boolean",
                "label": "Give one point for the whole task",
                "importance": "low",
                "description": "Disable to give one point for each draggable that is placed correctly.",
                "default": false
              },
              {
                "label": "Apply penalties",
                "name": "applyPenalties",
                "type": "boolean",
                "description": "Apply penalties for elements dropped in the wrong drop zones. This must be enabled when the same element(s) are able to be dropped into multiple drop zones, or if there is only one drop-zone. If this is not enabled, learners may match all items to all drop-zones and always receive a full score.",
                "default": true
              },
              {
                "name": "enableScoreExplanation",
                "type": "boolean",
                "label": "Enable score explanation",
                "description": "Display a score explanation to user when checking their answers (if the Apply penalties option has been selected).",
                "importance": "low",
                "default": true,
                "optional": true,
                "widget": "showWhen",
                "showWhen": {
                  "rules": [
                    {
                      "field": "singlePoint",
                      "equals": false
                    }
                  ]
                }
              },
              {
                "name": "backgroundOpacity",
                "type": "text",
                "label": "Background opacity for draggables",
                "importance": "low",
                "description": "If this field is set, it will override opacity set on all draggable elements. This should be a number between 0 and 100, where 0 means full transparency and 100 means no transparency",
                "optional": true
              },
              {
                "name": "dropZoneHighlighting",
                "type": "select",
                "label": "Drop Zone Highlighting",
                "importance": "low",
                "description": "Choose when to highlight drop zones.",
                "default": "dragging",
                "options": [
                  {
                    "value": "dragging",
                    "label": "When dragging"
                  },
                  {
                    "value": "always",
                    "label": "Always"
                  },
                  {
                    "value": "never",
                    "label": "Never"
                  }
                ]
              },
              {
                "name": "autoAlignSpacing",
                "type": "number",
                "label": "Spacing for Auto-Align (in px)",
                "importance": "low",
                "min": 0,
                "default": 2,
                "optional": true
              },
              {
                "name": "enableFullScreen",
                "label": "Enable FullScreen",
                "importance": "low",
                "type": "boolean",
                "description": "Check this option to enable the full screen button.",
                "default": false
              },
              {
                "name": "showScorePoints",
                "type": "boolean",
                "label": "Show score points",
                "description": "Show points earned for each answer. Not available when the Give one point for the whole task option is enabled.",
                "importance": "low",
                "default": true
              },
              {
                "name": "showTitle",
                "type": "boolean",
                "label": "Show Title",
                "importance": "low",
                "description": "Uncheck this option if you do not want this title to be displayed. The title will only be displayed in summaries, statistics etc.",
                "default": true
              }
            ]
          },
          {
            "name": "localize",
            "type": "group",
            "label": "Localize",
            "common": true,
            "fields": [
              {
                "name": "fullscreen",
                "type": "text",
                "label": "Fullscreen label",
                "default": "Fullscreen"
              },
              {
                "name": "exitFullscreen",
                "type": "text",
                "label": "Exit fullscreen label",
                "default": "Exit fullscreen"
              }
            ]
          },
          {
            "name": "grabbablePrefix",
            "type": "text",
            "label": "Grabbable prefix",
            "importance": "low",
            "default": "Grabbable {num} of {total}.",
            "common": true
          },
          {
            "name": "grabbableSuffix",
            "type": "text",
            "label": "Grabbable suffix",
            "importance": "low",
            "default": "Placed in dropzone {num}.",
            "common": true
          },
          {
            "name": "dropzonePrefix",
            "type": "text",
            "label": "Dropzone prefix",
            "importance": "low",
            "default": "Dropzone {num} of {total}.",
            "common": true
          },
          {
            "name": "noDropzone",
            "type": "text",
            "label": "No dropzone selection label",
            "importance": "low",
            "default": "No dropzone.",
            "common": true
          },
          {
            "name": "tipLabel",
            "type": "text",
            "label": "Label for show tip button",
            "importance": "low",
            "default": "Show tip.",
            "common": true
          },
          {
            "name": "tipAvailable",
            "type": "text",
            "label": "Label for tip available",
            "importance": "low",
            "default": "Tip available",
            "common": true
          },
          {
            "name": "correctAnswer",
            "type": "text",
            "label": "Label for correct answer",
            "importance": "low",
            "default": "Correct answer",
            "common": true
          },
          {
            "name": "wrongAnswer",
            "type": "text",
            "label": "Label for incorrect answer",
            "importance": "low",
            "default": "Wrong answer",
            "common": true
          },
          {
            "name": "feedbackHeader",
            "type": "text",
            "label": "Header for panel containing feedback for correct/incorrect answers",
            "importance": "low",
            "default": "Feedback",
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
            "name": "scoreExplanationButtonLabel",
            "type": "text",
            "label": "Textual representation of the score explanation button",
            "default": "Show score explanation",
            "importance": "low",
            "common": true
          },
          {
            "name": "a11yCheck",
            "type": "text",
            "label": "Assistive technology label for \"Check\" button",
            "default": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
            "importance": "low",
            "common": true
          },
          {
            "name": "a11yRetry",
            "type": "text",
            "label": "Assistive technology label for \"Retry\" button",
            "default": "Retry the task. Reset all responses and start the task over again.",
            "importance": "low",
            "common": true
          }
        ]';
    }

    //H5P.AdvancedBlanks-1.0
    private function updatedAdvancedBlanksSemantics() { 
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
                        "pattern": "^[\\\w\\\d]*$"
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
            "label": "Behaviour settings",
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
        ]
        ';
    }
}
