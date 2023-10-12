<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class H5PAddCrossWordsH5PLibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pCrossWordLibParams = ['name' => "H5P.Crossword", "major_version" => 0, "minor_version" => 5];
        $h5pCrossWordLib = DB::table('h5p_libraries')->where($h5pCrossWordLibParams)->first();

      if (empty($h5pCrossWordLib)) {
          $h5pCrossWordLibId = DB::table('h5p_libraries')->insertGetId([
                          'name' => 'H5P.Crossword',
                          'title' => 'Crossword',
                          'major_version' => 0,
                          'minor_version' => 5,
                          'patch_version' => 2,
                          'embed_types' => 'iframe',
                          'runnable' => 1,
                          'restricted' => 0,
                          'fullscreen' => 0,
                          'preloaded_js' => 'dist/h5p-crossword.js',
                          'preloaded_css' => 'dist/h5p-crossword.css',
                          'drop_library_css' => '',
                          'semantics' => $this->getSemantics(),
                          'tutorial_url' => ' ',
                          'has_icon' => 1
          ]);

          // insert dependent libraries
          $this->insertDependentLibraries($h5pCrossWordLibId);

          // insert libraries languages
          $this->insertLibrariesLanguages($h5pCrossWordLibId);

          $localURL = public_path('storage/activity-items/');
          $storageURL = '/storage/activity-items/';

          $crosswordImg = 'W4rGpxu3Dnt7KvQ8J5HEc6o9m2RNLy1bXU0ITsMQjYhZCVdfkqzSPeiFAlOwG.png';

          $organizations = DB::table('organizations')->pluck('id');
          $currentDate = now();

          $this->insertActivityItem($localURL, $crosswordImg, $organizations, $storageURL, $currentDate);
      }

    }

    /**
     * Insert Dependent Libraries
     * @param $h5pCrossWordLibId
     */
    private function insertDependentLibraries($h5pCrossWordLibId)
    {
        //Preloaded Dependencies
        $h5pQuestionParams = ['name' => "H5P.Question", "major_version" => 1, "minor_version" => 5];
        $h5pQuestionLib = DB::table('h5p_libraries')->where($h5pQuestionParams)->first();
        $h5pQuestionLibId = $h5pQuestionLib->id;

        $h5pJoubelUIParams = ['name' => "H5P.JoubelUI", "major_version" => 1, "minor_version" => 3];
        $h5pJoubelUILib = DB::table('h5p_libraries')->where($h5pJoubelUIParams)->first();
        $h5pJoubelUILibId = $h5pJoubelUILib->id;

        $h5pImageParams = ['name' => "H5P.Image", "major_version" => 1, "minor_version" => 1];
        $h5pImageParamsLib = DB::table('h5p_libraries')->where($h5pImageParams)->first();
        $h5pImageLibId = $h5pImageParamsLib->id;

        $h5pMaterialDesignIconsParams = ['name' => "H5P.MaterialDesignIcons", "major_version" => 1, "minor_version" => 0];
        $h5pMaterialDesignIconsLib = DB::table('h5p_libraries')->where($h5pMaterialDesignIconsParams)->first();
        $h5pMaterialDesignIconsLibId = $h5pMaterialDesignIconsLib->id;


        // Editor Dependencies
        $h5pVerticalTabsParams = ['name' => "H5PEditor.VerticalTabs", "major_version" => 1, "minor_version" => 3];
        $h5pVerticalTabsLib = DB::table('h5p_libraries')->where($h5pVerticalTabsParams)->first();
        $h5pVerticalTabsLibId = $h5pVerticalTabsLib->id;

        $h5pEditorRangeListParams = ['name' => "H5PEditor.RangeList", "major_version" => 1, "minor_version" => 0];
        $h5pEditorRangeListLib = DB::table('h5p_libraries')->where($h5pEditorRangeListParams)->first();
        $h5pEditorRangeListLibId = $h5pEditorRangeListLib->id;

        $h5pEditorShowWhenParams = ['name' => "H5PEditor.ShowWhen", "major_version" => 1, "minor_version" => 0];
        $h5pEditorShowWhenLib = DB::table('h5p_libraries')->where($h5pEditorShowWhenParams)->first();
        $h5pEditorShowWhenLibId = $h5pEditorShowWhenLib->id;


        $h5pColorSelectorParams = ['name' => "H5PEditor.ColorSelector", "major_version" => 1, "minor_version" => 3];
        $h5pColorSelectorParamsLib = DB::table('h5p_libraries')->where($h5pColorSelectorParams)->first();
        $h5pColorSelectorParamsLibId = $h5pColorSelectorParamsLib->id;



        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pQuestionLibId,
            'dependency_type' => 'preloaded'
        ]);
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pJoubelUILibId,
            'dependency_type' => 'preloaded'
        ]);
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pImageLibId,
            'dependency_type' => 'preloaded'
        ]);
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pMaterialDesignIconsLibId,
            'dependency_type' => 'preloaded'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pVerticalTabsLibId,
            'dependency_type' => 'editor'
        ]);
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pEditorRangeListLibId,
            'dependency_type' => 'editor'
        ]);
        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pEditorShowWhenLibId,
            'dependency_type' => 'editor'
        ]);

        DB::table('h5p_libraries_libraries')->insert([
            'library_id' => $h5pCrossWordLibId,
            'required_library_id' => $h5pColorSelectorParamsLibId,
            'dependency_type' => 'editor'
        ]);

    }


    private function getSemantics() {
      return '[
  {
    "name": "taskDescription",
    "label": "Task description",
    "type": "text",
    "widget": "html",
    "importance": "high",
    "description": "Describe your task here.",
    "enterMode": "div",
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
    ],
    "optional": true
  },
  {
    "name": "words",
    "label": "words",
    "importance": "high",
    "type": "list",
    "widgets": [
      {
        "name": "VerticalTabs",
        "label": "Default"
      }
    ],
    "min": 2,
    "entity": "Word",
    "field": {
      "name": "word",
      "type": "group",
      "label": "Word",
      "fields": [
        {
          "name": "clue",
          "type": "text",
          "label": "Clue",
          "description": "Clue that should point to the answer.",
          "importance": "medium"
        },
        {
          "name": "answer",
          "type": "text",
          "label": "Answer",
          "description": "Answer to the clue.",
          "importance": "medium"
        },
        {
          "name": "extraClue",
          "type": "library",
          "label": "Extra clue",
          "optional": true,
          "options": [
            "H5P.AdvancedText 1.1",
            "H5P.Image 1.1",
            "H5P.Audio 1.5",
            "H5P.Video 1.6"
          ]
        },
        {
          "name": "fixWord",
          "type": "boolean",
          "label": "Fix word on grid",
          "description": "Check if you want to fix the word to a particular position on the grid. Words with the same alignment may not be placed touching each other.",
          "importance": "low",
          "default": false,
          "optional": true
        },
        {
          "name": "row",
          "type": "number",
          "label": "Row",
          "description": "Row index where the answer should start.",
          "min": 1,
          "max": 100,
          "importance": "low",
          "widget": "showWhen",
          "showWhen": {
            "rules": [
              {
                "field": "fixWord",
                "equals": true
              }
            ]
          }
        },
        {
          "name": "column",
          "type": "number",
          "label": "Column",
          "description": "Column index where the answer should start.",
          "min": 1,
          "max": 100,
          "importance": "low",
          "widget": "showWhen",
          "showWhen": {
            "rules": [
              {
                "field": "fixWord",
                "equals": true
              }
            ]
          }
        },
        {
          "name": "orientation",
          "type": "select",
          "label": "Orientation",
          "description": "Orientation for the answer.",
          "options": [
            {
              "value": "across",
              "label": "Across"
            },
            {
              "value": "down",
              "label": "Down"
            }
          ],
          "default": "across",
          "widget": "showWhen",
          "showWhen": {
            "rules": [
              {
                "field": "fixWord",
                "equals": true
              }
            ]
          }
        }
      ]
    }
  },
  {
    "name": "solutionWord",
    "type": "text",
    "label": "Overall solution word",
    "description": "Optionally add a solution word that can be derived from particular characters on the grid. It will only be visible if all its characters can be found in the crossword. Please note: There\'s no accessibility support for this feature yet.",
    "importance": "low",
    "optional": "true"
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
    "name": "theme",
    "type": "group",
    "label": "Theme",
    "importance": "low",
    "fields": [
      {
        "name": "backgroundImage",
        "label": "Background image",
        "type": "image",
        "description": "Select an optional background image. It will be scaled to fit the background without stretching it.",
        "importance": "low",
        "optional": true
      },
      {
        "name": "backgroundColor",
        "type": "text",
        "label": "Background color",
        "description": "Choose a background color. It will either be used instead of a background image or as background for transparent areas.",
        "importance": "low",
        "optional": true,
        "default": "#173354",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
        }
      },
      {
        "name": "gridColor",
        "type": "text",
        "label": "Grid color",
        "description": "Choose a color for the grid.",
        "importance": "low",
        "optional": true,
        "default": "#000000",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
        }
      },
      {
        "name": "cellBackgroundColor",
        "type": "text",
        "label": "Cell background color",
        "description": "Choose the background color for a table cell.",
        "importance": "low",
        "optional": true,
        "default": "#ffffff",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
        }
      },
      {
        "name": "cellColor",
        "type": "text",
        "label": "Cell text color",
        "description": "Choose the text color for a table cell.",
        "importance": "low",
        "optional": true,
        "default": "#000000",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
        }
      },
      {
        "name": "clueIdColor",
        "type": "text",
        "label": "Clue id color",
        "description": "Choose the color of the clue id inside a table cell.",
        "importance": "low",
        "optional": true,
        "default": "#606060",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
        }
      },
      {
        "name": "cellBackgroundColorHighlight",
        "type": "text",
        "label": "Cell background color (highlighted)",
        "description": "Choose the background color for a table cell that is highlighted.",
        "importance": "low",
        "optional": true,
        "default": "#3e8de8",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
        }
      },
      {
        "name": "cellColorHighlight",
        "type": "text",
        "label": "Cell text color (highlighted)",
        "description": "Choose the text color for a table cell that is highlighted.",
        "importance": "low",
        "optional": true,
        "default": "#ffffff",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
        }
      },
      {
        "name": "clueIdColorHighlight",
        "type": "text",
        "label": "Cell id color (highlighted)",
        "description": "Choose the color of the clue id inside a table cell that is highlighted.",
        "importance": "low",
        "optional": true,
        "default": "#e0e0e0",
        "widget": "colorSelector",
        "spectrum": {
          "showInput": true,
          "showInitial": true
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
    "fields": [
      {
        "name": "poolSize",
        "type": "number",
        "min": 2,
        "label": "Number of words to be shown",
        "importance": "low",
        "description": "Create a randomized batch of words from the pool. The batch will always contain at least all the words that were fixed on the grid regardless of this setting. An empty value or 0 means to use all words.",
        "optional": true
      },
      {
        "name": "enableInstantFeedback",
        "label": "Enable instant feedback",
        "type": "boolean",
        "importance": "low",
        "default": false
      },
      {
        "name": "scoreWords",
        "label": "Score words",
        "description": "If this option is enabled, words will be counted in order to determine the score. Otherwise, correct characters will be counted.",
        "type": "boolean",
        "importance": "low",
        "default": true
      },
      {
        "name": "applyPenalties",
        "label": "Apply penalties",
        "description": "If this option is enabled, each wrong answer will be given a penalty score of -1.",
        "type": "boolean",
        "importance": "low",
        "default": false,
        "optional": true
      },
      {
        "name": "enableRetry",
        "label": "Enable \"Retry\"",
        "type": "boolean",
        "importance": "low",
        "default": true,
        "optional": true
      },
      {
        "name": "enableSolutionsButton",
        "label": "Enable \"Solution\"",
        "type": "boolean",
        "importance": "low",
        "default": true,
        "optional": true
      }
    ]
  },
  {
    "name": "l10n",
    "type": "group",
    "common": true,
    "label": "User interface",
    "importance": "low",
    "fields": [
      {
        "name": "across",
        "type": "text",
        "label": "Label for across",
        "importance": "low",
        "default": "Across"
      },
      {
        "name": "down",
        "type": "text",
        "label": "Label for down",
        "importance": "low",
        "default": "Down"
      },
      {
        "name": "checkAnswer",
        "type": "text",
        "label": "Text for \"Check\" button",
        "importance": "low",
        "default": "Check"
      },
      {
        "name": "submitAnswer",
        "type": "text",
        "label": "Text for \"Submit\" button",
        "importance": "low",
        "default": "Submit"
      },
      {
        "name": "tryAgain",
        "label": "Text for \"Retry\" button",
        "type": "text",
        "importance": "low",
        "default": "Retry"
      },
      {
        "name": "showSolution",
        "type": "text",
        "label": "Text for \"Show solution\" button",
        "importance": "low",
        "default": "Show solution"
      },
      {
        "name": "couldNotGenerateCrossword",
        "type": "text",
        "label": "Cannot create crossword",
        "importance": "low",
        "default": "Could not generate a crossword with the given words. Please try again with fewer words or words that have more characters in common."
      },
      {
        "name": "couldNotGenerateCrosswordTooFewWords",
        "type": "text",
        "label": "Cannot create crossword (too few words)",
        "importance": "low",
        "default": "Could not generate a crossword. You need at least two words."
      },
      {
        "name": "probematicWords",
        "type": "text",
        "label": "Probematic words",
        "description": "List words that caused trouble while generating the crossword. @words is a placeholder that will be replaced by the actual problematic words.",
        "importance": "low",
        "default": "Some words could not be placed. If you are using fixed words, please make sure that their position doesn\'t prevent other words from being placed. Words with the same alignment may not be placed touching each other. Problematic word(s): @words"
      },
      {
        "name": "extraClue",
        "type": "text",
        "label": "Extra clue",
        "importance": "low",
        "default": "Extra clue"
      },
      {
        "name": "closeWindow",
        "type": "text",
        "label": "Close window",
        "importance": "low",
        "default": "Close window"
      }
    ]
  },
  {
    "name": "a11y",
    "type": "group",
    "common": true,
    "label": "Readspeaker",
    "importance": "low",
    "fields": [
      {
        "name": "crosswordGrid",
        "type": "text",
        "label": "Crossword grid description",
        "importance": "low",
        "default": "Crossword grid. Use arrow keys to navigate and the keyboard to enter characters. Alternatively, use Tab to navigate to type the answers in Fill in the Blanks style fields instead of the grid."
      },
      {
        "name": "column",
        "type": "text",
        "label": "Column",
        "importance": "low",
        "default": "Column"
      },
      {
        "name": "row",
        "type": "text",
        "label": "Row",
        "importance": "low",
        "default": "Row"
      },
      {
        "name": "across",
        "type": "text",
        "label": "Across",
        "importance": "low",
        "default": "Across"
      },
      {
        "name": "down",
        "type": "text",
        "label": "Down",
        "importance": "low",
        "default": "Down"
      },
      {
        "name": "empty",
        "type": "text",
        "label": "Empty",
        "importance": "low",
        "default": "Empty"
      },
      {
        "name": "resultFor",
        "description": "Result announcer. @clue is a variable and will be replaced by the respective clue.",
        "type": "text",
        "label": "Result for",
        "importance": "low",
        "default": "Result for: @clue"
      },
      {
        "name": "correct",
        "type": "text",
        "label": "Correct",
        "importance": "low",
        "default": "Correct"
      },
      {
        "name": "wrong",
        "type": "text",
        "label": "Wrong",
        "importance": "low",
        "default": "Wrong"
      },
      {
        "name": "point",
        "type": "text",
        "label": "Point",
        "importance": "low",
        "default": "point"
      },
      {
        "name": "solutionFor",
        "description": "Solution announcer. @clue and @solution are variables and will be replaced by the respective values.",
        "type": "text",
        "label": "Solution for",
        "importance": "low",
        "default": "For @clue the solution is: @solution"
      },
      {
        "name": "extraClueFor",
        "description": "Extra clue announcer. @clue is a variable and will be replaced by the respective value.",
        "type": "text",
        "label": "Extra clue for",
        "importance": "low",
        "default": "Open extra clue for @clue"
      },
      {
        "name": "letterSevenOfNine",
        "description": "Announcing the current position. @position and @length are variables and will be replaced by their respective values.",
        "type": "text",
        "label": "Letter",
        "importance": "low",
        "default": "Letter @position of @length"
      },
      {
        "name": "lettersWord",
        "description": "Announcing the word length. @length is a variable and will be replaced by the respective value.",
        "type": "text",
        "label": "Length of word",
        "importance": "low",
        "default": "@length letter word"
      },
      {
        "name": "check",
        "type": "text",
        "label": "Assistive technology description for \"Check\" button",
        "importance": "low",
        "default": "Check the characters. The responses will be marked as correct, incorrect, or unanswered."
      },
      {
        "name": "showSolution",
        "type": "text",
        "label": "Assistive technology description for \"Show Solution\" button",
        "importance": "low",
        "default": "Show the solution. The crossword will be filled with its correct solution."
      },
      {
        "name": "retry",
        "type": "text",
        "label": "Assistive technology description for \"Retry\" button",
        "importance": "low",
        "default": "Retry the task. Reset all responses and start the task over again."
      },
      {
        "name": "yourResult",
        "type": "text",
        "label": "Your result",
        "description": "@score and @total are variables and will be replaced by their respective values.",
        "importance": "low",
        "default": "You got @score out of @total points"
      }
    ]
  }
]
';

  }

    private function insertLibrariesLanguages(int $h5pCrossWordLibId)
    {

        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pCrossWordLibId,
            'language_code' => 'ar',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"وصف المهام","description":"صِف مهمتك هنا."},{"label":"الكلمات","widgets":[{"label":"افتراضي"}],"entity":"كلمة","field":{"label":"كلمة","fields":[{"label":"الدليل","description":"الدليل الذي يجب أن يشير إلى الإجابة."},{"label":"الإجابة","description":"الإجابة على الدليل."},{"label":"دليل إضافي"},{"label":"تثبيت الكلمة على الشبكية","description":"تحقق مما إذا كنت تريد تثبيت الكلمة في موضع معين على الشبكية."},{"label":"الصف","description":"رقم الصف حيث يجب أن تبدأ الإجابة."},{"label":"العمود","description":"رقم العمود حيث يجب أن تبدأ الإجابة."},{"label":"الميل","description":"ميل الإجابة.","options":[{"label":"جانبي"},{"label":"لأسفل"}]}]}},{"label":"كلمة الحل الشامل","description":"أضف اختيارياً كلمة حل يمكن اشتقاقها من أحرف معينة على الشبكية. ستكون مرئية فقط إذا عُثر على جميع أحرفها في الكلمات المتقاطعة. يرجى الملاحظة: لا يوجد دعم لإمكانية الوصول لهذه الميزة حتى الآن."},{"label":"الملاحظات الإجمالية","fields":[{"widgets":[{"label":"افتراضي"}],"label":"تحديد التعليقات المخصصة للحصول على نطاق علامة في أي مجال","description":"انقر زر ”إضافة نطاق“ لإضافة أي عدد تريده. مثال: يعد متوسّط العلامة من 0 - 20% علامة سيئة، في حين أن العلامة من 21 - 91% علامة متوسطة، بينما العلامة من 91 - 100% علامة رائعة!","entity":"المجال","field":{"fields":[{"label":"نطاق العلامة"},{},{"label":"ملاحظات لنطاق علامة محدد","placeholder":"كتابة الملاحظات"}]}}]},{"label":"السمة","fields":[{"label":"صورة الخلفية","description":"حدد صورة الخلفية الاختيارية. سيعدل حجمها لتناسب الخلفية دون تمديدها."},{"label":"لون الخلفية","description":"اختر لون الخلفية. سيستخدم إما بديلًا لصورة الخلفية أو كخلفية للمناطق الشفافة.","default":"#173354"},{"label":"لون الشبكية","description":"اختر لوناً للشبكية.","default":"#000000"},{"label":"لون خلفية للخلية","description":"اختر لون الخلفية لخلية الجدول.","default":"#ffffff"},{"label":"لون نص الخلية","description":"اختر لون النص لخلية الجدول.","default":"#000000"},{"label":"لون معرف الدليل","description":"اختر لون الدليل الموجود داخل خلية الجدول.","default":"#606060"},{"label":"لون الخلفية للخلية (مميز)","description":"اختر لون الخلفية لخلية الجدول المميزة.","default":"#3e8de8"},{"label":"لون نص الخلية (مميز)","description":"اختر لون النص لخلية الجدول المميزة.","default":"#ffffff"},{"label":"لون معرف الخلية (مميز)","description":"اختر لون معرف الدليل داخل خلية الجدول المميزة.","default":"#e0e0e0"}]},{"fields":[{"label":"عدد الكلمات التي ستعرض","description":"أنشئ مجموعة ترتيب عشوائي من الكلمات من المخزنة. ستحتوي المجموعة دائماً على الأقل على كل الكلمات التي تم إصلاحها على الشبكية بغض النظر عن هذا الإعداد. القيمة الفارغة أو 0 تعني استخدام كل الكلمات."},{"label":"قم بتمكين الملاحظات الفورية"},{"label":"كلمات العلامة","description":"إذا مُكن هذا الخيار، فستحتسب الكلمات لتحديد العلامة. خلاف ذلك، ستحتسب الأحرف الصحيحة."},{"label":"تطبيق العقوبات","description":"إذا مُكن هذا الخيار، فستمنح كل إجابة خاطئة علامة عقوبة تساوي -1."},{"label":"تمكين \"إعادة المحاولة\""},{"label":"تمكين \"الحل\""}],"label":"الإعدادات السلوكية","description":"ستتيح لك هذه الخيارات التحكم في كيفية عمل المهمة."},{"fields":[{"label":"التسمية للجانبي","default":"جانبي"},{"label":"تسمية لأسفل","default":"لأسفل"},{"label":"نص زر \"تحقق\"","default":"تحقق"},{"label":"نص لزر إرسال","default":"إرسال"},{"label":"نص زر \"إعادة المحاولة\"","default":"إعادة المحاولة"},{"label":"نص زر \"عرض الحل\"","default":"عرض الحل"},{"label":"لا يمكن إنشاء الكلمات المتقاطعة","default":"تعذر إنشاء الكلمات المتقاطعة بالكلمات المحددة. يرجى المحاولة مرة أخرى باستخدام عدد أقل من الكلمات أو الكلمات التي لها عدد أكبر من الأحرف المشتركة. إذا كنت تستخدم كلمات ثابتة، فيرجى التأكد من أن موضعها لا يمنع وضع كلمات أخرى."},{"label":"لا يمكن إنشاء كلمات متقاطعة (عدد الكلمات قليل جداً)","default":"لا يمكن إنشاء الكلمات المتقاطعة. أنت بحاجة إلى كلمتين على الأقل."},{"label":"الكلمات الاحتمالية","description":"ضع قائمة بالكلمات التي تسببت في مشكلة أثناء إنشاء الكلمات المتقاطعة. @words هو حيز مخصص سيستبدل بكلمات إشكالية فعلية.","default":"كلمة (كلمات) إشكالية: @words"},{"label":"دليل إضافي","default":"دليل إضافي"},{"label":"إغلاق النافذة","default":"إغلاق النافذة"}],"label":"واجهة المستخدم"},{"label":"برنامج ReadSpeaker","fields":[{"label":"وصف شبكية الكلمات المتقاطعة","default":"شبكية الكلمات المتقاطعة. استخدم مفاتيح الأسهم للتنقل ولوحة المفاتيح لإدخال الأحرف. استخدم مفتاح Tab لاستخدام حقول الإدخال بدلاً من ذلك."},{"label":"العمود","default":"العمود"},{"label":"الصف","default":"الصف"},{"label":"جانبي","default":"جانبي"},{"label":"لأسفل","default":"لأسفل"},{"label":"فارغة","default":"فارغة"},{"description":"معلن النتيجة. @clue هو متغير وسيستبدل بالدليل المعني.","label":"النتيجة لـ","default":"النتيجة لـ: @clue"},{"label":"صحيح","default":"صحيح"},{"label":"خطاً","default":"خطاً"},{"label":"نقطة","default":"نقطة"},{"description":"معلن الحل. @clue و @solution هي متغيرات وستستبدل بالقيم الخاصة بها.","label":"الحل لـ","default":"ل @clue الحل هو: @solution"},{"description":"معلن الدليل الإضافي. @clue هو متغير وسيستبدل بالقيمة المعنية.","label":"الدليل الإضافي لـ","default":"فتح دليل إضافي لـ @clue"},{"description":"إعلان المركز الحالي. @position و @length هي متغيرات وستستبدل بقيمها الخاصة.","label":"حرف","default":"حرف @position من @length"},{"description":"إعلان طول الكلمة. @length هو متغير وسيستبدل بالقيمة المناظرة.","label":"طول الكلمة","default":"@length حروف الكلمة"},{"label":"وصف التكنولوجيا المساعدة لزر التحقق","default":"تحقق من الأحرف. سيتم تحديد الردود بأنها صحيحة أو غير صحيحة أو لم يتم الإجابة عليها."},{"label":"وصف تقنية المساعدة لزر \"عرض الحلول\"","default":"اعرض الحل. ستملأ الكلمات المتقاطعة بحلها الصحيح."},{"label":"وصف التقنية المساعدة لزر إعادة المحاولة","default":"إعادة المحاولة للمهمة. إعادة ضبط جميع الردود والبدء بالمهمة مرة أخرى."},{"label":"نتيجتك","description":"@score و @total هي متغيرات وستستبدل بقيمها الخاصة.","default":"لقد حصلت على @score من أصل @total نقاط."}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pCrossWordLibId,
            'language_code' => 'ca',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Task description","description":"Describe your task here."},{"widgets":[{"label":"Default"}],"entity":"Word","field":{"label":"Word","fields":[{"label":"Clue","description":"Clue that should point to the answer."},{"label":"Answer","description":"Answer to the clue."},{"label":"Extra clue"},{"description":"Check if you want to fix the word to a particular position on the grid. Words with the same alignment may not be placed touching each other.","label":"Fix word on grid"},{"label":"Row","description":"Row index where the answer should start."},{"label":"Column","description":"Column index where the answer should start."},{"label":"Orientation","options":[{"label":"Across"},{"label":"Down"}],"description":"Orientation for the answer."}]},"label":"words"},{"label":"Overall solution word","description":"Optionally add a solution word that can be derived from particular characters on the grid. It will only be visible if all its characters can be found in the crossword. Please note: There\'s no accessibility support for this feature yet."},{"fields":[{"entity":"range","widgets":[{"label":"Default"}],"label":"Define custom feedback for any score range","description":"Click the \"Add range\" button to add as many ranges as you need. Example: 0-20% Bad score, 21-91% Average Score, 91-100% Great Score!","field":{"fields":[{"label":"Score Range"},{},{"label":"Feedback for defined score range","placeholder":"Fill in the feedback"}]}}],"label":"Overall Feedback"},{"label":"Theme","fields":[{"label":"Background image","description":"Select an optional background image. It will be scaled to fit the background without stretching it."},{"label":"Background color","description":"Choose a background color. It will either be used instead of a background image or as background for transparent areas.","default":"#173354"},{"label":"Grid color","description":"Choose a color for the grid.","default":"#000000"},{"label":"Cell background color","description":"Choose the background color for a table cell.","default":"#ffffff"},{"label":"Cell text color","description":"Choose the text color for a table cell.","default":"#000000"},{"label":"Clue id color","description":"Choose the color of the clue id inside a table cell.","default":"#606060"},{"label":"Cell background color (highlighted)","description":"Choose the background color for a table cell that is highlighted.","default":"#3e8de8"},{"label":"Cell text color (highlighted)","description":"Choose the text color for a table cell that is highlighted.","default":"#ffffff"},{"label":"Cell id color (highlighted)","description":"Choose the color of the clue id inside a table cell that is highlighted.","default":"#e0e0e0"}]},{"fields":[{"description":"Create a randomized batch of words from the pool. The batch will always contain at least all the words that were fixed on the grid regardless of this setting. An empty value or 0 means to use all words.","label":"Number of words to be shown"},{"label":"Enable instant feedback"},{"description":"If this option is enabled, words will be counted in order to determine the score. Otherwise, correct characters will be counted.","label":"Score words"},{"label":"Apply penalties","description":"If this option is enabled, each wrong answer will be given a penalty score of -1."},{"label":"Enable \"Retry\""},{"label":"Enable \"Solution\""}],"label":"Behavioural settings","description":"These options will let you control how the task behaves."},{"label":"User interface","fields":[{"label":"Label for across","default":"Across"},{"label":"Label for down","default":"Down"},{"label":"Text for \"Check\" button","default":"Check"},{"label":"Text for \"Submit\" button","default":"Submit"},{"label":"Text for \"Retry\" button","default":"Retry"},{"label":"Text for \"Show solution\" button","default":"Show solution"},{"label":"Cannot create crossword","default":"Could not generate a crossword with the given words. Please try again with fewer words or words that have more characters in common."},{"label":"Cannot create crossword (too few words)","default":"Could not generate a crossword. You need at least two words."},{"label":"Probematic words","description":"List words that caused trouble while generating the crossword. @words is a placeholder that will be replaced by the actual problematic words.","default":"Some words could not be placed. If you are using fixed words, please make sure that their position doesn\'t prevent other words from being placed. Words with the same alignment may not be placed touching each other. Problematic word(s): @words"},{"label":"Extra clue","default":"Extra clue"},{"label":"Close window","default":"Close window"}]},{"fields":[{"label":"Crossword grid description","default":"Crossword grid. Use arrow keys to navigate and the keyboard to enter characters. Alternatively, use Tab to navigate to type the answers in Fill in the Blanks style fields instead of the grid."},{"label":"Column","default":"Column"},{"default":"Row","label":"Row"},{"label":"Across","default":"Across"},{"label":"Down","default":"Down"},{"label":"Empty","default":"Empty"},{"description":"Result announcer. @clue is a variable and will be replaced by the respective clue.","label":"Result for","default":"Result for: @clue"},{"label":"Correct","default":"Correct"},{"label":"Wrong","default":"Wrong"},{"label":"Point","default":"point"},{"description":"Solution announcer. @clue and @solution are variables and will be replaced by the respective values.","label":"Solution for","default":"For @clue the solution is: @solution"},{"description":"Extra clue announcer. @clue is a variable and will be replaced by the respective value.","label":"Extra clue for","default":"Open extra clue for @clue"},{"description":"Announcing the current position. @position and @length are variables and will be replaced by their respective values.","label":"Letter","default":"Letter @position of @length"},{"label":"Length of word","description":"Announcing the word length. @length is a variable and will be replaced by the respective value.","default":"@length letter word"},{"label":"Assistive technology description for \"Check\" button","default":"Check the characters. The responses will be marked as correct, incorrect, or unanswered."},{"label":"Assistive technology description for \"Show Solution\" button","default":"Show the solution. The crossword will be filled with its correct solution."},{"label":"Assistive technology description for \"Retry\" button","default":"Retry the task. Reset all responses and start the task over again."},{"label":"Your result","description":"@score and @total are variables and will be replaced by their respective values.","default":"You got @score out of @total points."}],"label":"Readspeaker"}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pCrossWordLibId,
            'language_code' => 'de',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Aufgabenbeschreibung","description":"Beschreibe deine Aufgabe hier."},{"label":"Wörter","widgets":[{"label":"Eingabemaske"}],"entity":"Wort","field":{"label":"Wort","fields":[{"label":"Hinweis","description":"Hinweis auf die Antwort."},{"label":"Antwort","description":"Answer zum Hinweis."},{"label":"Extra-Hinweis"},{"label":"Fixiere Wort im Gitter","description":"Wähle diese Option, wenn du das Wort an einer bestimmten Stelle im Gitter fixieren möchtest. Wörter mit derselben Ausrichtung dürfen sich nicht berühren."},{"label":"Zeile","description":"Zeilennummer, in der die Antwort starten soll."},{"label":"Spalte","description":"Spaltennummer, in der die Antwort starten soll."},{"label":"Ausrichtung","description":"Ausrichtung der Antwort.","options":[{"label":"Waagerecht"},{"label":"Senkrecht"}]}]}},{"label":"Lösungswort","description":"Du kannst optional ein Lösungswort angeben, das aus den Buchstaben im Gitter abgeleitet werden kann. Es wird nur sichtbar sein, wenn es genügend passende Buchstaben im Kreuzworträtsel gibt. Hinweis: Bisher ist diese Funktion nicht barrierefrei implementiert."},{"label":"Gesamtrückmeldung","fields":[{"widgets":[{"label":"Voreinstellung"}],"label":"Lege Rückmeldungen für einzelne Punktebereiche fest","description":"Klicke auf den \"Bereich hinzufügen\"-Button, um so viele Bereiche hinzuzufügen, wie du brauchst. Beispiel: 0-20% Schlechte Punktzahl, 21-91% Durchschnittliche Punktzahl, 91-100% Großartige Punktzahl!","entity":"Bereich","field":{"fields":[{"label":"Punktebereich"},{},{"label":"Rückmeldung für jeweiligen Punktebereich","placeholder":"Trage die Rückmeldung ein"}]}}]},{"label":"Theme","fields":[{"label":"Hintergrundbild","description":"Wähle ein optionales Hintergrundbild. Es wird ohne Verzerrung skaliert, um in den Hintergrund zu passen."},{"label":"Hintergrundfarbe","description":"Wähle eine Hintergrundfarbe. Sie wird entweder statt eines Hintergrundbildes benutzt oder dort wo das Bild transparent ist.","default":"#173354"},{"label":"Gitterfarbe","description":"Wähle eine Farbe für das Gitter.","default":"#000000"},{"label":"Hintergrundfarbe für Zelle","description":"Wähle die Hintergrundfarbe einer Tabellenzelle.","default":"#ffffff"},{"label":"Textfarbe für Zelle","description":"Wähle die Textfarbe einer Tabellenzelle.","default":"#000000"},{"label":"Farbe der Hinweisnummer","description":"Wähle die Farbe der Hinweisnummer innerhalb einer Tabellenzelle.","default":"#606060"},{"label":"Hintergrundfarbe für Zelle (ausgewählt)","description":"Wähle die Hintergrundfarbe einer ausgewählten Tabellenzelle.","default":"#3e8de8"},{"label":"Textfarbe für Zelle (ausgewählt)","description":"Wähle die Textfarbe einer ausgewählten Tabellenzelle.","default":"#ffffff"},{"label":"Farbe der Hinweisnummer (ausgewählt)","description":"Wähle die Farbe der Hinweisnummer innerhalb einer ausgewählten Tabellenzelle.","default":"#e0e0e0"}]},{"label":"Verhaltenseinstellungen","description":"Diese Optionen legen fest, wie die Aufgabe im Detail funktioniert.","fields":[{"label":"Anzahl der zu zeigenden Wörter","description":"Erstelle eine zufällige Auswahl der Wörter. Die Auswahl wird immer mindestens die Wörter enthalten, die im Gitter fixiert wurden. Ein leerer Wert oder 0 bedeutet alle Wörter."},{"label":"Sofort-Rückmeldungen einschalten"},{"label":"Wörter zählen","description":"Wenn diese Einstellung gewählt ist, werden Wörter für die Bewertung gezählt. Andernfalls werden Zeichen gezählt."},{"label":"Strafpunkte vergeben","description":"Wenn diese Einstellung gewählt ist, wird für jede falsche Antwort ein Punkt abgezogen."},{"label":"\"Wiederholen\"-Button anzeigen"},{"label":"Aktiviere \"Lösung anzeigen\"-Button"}]},{"label":"Bezeichnungen und Beschriftungen","fields":[{"label":"Beschriftung für waagerecht","default":"Waagerecht"},{"label":"Beschriftung für senktrecht","default":"Senkrecht"},{"label":"Beschriftung des \"Überprüfen\"-Buttons","default":"Überprüfen"},{"label":"Beschriftung des \"Absenden\"-Buttons","default":"Absenden"},{"label":"Beschriftung des \"Wiederholen\"-Buttons","default":"Wiederholen"},{"label":"Beschriftung des \"Lösung anzeigen\"-Buttons","default":"Lösung anzeigen"},{"label":"Es kann kein Kreuzworträtsel generiert werden","default":"Es konnte kein Kreuzworträtsel aus den gegebenen Wörtern erzeugt werden. Bitte versuche es erneut mit weniger Wörtern oder mit Wörtern, die mehr Buchstaben gemein haben."},{"label":"Kein Kreuzworträtsel möglich (zu wenig Wörter)","default":"Es konnte kein Kreuzworträtsel erzeugt werden. Du benötigst wenigstens zwei Wörter."},{"label":"Problematische Wörter","description":"Liste Wörter auf, die beim Generieren des Kreuzworträtsels nicht gesetzt werden konnten. @words ist ein Platzhalter, der durch die tatsächlichen Problematischen Wörter ersetzt wird.","default":"Einige Wörter konnten nicht platziert werden. Wenn du Wörter im Gitter fixierst, stelle bitte sicher, dass ihre Position das Setzen von anderen Wörtern nicht verhindert. Wörter mit derselben Ausrichtung dürfen sich nicht berühren. Problematische Wörter: @words"},{"label":"Extra-Hinweis","default":"Extra-Hinweis"},{"label":"Fenster schließen","default":"Fenster schließen"}]},{"label":"Vorlesewerkzeug (Barrierefreiheit)","fields":[{"label":"Beschreibung Kreuzwortgitter","default":"Kreuzwortgitter. Benutze die Pfeiltasten und die Tastatur zum Eingeben von Zeichen. Nutze die Tabulatortaste, um stattdessen Eingabefelder zu verwenden."},{"label":"Spalte","default":"Spalte"},{"label":"Zeile","default":"Zeile"},{"label":"Waagerecht","default":"Waagerecht"},{"label":"Senkrecht","default":"Senkrecht"},{"label":"Leer","default":"Leer"},{"description":"Ergebnisansage. @clue ist eine Variable und wird durch den passenden Hinweis ersetzt.","label":"Ergebnis für","default":"Ergebnis für: @clue"},{"label":"Richtig","default":"Richtig"},{"label":"Falsch","default":"Falsch"},{"label":"Punkt","default":"Punkt"},{"description":"Lösungsansage. @clue und @solution sind Variablen und werden durch die entsprechenden Werte ersetzt.","label":"Lösung für","default":"Für @clue lautet die Lösung: @solution"},{"description":"Extra-Hinweisansage. @clue ist eine Variable und wird durch den passenden Hinweis ersetzt.","label":"Extra-Hinweis für","default":"Öffne den Extra-Hinweis für @clue"},{"description":"Positionsansage. @position und @length sind Variablen und werden durch die entsprechenden Werte ersetzt.","label":"Zeichen","default":"Zeichen @position von @length"},{"description":"Wortlängenansage. @length ist eine Variable und wird durch den passenden Wert ersetzt.","label":"Länge des Wörtes","default":"Wort mit @length Zeichen"},{"label":"Vorlesewerkzeug-Beschreibung für den \"Überprüfen\"-Button","default":"Überprüfe die Eingaben. Die Antworten werden als richtig, falsch oder unbeantwortet markiert."},{"label":"Vorlesewerkzeug-Beschreibung für den \"Lösung anzeigen\"-Button","default":"Zeige die Lösung. Das Kreuzworträtsel wird mit der richtigen Lösung gefüllt."},{"label":"Vorlesewerkzeug-Beschreibung für den \"Wiederholen\"-Button","default":"Wiederhole die Aufgabe. Setze alle Eingaben zurück und fange von vorne an."},{"label":"Dein Ergebnis","description":"@score und @total sind Variablen und werden durch die entsprechenden Werte ersetzt.","default":"Du hast @score von @total Punkten erreicht."}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pCrossWordLibId,
            'language_code' => 'el',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Περιγραφή εργασίας","description":"Περιγράψτε εδώ την εργασία σας"},{"label":"Λέξεις","widgets":[{"label":"Προεπιλογή"}],"entity":"Λέξη","field":{"label":"Λέξη","fields":[{"label":"Στοιχείο","description":"Στοιχείο που πρέπει να δείχνει την απάντηση."},{"label":"Απάντηση","description":"Απάντηση στο στοιχείο"},{"label":"Επιπλέον στοιχείο"},{"label":"Εφαρμόστε τη λέξη στο πλέγμα","description":"Ελέγξτε αν θέλετε να εφαρμόσετε τη λέξη σε μια συγκεκριμένη θέση στο πλέγμα. Words with the same alignment may not be placed touching each other."},{"label":"Γραμμή","description":"Δείκτης γραμμής από όπου πρέπει να ξεκινά η απάντηση."},{"label":"Στήλη","description":"Δείκτης στήλης από όπου πρέπει να ξεκινά η απάντηση."},{"label":"Προσανατολισμός","description":"Προσανατολισμός για την απάντηση.","options":[{"label":"Οριζόντια"},{"label":"Κάθετα"}]}]}},{"label":"Συνολική λέξη λύσης","description":"Προαιρετικά προσθέστε μια λέξη λύσης που μπορεί να προέρχεται από συγκεκριμένους χαρακτήρες στο πλέγμα. Θα είναι ορατή μόνο εάν όλοι οι χαρακτήρες της βρίσκονται στο σταυρόλεξο. Λάβετε υπόψη: Δεν υπάρχει ακόμη υποστήριξη προσβασιμότητας για αυτήν τη λειτουργία."},{"label":"Συνολική ανατροφοδότηση","fields":[{"widgets":[{"label":"Προεπιλογή"}],"label":"Ορίστε προσαρμοσμένη ανατροφοδότηση για οποιοδήποτε εύρος βαθμολογίας","description":"Κάντε κλικ στο κουμπί \"Προσθήκη εύρους\" για να προσθέσετε όσα εύρη χρειάζεστε. Παράδειγμα: 0-20% Κακή βαθμολογία, 21-91% Μέση βαθμολογία, 91-100% Καλή βαθμολογία!","entity":"ευρουσ","field":{"fields":[{"label":"Εύρος βαθμολογίας"},{},{"label":"Σχόλια για καθορισμένο εύρος βαθμολογίας","placeholder":"Συμπληρώστε τα σχόλια"}]}}]},{"label":"Θέμα","fields":[{"label":"Εικόνα φόντου","description":"Επιλέξτε μια προαιρετική εικόνα φόντου. Θα κλιμακωθεί ώστε να ταιριάζει στο φόντο χωρίς να παραμορφωθεί"},{"label":"Χρώμα φόντου","description":"Επιλέξτε χρώμα φόντου. Θα χρησιμοποιηθεί είτε αντί για εικόνα φόντου είτε ως φόντο για διαφανείς περιοχές.","default":"#173354"},{"label":"Χρώμα πλέγματος","description":"Επιλέξτε ένα χρώμα για το πλέγμα.","default":"#000000"},{"label":"Χρώμα φόντου κελιού","description":"Επιλέξτε το χρώμα φόντου για ένα κελί πίνακα.","default":"#ffffff"},{"label":"Χρώμα κειμένου κελιού","description":"Επιλέξτε το χρώμα κειμένου για ένα κελί πίνακα.","default":"#000000"},{"label":"Χρώμα αναγνωριστικού βοήθειας","description":"Επιλέξτε το χρώμα του αναγνωριστικού βοήθειας μέσα σε ένα κελί πίνακα.","default":"#606060"},{"label":"Χρώμα φόντου κελιού (επισημασμένο)","description":"Επιλέξτε το χρώμα φόντου για ένα κελί πίνακα που επισημαίνεται.","default":"#3e8de8"},{"label":"Χρώμα κειμένου κελιού (επισημασμένο)","description":"Επιλέξτε το χρώμα κειμένου για ένα κελί πίνακα που επισημαίνεται.","default":"#ffffff"},{"label":"Χρώμα αναγνωριστικού κελιού (επισημασμένο)","description":"Επιλέξτε το χρώμα του αναγνωριστικού ενδείξεων μέσα σε ένα κελί πίνακα που επισημαίνεται.","default":"#e0e0e0"}]},{"label":"Ρυθμίσεις συμπεριφοράς","description":"Αυτές οι επιλογές θα σας επιτρέψουν να ελέγξετε πώς συμπεριφέρεται η εργασία.","fields":[{"label":"Αριθμός λέξεων προς εμφάνιση","description":"Δημιουργήστε μια τυχαία παρτίδα λέξεων από την ομάδα. Η παρτίδα θα περιέχει πάντα τουλάχιστον όλες τις λέξεις που έχουν καθοριστεί στο πλέγμα ανεξάρτητα από αυτήν τη ρύθμιση. Κενή τιμή ή 0 σημαίνει τη χρήση όλων των λέξεων."},{"label":"Ενεργοποίηση άμεσης ανατροφοδότησης"},{"label":"Βαθμολογήστε λέξεις","description":"Εάν αυτή η επιλογή είναι ενεργοποιημένη, θα μετρηθούν οι λέξεις για να καθοριστεί η βαθμολογία. Διαφορετικά, θα μετρηθούν οι σωστοί χαρακτήρες "},{"label":"Εφαρμογή ποινών","description":"Εάν αυτή η επιλογή είναι ενεργοποιημένη, σε κάθε λανθασμένη απάντηση θα δοθεί βαθμολογία -1."},{"label":"Ενεργοποίηση \"Επανάληψη\""},{"label":"Ενεργοποίηση \"Λύση\""}]},{"label":"Διεπαφή χρήστη","fields":[{"label":"Ετικέτα για οριζόντια","default":"Οριζόντια"},{"label":"Ετικέτα για κάθετα","default":"Κάθετα"},{"label":"Κείμενο για το κουμπί \"Έλεγχος\"","default":"Έλεγχος"},{"label":"Κείμενο για το κουμπί \"Υποβολή\"","default":"Υποβολή"},{"label":"Κείμενο για το κουμπί \"Επανάληψη\"","default":"Επανάληψη"},{"label":"Κείμενο για το κουμπί \"Εμφάνιση λύσης\"","default":"Εμφάνιση λύσης"},{"label":"Δεν είναι δυνατή η δημιουργία σταυρόλεξου","default":"Δεν ήταν δυνατή η δημιουργία σταυρόλεξου με τις συγκεκριμένες λέξεις. Δοκιμάστε ξανά με λιγότερες λέξεις ή λέξεις που έχουν περισσότερους κοινούς χαρακτήρες."},{"label":"Δεν είναι δυνατή η δημιουργία σταυρόλεξου (πολύ λίγες λέξεις)","default":"Δεν ήταν δυνατή η δημιουργία σταυρόλεξου. Χρειάζεστε τουλάχιστον δύο λέξεις."},{"label":"Προβληματικές λέξεις","description":"Καταχωρίστε λέξεις που προκάλεσαν προβλήματα κατά τη δημιουργία του σταυρόλεξου. Το @words είναι μια μεταβλητή θέσης που θα αντικατασταθεί από τις πραγματικές προβληματικές λέξεις.","default":"Some words could not be placed. Εάν χρησιμοποιείτε σταθερές λέξεις, βεβαιωθείτε ότι η θέση τους δεν εμποδίζει άλλες λέξεις να τοποθετούνται. Words with the same alignment may not be placed touching each other. Προβληματικές λέξεις: @words"},{"label":"Επιπλέον βοήθεια","default":"Επιπλέον βοήθεια"},{"label":"Κλείσιμο παραθύρου","default":"Κλείσιμο παραθύρου"}]},{"label":"Αναγνώστης","fields":[{"label":"Περιγραφή πλέγματος σταυρόλεξων","default":"Πλέγμα σταυρόλεξων. Χρησιμοποιήστε πλήκτρα βέλους για πλοήγηση και πληκτρολόγιο για εισαγωγή χαρακτήρων ή χρησιμοποιήστε το πλήκτρο (Tab) για να χρησιμοποιήσετε πεδία εισαγωγής."},{"label":"Στήλη","default":"Στήλη"},{"label":"Γραμμή","default":"Γραμμή"},{"label":"Οριζόντια","default":"Οριζόντια"},{"label":"Κάθετα","default":"Κάθετα"},{"label":"Κενό","default":"Κενό"},{"description":"Εκφωνητής αποτελεσμάτων. Το @clue είναι μια μεταβλητή και θα αντικατασταθεί από την αντίστοιχη ένδειξη.","label":"Αποτέλεσμα για","default":"Αποτέλεσμα για: @clue"},{"label":"Σωστό","default":"Σωστό"},{"label":"Λάθος","default":"Λάθος"},{"label":"Βαθμοί","default":"Βαθμοί"},{"description":"Ο εκφωνητής λύσεων. @clue και @solution είναι μεταβλητές και θα αντικατασταθούν από τις αντίστοιχες τιμές.","label":"Λύση για","default":"Για το @clue η λύση είναι: @solution"},{"description":"Εκφωνητής επιπλέον βοήθειας. Το @clue είναι μια μεταβλητή και θα αντικατασταθεί από την αντίστοιχη τιμή.","label":"Επιπλέον βοήθεια","default":"Άνοιγμα επιπλέον βοήθειας για @clue"},{"description":"Ανακοίνωση της τρέχουσας θέσης. @position και @length είναι μεταβλητές και θα αντικατασταθούν από τις αντίστοιχες τιμές τους.","label":"Γράμμα","default":"Γράμμα @position από @length"},{"description":"Ανακοίνωση του μήκους της λέξης. Το @length είναι μια μεταβλητή και θα αντικατασταθεί από την αντίστοιχη τιμή.","label":"Μήκος λέξης","default":"Λέξη @length γραμμάτων"},{"label":"Περιγραφή υποστηρικτικής τεχνολογίας για το κουμπί \"Έλεγχος\"","default":"Ελέγξτε τους χαρακτήρες. Οι απαντήσεις θα επισημανθούν ως σωστές, λανθασμένες ή αναπάντητες."},{"label":"Περιγραφή υποστηρικτικής τεχνολογίας για το κουμπί \"Εμφάνιση λύσης\"","default":"Εμφάνιση της λύσης. Το σταυρόλεξο θα συμπληρωθεί με τη σωστή λύση του."},{"label":"Περιγραφή υποστηρικτικής τεχνολογίας για το κουμπί \"Επανάληψη\"","default":"Επαναλάβετε την εργασία. Επαναφέρετε όλες τις απαντήσεις και ξεκινήστε την εργασία ξανά."},{"label":"Τα αποτελέσματά σας","description":"Τα @score και @total είναι μεταβλητές και θα αντικατασταθούν από τις αντίστοιχες τιμές τους.","default":"Συγκεντρώσατε @score από τους @total βαθμούς."}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pCrossWordLibId,
            'language_code' => 'es',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Descripción de la tarea","description":"Describe aquí la tarea."},{"label":"palabras","widgets":[{"label":"Predeterminado"}],"entity":"Palabra","field":{"label":"Palabra","fields":[{"label":"Pista","description":"Pista que debería apuntar a la respuesta."},{"label":"Respuesta","description":"Respuesta a la pista."},{"label":"Pista extra"},{"label":"Fijar palabra en rejilla","description":"Comprobar si desea fijar la palabra a una posición particular en la rejilla. Las palabras con la misma alineación podrían no estar colocadas tocándose ambas."},{"label":"Fila","description":"Índice de fila donde debería comenzar la respuesta."},{"label":"Columna","description":"Índice de la columna donde debería comenzar la respuesta."},{"label":"Orientación","description":"Orientación para la respuesta.","options":[{"label":"Horizontal"},{"label":"Vertical"}]}]}},{"label":"Palabra de solución global","description":"Opcionalmente, puedes incluir una palabra de solución global que pueda derivarse de caracteres particulares en la rejilla. Solamente será visible si todos sus caracteres se pueden encontrar en el crucigrama. Por favor, ten en cuenta que aun no hay soporte de accesibilidad para esta característica."},{"label":"Retroalimentación Global","fields":[{"widgets":[{"label":"Predeterminado"}],"label":"Definir retroalimentación personalizada para cualquier rango de puntaje","description":"Haga clic en el botón \"Añadir rango\" para añadir cuantos rangos necesite. Ejemplo: 0-20% Mal puntaje, 21-91% Puntaje Promedio, 91-100% ¡Magnífico Puntaje!","entity":"rango","field":{"fields":[{"label":"Rango de puntuación"},{},{"placeholder":"Escribe tu retroalimentación","label":"Realimentación para rango de puntuación definido"}]}}]},{"label":"Tema","fields":[{"label":"Imagen de fondo","description":"Selecciona una imagen opcional de fondo. Será escalada para que quepa en el fondo sin estirarla."},{"label":"Color del fondo","description":"Elije un color de fondo. Se usará, en lugar de una imagen de fondo o como fondo para las áreas transparentes.","default":"#173354"},{"label":"Color de rejilla","description":"Elije un color para la rejilla.","default":"#000000"},{"label":"Color del fondo de celda","description":"Elija el color del fondo para una celda de la tabla.","default":"#ffffff"},{"label":"Color del texto de la celda","description":"Elije el color del texto para una celda de la tabla.","default":"#000000"},{"label":"Color de ID de pista","description":"Elije el color de la ID de pista dentro de una celda de tabla.","default":"#606060"},{"label":"Color del fondo de la celda (resaltada)","description":"Elije el color del fondo para una celda de tabla que está resaltada.","default":"#3e8de8"},{"label":"Color del texto de celda (resaltada)","description":"Elije el color del texto para una celda de tabla que está resaltada.","default":"#ffffff"},{"label":"Color de ID de celda (resaltada)","description":"Elije el color de la ID de pista dentro de una celda de tabla que está resaltada.","default":"#e0e0e0"}]},{"label":"Configuraciones del comportamiento","description":"Estas opciones le permitirán controlar como se comporta el trabajo.","fields":[{"label":"Número de palabras a mostrar","description":"Crear un lote aleatorio de palabras a partir del conjunto de palabras. El lote siempre contendrá por lo menos todas las palabras fijadas en la rejilla independientemente del valor de esta configuración. Un valor vacío de 0 significa usar todas las palabras."},{"label":"Habilitar retroalimentación instantánea"},{"label":"Dar puntuación a palabras","description":"Si esta opción está activada, las palabras contarán para determinar la puntuación. En caso contrario, se contarán los caracteres correctos."},{"label":"Aplicar penalizaciones","description":"Si esta opción está habilitada, por cada respuesta incorrecta se aplicará una penalización a la puntuación de -1."},{"label":"Habilitar \"Intentar de nuevo\""},{"label":"Habilitar \"Solución\""}]},{"label":"Interfaz del usuario","fields":[{"label":"Etiqueta para horizontal","default":"Horizontal"},{"label":"Etiqueta para vertical","default":"Vertical"},{"label":"Texto para botón \"Comprobar\"","default":"Comprobar"},{"label":"Texto para botón \"Enviar\"","default":"Enviar"},{"label":"Texto para el botón \"Intentar de nuevo\"","default":"Intentar de nuevo"},{"label":"Texto para botón \"Mostrar solución\"","default":"Mostrar solución"},{"label":"No se puede crear el crucigrama","default":"No se pudo generar un crucigrama con las palabras dadas. Por favor inténtalo nuevamente con menos palabras, o con palabras que tengan más caracteres en común."},{"label":"No se puede crear el crucigrama (muy pocas palabras)","default":"No se pudo generar un crucigrama. Necesitas por lo menos dos palabras."},{"label":"Palabras problemáticas","description":"Lista palabras que causaron problemas al generar el rompecabezas. @words es una variable que será sustituída por las palabras problemáticas actuales.","default":"Algunas palabras no pudieron ser colocadas. Si está usando palabras fijadas, por favor asegúrese de que su posición no impida que otras palabras sean colocadas. Las palabras con la misma alineación podrían no estar colocadas tocándose ambas. Palabras problemáticas: @words"},{"label":"Pista extra","default":"Pista extra"},{"label":"Cerrar ventana","default":"Cerrar ventana"}]},{"label":"Lector de texto en voz alta","fields":[{"label":"Descripción de la rejilla del crucigrama","default":"Cuadrícula de crucigramas. Use las teclas de flecha para navegar y el teclado para ingresar caracteres. También puedes usar la tecla Tab para navegar y escribir las respuestas en los campos de estilo \"Rellenar los espacios en blanco\" en lugar de la cuadrícula."},{"label":"Columna","default":"Columna"},{"label":"Fila","default":"Fila"},{"label":"Horizontal","default":"Horizontal"},{"label":"Vertical","default":"Vertical"},{"label":"Vacío","default":"Vacío"},{"description":"Anunciador del resultado. @clue es una variable y será remplazada por la pista respectiva.","label":"Resultado para","default":"Resultado para: @clue"},{"label":"Correcto","default":"Correcto"},{"label":"Incorrecto","default":"Incorrecto"},{"label":"Punto","default":"punto"},{"description":"Anunciador de solución. @clue y @solution son variables que serán remplazadas por los valores respectivos.","label":"Solución para","default":"Para @clue la solución es: @solution"},{"description":"Anunciador de solución extra. @clue es una variable y será remplazada por el valor respectivo.","label":"Pista extra para","default":"Abrir pista extra para @clue"},{"description":"Anunciando la posición actual. @position y @length son variables y serán remplazadas por sus valores respectivos.","label":"Letra","default":"Letra @position de @length"},{"label":"Longitud de la palabra","description":"Anunciando la longitud de la palabra. @length es una variable y será remplazada por su valor respectivo.","default":"palabra de @length letras"},{"label":"Descripción para las tecnologías de asistencia del botón \"Comprobar\"","default":"Comprueba los caracteres. Las respuestas serán marcadas como correctas, incorrectas, o sin contestar."},{"label":"Descripción para las tecnologías de asistencia del botón \"Mostrar solución\"","default":"Mostrar la solución. El crucigrama se completará con su solución correcta."},{"label":"Descripción para las tecnologías de asistencia del botón \"Intentar de nuevo\"","default":"Vuelve a intentar la tarea. Borra todas tus respuestas y empieza de nuevo."},{"label":"Tu resultado","description":"@score y @total son variables y serán remplazadas por sus valores respectivos.","default":"Has conseguido @score de un total de @total puntos."}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

        DB::table('h5p_libraries_languages')->insert([
            'library_id' => $h5pCrossWordLibId,
            'language_code' => 'es-mx',
            'translation' => json_encode(json_decode('{"semantics":[{"label":"Descripción del trabajo","description":"Describa aquí su trabajo."},{"label":"palabras","widgets":[{"label":"Predeterminado"}],"entity":"Palabra","field":{"label":"Palabra","fields":[{"label":"Pista","description":"Pista que debería apuntar a la respuesta."},{"label":"Respuesta","description":"Respuesta a la pista."},{"label":"Pista extra"},{"label":"Fijar palabra en rejilla","description":"Comprobar si desea fijar la palabra a una posición particular en la rejilla. Las palabras con la misma alineación podrían no estar colocadas tocándose ambas."},{"label":"Fila","description":"Índice de fila donde debería comenzar la respuesta."},{"label":"Columna","description":"Índice de la columna donde debería comenzar la respuesta."},{"label":"Orientación","description":"Orientación para la respuesta.","options":[{"label":"Horizontal"},{"label":"Vertical"}]}]}},{"label":"Palabra de solución global","description":"Opcionalmente, incluya una palabra de solución global que puede estar derivada de caracteres particulares en la rejilla. Solamente será visible si todos sus caracteres pueden ser encontrados en el crucigrama. Por favor, tenga en cuenta que aun no hay soporte de accesibilidad para esta característica."},{"label":"Retroalimentación Global","fields":[{"widgets":[{"label":"Predeterminado"}],"label":"Definir retroalimentación personalizada para cualquier rango de puntaje","description":"Haga clic en el botón \"Añadir rango\" para añadir cuantos rangos necesite. Ejemplo: 0-20% Mal puntaje, 21-91% Puntaje Promedio, 91-100% ¡Magnífico Puntaje!","entity":"rango","field":{"fields":[{"label":"Rango del Puntaje"},{},{"label":"Retroalimentación para rango de puntaje definido","placeholder":"Complete la retroalimentación"}]}}]},{"label":"Tema","fields":[{"label":"Imagen de fondo","description":"Seleccione una imagen opcional de fondo. Será escalada para que quepa en el fondo sin estirarla."},{"label":"Color del fondo","description":"Elija un color de fondo. Será usado, ya sea en lugar de una imagen de fondo, o como un fondo para las áreas transparentes.","default":"#173354"},{"label":"Color de rejilla","description":"Elija un color para la rejilla.","default":"#000000"},{"label":"Color del fondo de celda","description":"Elija el color del fondo para una celda de la tabla.","default":"#ffffff"},{"label":"Color del texto de la celda","description":"Elegir el color del texto para una celda de la tabla.","default":"#000000"},{"label":"Color de ID de pista","description":"Elija el color de la ID de pista dentro de una celda de tabla.","default":"#606060"},{"label":"Color del fondo de la celda (resaltada)","description":"Elija el color del fondo para una celda de tabla que está resaltada.","default":"#3e8de8"},{"label":"Color del texto de celda (resaltada)","description":"Elija el color del texto para una celda de tabla que está resaltada.","default":"#ffffff"},{"label":"Color de ID de celda (resaltada)","description":"Elija el color de la ID de pista dentro de una celda de tabla que está resaltada.","default":"#e0e0e0"}]},{"label":"Configuraciones del comportamiento","description":"Estas opciones le permitirán controlar como se comporta el trabajo.","fields":[{"label":"Número de palabras a ser mostradas","description":"Crear un lote aleatorizado de palabras a partir del conjunto. El lote siempre contendrá al menos todas las palabras que fueron arregladas en la rejilla sin importar esta configuración. Un valor vacío de 0 significa usar todas las palabras."},{"label":"Habilitar retroalimentación instantánea"},{"label":"Dar puntaje a palabras","description":"Si esta opción es habilitada, las palabras serán contadas para determinar el puntaje. En caso contrario, los caracteres correctos serán contados."},{"label":"Aplicar penalizaciones","description":"Si esta opción es habilitada, por cada respuesta incorrecta será dada una penalización del puntaje de -1."},{"label":"Habilitar \"Reintentar\""},{"label":"Habilitar \"Solución\""}]},{"label":"Interfaz del usuario","fields":[{"label":"Etiqueta para horizontal","default":"Horizontal"},{"label":"Etiqueta para vertical","default":"Vertical"},{"label":"Texto para botón \"Comprobar\"","default":"Comprobar"},{"label":"Texto para botón \"Enviar\"","default":"Enviar"},{"label":"Texto para botón \"Reintentar\"","default":"Reintentar"},{"label":"Texto para botón \"Mostrar solución\"","default":"Mostrar solución"},{"label":"No se puede crear crucigrama","default":"No se pudo generar un crucigrama con las palabras dadas. Por favor intente nuevamente con menos palabras, o con palabras que tengan más caracteres en común."},{"label":"No se puede crear crucigrama (muy pocas palabras)","default":"No se pudo generar un crucigrama. Usted necesita por lo menos dos palabras."},{"label":"Palabras problemáticas","description":"Enlistar palabras que causaron problema al generar el rompecabezas. @words es un reemplazable que será remplazado por las palabras problemáticas actuales.","default":"Algunas palabras no pudieron ser colocadas. Si está usando palabras fijadas, por favor asegúrese de que su posición no impida que otras palabras sean colocadas. Las palabras con la misma alineación podrían no estar colocadas tocándose ambas. Palabras problemáticas: @words"},{"label":"Clave extra","default":"Clave extra"},{"label":"Cerrar ventana","default":"Cerrar ventana"}]},{"label":"Lector de texto en voz alta","fields":[{"label":"Descripción de rejilla del crucigrama","default":"Cuadrícula de crucigramas. Use las teclas de flecha para navegar y el teclado para ingresar caracteres. También puede usar la tecla Tab para navegar y escribir las respuestas en los campos de estilo \"Rellenar los espacios en blanco\" en lugar de la cuadrícula."},{"label":"Columna","default":"Columna"},{"label":"Fila","default":"Fila"},{"label":"Horizontal","default":"Horizontal"},{"label":"Vertical","default":"Vertical"},{"label":"Vacío","default":"Vacío"},{"description":"Anunciador del resultado. @clue es una variable y será remplazada por la pista respectiva.","label":"Resultado para","default":"Resultado para: @clue"},{"label":"Correcto","default":"Correcto"},{"label":"Incorrecto","default":"Incorrecto"},{"label":"Punto","default":"punto"},{"description":"Anunciador de solución. @clue y @solution son variables que serán remplazadas por los valores respectivos.","label":"Solución para","default":"Para @clue la solución es: @solution"},{"description":"Anunciador de solución extra. @clue es una variable y será remplazada por el valor respectivo.","label":"Pista extra para","default":"Abrir pista extra para @clue"},{"description":"Anunciando la posición actual. @position y @length son variables y serán remplazadas por sus valores respectivos.","label":"Letra","default":"Letra @position de @length"},{"description":"Anunciando la longitud de la palabra. @length es una variable y será remplazada por su valor respectivo.","label":"Longitud de la palabra","default":"palabra de @length letras"},{"label":"Descripción para las tecnologías de asistencia del botón \"Comprobar\"","default":"Comprobar los caracteres. Las respuestas serán marcadas como correcta, incorrecta, o sin contestar."},{"label":"Descripción para las tecnologías de asistencia del botón \"Mostrar solución\"","default":"Mostrar la solución. El crucigrama se completará con su solución correcta."},{"label":"Descripción para las tecnologías de asistencia del botón \"Intentar de nuevo\"","default":"Volver a intentar la tarea. Borra todas las respuestas y empezar de nuevo."},{"label":"Su resultado","description":"@score y @total son variables y serán remplazadas por sus valores respectivos.","default":"Usted obtuvo @score de un total de @total puntos."}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
        ]);

    }

    /**
     * Copy image from another server.
     *
     * @return void
     */
    public function copyImage($image)
    {
        $liveURL = 'https://studio.curriki.org/api/storage/activity-items/';
        $localURL = public_path('storage/activity-items/');

        $liveImageSrc = $liveURL . $image;
        $destination = $localURL . $image;

        if (@file_get_contents($liveURL . $image)) {
            copy($liveImageSrc, $destination);
        }
    }

    /**
     * @param $localURL
     * @param $crosswordImg
     * @param $organizations
     * @param $storageURL
     * @param $currentDate
     */
    private function insertActivityItem($localURL, $crosswordImg, $organizations, $storageURL, $currentDate)
    {
        $itemsArray = [
            'Crossword' => [
                'Create Crossword puzzle for the learner',
                'Multimedia',
                'H5P.Crossword 0.5',
                '0',
                '',
                $crosswordImg,

            ]
        ];

        foreach ($organizations as $key => $organization) {

            $activityItems = [];
            $activityTypes = DB::table('activity_types')->whereOrganizationId($organization)->pluck('id', 'title');

            foreach ($itemsArray as $itemKey => $itemRow) {

                if (!isset($activityTypes[$itemRow[1]])) {
                    continue;
                }

                if (!File::exists($localURL . $itemRow[5])) {
                    $this->copyImage($itemRow[5]);
                }

                $activityItemInsert = [
                    'title' => 'Crossword',
                    'image' => $storageURL . $itemRow[5],
                    'description' => $itemRow[0],
                    'activity_type_id' => $activityTypes[$itemRow[1]],
                    'h5pLib' => $itemRow[2],
                    'demo_activity_id' => $itemRow[3],
                    'demo_video_id' => $itemRow[4],
                    'type' => 'h5p',
                    'created_at' => $currentDate,
                    'deleted_at' => null,
                    'organization_id' => $organization,
                ];

                $activityItems[] = $activityItemInsert;
            }

            // Using updateOrInsert() is the recommended way.
            foreach ($activityItems as $activityItem) {
                $params = ['title' => $activityItem['title'], 'organization_id' => $organization];
                DB::table('activity_items')->updateOrInsert($params, $activityItem);
            }
        }
    }

}
