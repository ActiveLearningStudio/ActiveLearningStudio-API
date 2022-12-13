<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class H5PArithmaticQuizSubmitButtonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pArithmeticQuizLibParams = ['name' => "H5P.ArithmeticQuiz", "major_version" => 1, "minor_version" => 1];
        $h5pArithmeticQuizLib = DB::table('h5p_libraries')->where($h5pArithmeticQuizLibParams)->first();
        if ($h5pArithmeticQuizLib) {
            DB::table('h5p_libraries')->where($h5pArithmeticQuizLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
    }

    private function updatedSemantics() {
        return '[
          {
            "name": "intro",
            "type": "text",
            "label": "Intro",
            "importance": "high",
            "description": "The intro text (maximum 100 characters)",
            "maxLength": 100,
            "optional": true
          },
          {
            "name": "quizType",
            "type": "select",
            "label": "Quiz type",
            "importance": "high",
            "options": [
              {
                "value": "arithmetic",
                "label": "Arithmetic Operations Quiz"
              },
              {
                "value": "linearEquation",
                "label": "Linear Equations Quiz"
              }
            ],
            "default": "arithmetic"
          },
          {
            "name": "arithmeticType",
            "type": "select",
            "label": "Arithmetic type",
            "widget": "showWhen",
            "showWhen": {
              "rules": [
                {
                  "field": "quizType",
                  "equals": [
                    "arithmetic"
                  ]
                }
              ]
            },
            "importance": "high",
            "options": [
              {
                "value": "addition",
                "label": "Addition"
              },
              {
                "value": "subtraction",
                "label": "Subtraction"
              },
              {
                "value": "multiplication",
                "label": "Multiplication"
              },
              {
                "value": "division",
                "label": "Division"
              }
            ],
            "default": "addition"
          },
          {
            "name": "equationType",
            "type": "select",
            "label": "Equation type",
            "widget": "showWhen",
            "showWhen": {
              "rules": [
                {
                  "field": "quizType",
                  "equals": [
                    "linearEquation"
                  ]
                }
              ]
            },
            "importance": "high",
            "options": [
              {
                "value": "basic",
                "label": "Basic [ 3x = 12 ]"
              },
              {
                "value": "intermediate",
                "label": "Intermediate [ 4x - 3 = 13 ]"
              },
              {
                "value": "advanced",
                "label": "Advanced [ 5x + 3 = 3x + 15 ]"
              }
            ],
            "default": "intermediate"
          },
          {
            "name": "useFractions",
            "type": "boolean",
            "label": "Enable fractions",
            "description": "Enable to allow fractions in equations.",
            "default": false,
            "widget": "showWhen",
            "showWhen": {
              "rules": [
                {
                  "field": "quizType",
                  "equals": [
                    "linearEquation"
                  ]
                }
              ]
            }
          },
          {
            "name": "maxQuestions",
            "type": "number",
            "label": "Max number of questions",
            "importance": "medium",
            "default": 20,
            "min": 2,
            "max": 100
          },
          {
            "name": "UI",
            "type": "group",
            "label": "User interface translations",
            "importance": "low",
            "common": true,
            "fields": [
              {
                "name": "score",
                "type": "text",
                "label": "Scoring during quiz",
                "importance": "low",
                "default": "Score:"
              },
              {
                "name": "time",
                "type": "text",
                "label": "Time",
                "importance": "low",
                "default": "Time: @time"
              },
              {
                "name": "resultPageHeader",
                "type": "text",
                "label": "Result page header",
                "importance": "low",
                "default": "Finished!"
              },
              {
                "name": "go",
                "type": "text",
                "label": "Go label",
                "importance": "low",
                "default": "GO!"
              },
              {
                "name": "startButton",
                "type": "text",
                "label": "Start button label",
                "importance": "low",
                "default": "Start"
              },
              {
                "name": "retryButton",
                "type": "text",
                "label": "Retry button label",
                "importance": "low",
                "default": "Retry"
              },
              {
                "name": "correctText",
                "type": "text",
                "label": "Readspeaker text for correct answer",
                "importance": "low",
                "default": "Correct"
              },
              {
                "name": "incorrectText",
                "type": "text",
                "label": "Readspeaker text for incorrect answer",
                "importance": "low",
                "default": "Incorrect. Correct answer was :num"
              },
              {
                "name": "durationLabel",
                "type": "text",
                "label": "Readspeaker label for the timer",
                "importance": "low",
                "default": "Duration in hours, minutes and seconds."
              },
              {
                "name": "humanizedQuestion",
                "type": "text",
                "label": "Readspeaker text for question",
                "importance": "low",
                "default": "What does :arithmetic equal?"
              },
              {
                "name": "humanizedEquation",
                "type": "text",
                "label": "Readspeaker text for equation question",
                "importance": "low",
                "default": "For the equation :equation, what does :item equal?"
              },
              {
                "name": "humanizedVariable",
                "type": "text",
                "label": "Readspeaker text for equation variable",
                "importance": "low",
                "default": "What does :item equal?"
              },
              {
                "name": "plusOperator",
                "type": "text",
                "label": "Plus operator text",
                "importance": "low",
                "default": "plus"
              },
              {
                "name": "subtractionOperator",
                "type": "text",
                "label": "Subtraction operator text",
                "importance": "low",
                "default": "minus"
              },
              {
                "name": "multiplicationOperator",
                "type": "text",
                "label": "Multiplication operator text",
                "importance": "low",
                "default": "times"
              },
              {
                "name": "divisionOperator",
                "type": "text",
                "label": "Division operator text",
                "importance": "low",
                "default": "delt på"
              },
              {
                "name": "equalitySign",
                "type": "text",
                "label": "Equality sign text",
                "importance": "low",
                "default": "equals"
              },
              {
                "name": "slideOfTotal",
                "type": "text",
                "label": "Slide number text",
                "importance": "low",
                "description": "Announces current slide and total number of slides, variables are :num and :total",
                "default": "Slide :num of :total"
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
        ]
        
        ';
    }
}
