<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateDialogCardsSemanticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pDialogcardsLibParams = ['name' => "H5P.Dialogcards", "major_version" => 1, "minor_version" => 9];
        $h5pDialogcardsLib = DB::table('h5p_libraries')->where($h5pDialogcardsLibParams)->first();
        if ($h5pDialogcardsLib) {
            DB::table('h5p_libraries')->where($h5pDialogcardsLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);

            $h5pDialogcardsTranslationParams = ['library_id' => $h5pDialogcardsLib->id, "language_code" => 'en'];
            $h5pDialogcardsTranslation = DB::table('h5p_libraries_languages')->where($h5pDialogcardsTranslationParams)->first();
            if($h5pDialogcardsTranslation) {
                DB::table('h5p_libraries_languages')->where($h5pDialogcardsTranslationParams)->update([
                    'translation' => json_encode(json_decode('{"semantics":[{"label":"Heading"},{"label":"Mode","description":"Mode of presenting the dialog cards","options":[{"label":"Normal"},{"label":"Repetition"}]},{"label":"Task description","default":""},{"widgets":[{"label":"Default"}],"label":"Dialogs","entity":"dialog","field":{"label":"Question","fields":[{"label":"Text","description":"Hint for the first part of the dialogue"},{"label":"Answer","description":"Hint for the second part of the dialogue"},{"label":"Image","description":"Optional image for the card. (The card may use just an image, just a text or both)"},{"label":"Alternative text for the image"},{"label":"Back Image","description":"Optional image for the back of the card."},{"label":"Alternative text for the back image"},{"label":"Audio files"},{"label":"Tips","fields":[{"label":"Tip for text","description":"Tip for the first part of the dialogue"},{"label":"Tip for answer","description":"Tip for the second part of the dialogue"}]}]}},{"label":"Behavioural settings","description":"These options will let you control how the task behaves.","fields":[{"label":"Enable \"Retry\" button"},{"label":"Disable backwards navigation","description":"This option will only allow you to move forward with Dialog Cards"},{"label":"Scale the text to fit inside the card","description":"Unchecking this option will make the card adapt its size to the size of the text"},{"label":"Randomize cards","description":"Enable to randomize the order of cards on display."},{"label":"Maximum proficiency level"},{"label":"Allow quick progression","description":"If activated, learners can decide to indicate that they know a card without turning it"}]},{"label":"Text for the turn button","default":"Turn"},{"label":"Text for the next button","default":"Next"},{"label":"Text for the previous button","default":"Previous"},{"label":"Text for the retry button","default":"Retry"},{"label":"Text for the \"correct answer\" button","default":"I got it right!"},{"label":"Text for the \"incorrect answer\" button","default":"I got it wrong"},{"label":"Text for \"Round\" message below cards and on the summary screen","description":"@round will be replaced by the number of the current round","default":"Round @round"},{"label":"Text for \"Cards left\" message","description":"@number will be replaced by the number of cards left in this round","default":"Cards left: @number"},{"label":"Text for the \"next round\" button","description":"@round will be replaced by the round number","default":"Proceed to round @round"},{"label":"Text for the \"Start over\" button","default":"Start over"},{"label":"Text for the \"show summary\" button","default":"Next"},{"label":"Title text for the summary page","default":"Summary"},{"label":"Text for \"Cards you got right:\"","default":"Cards you got right:"},{"label":"Text for \"Cards you got wrong:\"","default":"Cards you got wrong:"},{"label":"Text for \"Cards not shown:\"","default":"Cards in pool not shown:"},{"label":"Text for \"Overall Score\"","default":"Overall Score"},{"label":"Text for \"Cards completed\"","default":"Cards you have completed learning:"},{"label":"Text for \"Completed rounds:\"","default":"Completed rounds:"},{"label":"Message when all cards have been learned proficiently","description":"@cards will be replaced by the number of all cards in the pool. @max will be replaced by the maximum proficiency level - 1.","default":"Well done! You got all @cards cards correct @max times in a row!"},{"label":"Progress text","description":"Available variables are @card and @total.","default":"Card @card of @total"},{"label":"Label for card text","description":"Used for accessibility by assistive technologies","default":"Card front"},{"label":"Label for card back","description":"Used for accessibility by assistive technologies","default":"Card back"},{"label":"Label for the show tip button","default":"Show tip"},{"label":"Audio not supported message","default":"Your browser does not support this audio"},{"label":"Confirm starting over dialog","fields":[{"label":"Header text","default":"Start over?"},{"label":"Body text","default":"All progress will be lost. Are you sure you want to start over?"},{"label":"Cancel button label","default":"Cancel"},{"label":"Confirm button label","default":"Start over"}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)
                ]);
            }
        }



    }

    private function updatedSemantics() {
        return '[
  {
    "name": "title",
    "type": "text",
    "widget": "html",
    "label": "Heading",
    "importance": "high",
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
    "name": "mode",
    "type": "select",
    "label": "Mode",
    "description": "Mode of presenting the dialog cards",
    "importance": "medium",
    "options": [
      {
        "value": "normal",
        "label": "Normal"
      },
      {
        "value": "repetition",
        "label": "Repetition"
      }
    ],
    "default": "normal"
  },
  {
    "name": "description",
    "type": "text",
    "widget": "html",
    "label": "Task description",
    "importance": "medium",
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
    "name": "dialogs",
    "type": "list",
    "importance": "high",
    "widgets": [
      {
        "name": "VerticalTabs",
        "label": "Default"
      }
    ],
    "label": "Dialogs",
    "entity": "dialog",
    "min": 1,
    "defaultNum": 1,
    "field": {
      "name": "question",
      "type": "group",
      "label": "Question",
      "importance": "high",
      "fields": [
        {
          "name": "text",
          "type": "text",
          "widget": "html",
          "tags": [
            "p",
            "br",
            "strong",
            "em",
            "code"
          ],
          "font": {
            "size": true,
            "family": true,
            "color": true,
            "background": true
          },
          "label": "Text",
          "importance": "high",
          "description": "Hint for the first part of the dialogue",
          "default": "<p style=\"text-align: center;\"></p>"
        },
        {
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
          "font": {
            "size": true,
            "family": true,
            "color": true,
            "background": true
          },
          "label": "Answer",
          "importance": "high",
          "description": "Hint for the second part of the dialogue",
          "default": "<p style=\"text-align: center;\"></p>"
        },
        {
          "name": "image",
          "type": "image",
          "label": "Image",
          "importance": "high",
          "optional": true,
          "description": "Optional image for the card. (The card may use just an image, just a text or both)"
        },
        {
          "name": "imageAltText",
          "type": "text",
          "label": "Alternative text for the image",
          "importance": "high",
          "optional": true
        },
        {
          "name": "backimage",
          "type": "image",
          "label": "Back Image",
          "importance": "high",
          "optional": true,
          "description": "Optional image for the back of the card."
        },
        {
          "name": "backImageAltText",
          "type": "text",
          "label": "Alternative text for the back image",
          "importance": "high",
          "optional": true
        },
        {
          "name": "audio",
          "type": "audio",
          "label": "Audio files",
          "importance": "low",
          "optional": true
        },
        {
          "name": "tips",
          "type": "group",
          "label": "Tips",
          "importance": "low",
          "fields": [
            {
              "name": "front",
              "type": "text",
              "label": "Tip for text",
              "importance": "low",
              "optional": true,
              "description": "Tip for the first part of the dialogue"
            },
            {
              "name": "back",
              "type": "text",
              "label": "Tip for answer",
              "importance": "low",
              "optional": true,
              "description": "Tip for the second part of the dialogue"
            }
          ]
        }
      ]
    }
  },
  {
    "name": "behaviour",
    "type": "group",
    "label": "Behavioural settings",
    "importance": "low",
    "description": "These options will let you control how the task behaves.",
    "optional": true,
    "fields": [
      {
        "name": "enableRetry",
        "type": "boolean",
        "label": "Enable \"Retry\" button",
        "importance": "low",
        "default": true,
        "optional": true
      },
      {
        "name": "disableBackwardsNavigation",
        "type": "boolean",
        "label": "Disable backwards navigation",
        "importance": "low",
        "description": "This option will only allow you to move forward with Dialog Cards",
        "optional": true,
        "default": false,
        "widget": "showWhen",
        "showWhen": {
          "rules": [
            {
              "field": "../mode",
              "equals": "normal"
            }
          ]
        }
      },
      {
        "name": "scaleTextNotCard",
        "type": "boolean",
        "label": "Scale the text to fit inside the card",
        "importance": "low",
        "description": "Unchecking this option will make the card adapt its size to the size of the text",
        "default": false
      },
      {
        "name": "randomCards",
        "type": "boolean",
        "label": "Randomize cards",
        "importance": "low",
        "description": "Enable to randomize the order of cards on display.",
        "default": false,
        "widget": "showWhen",
        "showWhen": {
          "rules": [
            {
              "field": "../mode",
              "equals": "normal"
            }
          ]
        }
      },
      {
        "name": "maxProficiency",
        "type": "number",
        "label": "Maximum proficiency level",
        "importance": "low",
        "default": 5,
        "min": 3,
        "max": 7,
        "optional": true,
        "widget": "showWhen",
        "showWhen": {
          "rules": [
            {
              "field": "../mode",
              "equals": "repetition"
            }
          ]
        }
      },
      {
        "name": "quickProgression",
        "type": "boolean",
        "label": "Allow quick progression",
        "description": "If activated, learners can decide to indicate that they know a card without turning it",
        "importance": "low",
        "default": false,
        "optional": true,
        "widget": "showWhen",
        "showWhen": {
          "rules": [
            {
              "field": "../mode",
              "equals": "repetition"
            }
          ]
        }
      }
    ]
  },
  {
    "label": "Text for the turn button",
    "importance": "low",
    "name": "answer",
    "type": "text",
    "default": "Turn",
    "common": true
  },
  {
    "label": "Text for the next button",
    "importance": "low",
    "type": "text",
    "name": "next",
    "default": "Next",
    "common": true
  },
  {
    "name": "prev",
    "type": "text",
    "label": "Text for the previous button",
    "importance": "low",
    "default": "Previous",
    "common": true
  },
  {
    "name": "retry",
    "type": "text",
    "label": "Text for the retry button",
    "importance": "low",
    "default": "Retry",
    "common": true
  },
  {
    "name": "correctAnswer",
    "type": "text",
    "label": "Text for the \"correct answer\" button",
    "importance": "low",
    "default": "I got it right!",
    "common": true
  },
  {
    "name": "incorrectAnswer",
    "type": "text",
    "label": "Text for the \"incorrect answer\" button",
    "importance": "low",
    "default": "I got it wrong",
    "common": true
  },
  {
    "name": "round",
    "type": "text",
    "label": "Text for \"Round\" message below cards and on the summary screen",
    "description": "@round will be replaced by the number of the current round",
    "importance": "low",
    "default": "Round @round",
    "common": true
  },
  {
    "name": "cardsLeft",
    "type": "text",
    "label": "Text for \"Cards left\" message",
    "description": "@number will be replaced by the number of cards left in this round",
    "importance": "low",
    "default": "Cards left: @number",
    "common": true
  },
  {
    "name": "nextRound",
    "type": "text",
    "label": "Text for the \"next round\" button",
    "description": "@round will be replaced by the round number",
    "importance": "low",
    "default": "Proceed to round @round",
    "common": true
  },
  {
    "name": "startOver",
    "type": "text",
    "label": "Text for the \"Start over\" button",
    "importance": "low",
    "default": "Start over",
    "common": true
  },
  {
    "name": "showSummary",
    "type": "text",
    "label": "Text for the \"show summary\" button",
    "importance": "low",
    "default": "Next",
    "common": true
  },
  {
    "name": "summary",
    "type": "text",
    "label": "Title text for the summary page",
    "importance": "low",
    "default": "Summary",
    "common": true
  },
  {
    "name": "summaryCardsRight",
    "type": "text",
    "label": "Text for \"Cards you got right:\"",
    "importance": "low",
    "default": "Cards you got right:",
    "common": true
  },
  {
    "name": "summaryCardsWrong",
    "type": "text",
    "label": "Text for \"Cards you got wrong:\"",
    "importance": "low",
    "default": "Cards you got wrong:",
    "common": true
  },
  {
    "name": "summaryCardsNotShown",
    "type": "text",
    "label": "Text for \"Cards not shown:\"",
    "importance": "low",
    "default": "Cards in pool not shown:",
    "common": true
  },
  {
    "name": "summaryOverallScore",
    "type": "text",
    "label": "Text for \"Overall Score\"",
    "importance": "low",
    "default": "Overall Score",
    "common": true
  },
  {
    "name": "summaryCardsCompleted",
    "type": "text",
    "label": "Text for \"Cards completed\"",
    "importance": "low",
    "default": "Cards you have completed learning:",
    "common": true
  },
  {
    "name": "summaryCompletedRounds",
    "type": "text",
    "label": "Text for \"Completed rounds:\"",
    "importance": "low",
    "default": "Completed rounds:",
    "common": true
  },
  {
    "name": "summaryAllDone",
    "type": "text",
    "label": "Message when all cards have been learned proficiently",
    "description": "@cards will be replaced by the number of all cards in the pool. @max will be replaced by the maximum proficiency level - 1.",
    "importance": "low",
    "default": "Well done! You got all @cards cards correct @max times in a row!",
    "common": true
  },
  {
    "name": "progressText",
    "type": "text",
    "label": "Progress text",
    "importance": "low",
    "description": "Available variables are @card and @total.",
    "default": "Card @card of @total",
    "common": true
  },
  {
    "name": "cardFrontLabel",
    "type": "text",
    "label": "Label for card text",
    "importance": "low",
    "description": "Used for accessibility by assistive technologies",
    "default": "Card front",
    "common": true
  },
  {
    "name": "cardBackLabel",
    "type": "text",
    "label": "Label for card back",
    "importance": "low",
    "description": "Used for accessibility by assistive technologies",
    "default": "Card back",
    "common": true
  },
  {
    "name": "tipButtonLabel",
    "type": "text",
    "label": "Label for the show tip button",
    "importance": "low",
    "default": "Show tip",
    "common": true
  },
  {
    "name": "audioNotSupported",
    "type": "text",
    "label": "Audio not supported message",
    "importance": "low",
    "common": true,
    "default": "Your browser does not support this audio"
  },
  {
    "name": "confirmStartingOver",
    "type": "group",
    "label": "Confirm starting over dialog",
    "importance": "low",
    "common": true,
    "fields": [
      {
        "name": "header",
        "type": "text",
        "label": "Header text",
        "importance": "low",
        "default": "Start over?"
      },
      {
        "name": "body",
        "type": "text",
        "label": "Body text",
        "importance": "low",
        "default": "All progress will be lost. Are you sure you want to start over?",
        "widget": "html",
        "enterMode": "p",
        "tags": [
          "strong",
          "em",
          "del",
          "u",
          "code"
        ]
      },
      {
        "name": "cancelLabel",
        "type": "text",
        "label": "Cancel button label",
        "importance": "low",
        "default": "Cancel"
      },
      {
        "name": "confirmLabel",
        "type": "text",
        "label": "Confirm button label",
        "importance": "low",
        "default": "Start over"
      }
    ]
  }
]';
    }
}
