<?php

use Illuminate\Database\Seeder;

class fixFindTheWordSemantics extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //H5P.FindTheWords-1.4
        $h5pFindTheWordsParams = ['name' => "H5P.FindTheWords", "major_version" =>1, "minor_version" => 4];
        $h5pFindTheWords = DB::table('h5p_libraries')->where($h5pFindTheWordsParams)->first();
        
        if ($h5pFindTheWords) {
            DB::table('h5p_libraries')->where($h5pFindTheWordsParams)->update([
                'semantics' => $this->updatedFindTheWordsSemantics(),
            ]);
        }
    }

     //H5P.FindTheWords-1.4
     private function updatedFindTheWordsSemantics() { 
        return '[
          {
            "label": "Task description",
            "name": "taskDescription",
            "type": "text",
            "description": "Description of the Game",
            "default": "Find the words from the grid",
            "importance": "high"
          },
          {
            "name": "wordList",
            "type": "text",
            "label": "Word list",
            "description": "Comma Separated list of words. Special Characters, White Spaces and Numbers Not allowed",
            "default": "one,two,three",
            "regexp": {
              "pattern": "^(?!(?:.*[\"!#$%&./:;<=>?@\\\[\\\]^_`\\\{|}~()\\\-*+\\\d]|^[,])).*$"
            },
            "importance": "high"
          },
          {
            "name": "behaviour",
            "type": "group",
            "label": "Behaviour settings",
            "importance": "low",
            "description": "These options will let you control how the game behaves.",
            "optional": true,
            "fields": [
              {
                "name": "orientations",
                "type": "group",
                "label": "Orientations",
                "description": "An array containing the names of the word directions that should be used when creating the puzzle",
                "fields": [
                  {
                    "name": "horizontal",
                    "type": "boolean",
                    "label": "Horizontal- left to right",
                    "default": true
                  },
                  {
                    "name": "horizontalBack",
                    "type": "boolean",
                    "label": "Horizontal- right to left",
                    "default": true
                  },
                  {
                    "name": "vertical",
                    "type": "boolean",
                    "label": "Vertical downwards",
                    "default": true
                  },
                  {
                    "name": "verticalUp",
                    "type": "boolean",
                    "label": "Vertical upwards",
                    "default": true
                  },
                  {
                    "name": "diagonal",
                    "type": "boolean",
                    "label": "Diagonal downwards- left to right",
                    "default": true
                  },
                  {
                    "name": "diagonalBack",
                    "type": "boolean",
                    "label": "Diagonal downwards- right to left",
                    "default": true
                  },
                  {
                    "name": "diagonalUp",
                    "type": "boolean",
                    "label": "Diagonal upwards- left to right",
                    "default": true
                  },
                  {
                    "name": "diagonalUpBack",
                    "type": "boolean",
                    "label": "Diagonal upwards- right to left",
                    "default": true
                  }
                ]
              },
              {
                "name": "fillPool",
                "type": "text",
                "label": "Vertical downwards",
                "description": "pool of letters from which the blanks to be filled",
                "default": "abcdefghijklmnopqrstuvwxyz",
                "regexp": {
                  "pattern": "^[^\t\n .<>?;:\"`!@#$%^&*()\\\[\\\]{}_+=|\\\-]*$"
                }
              },
              {
                "name": "preferOverlap",
                "type": "boolean",
                "label": "Prefer overlap",
                "description": "Determines how wordfind decides where to place a word within the puzzle.   When true, it randomly selects amongst the positions the highest number of letters that overlap creating a more compact puzzle.   When false, it randomly selects amongst all valid positions creating a less compact puzzle.",
                "default": true
              },
              {
                "name": "showVocabulary",
                "type": "boolean",
                "label": "Show vocabulary",
                "description": "Determines whether to show vocabularies to the player",
                "default": true
              },
              {
                "name": "enableShowSolution",
                "type": "boolean",
                "label": "Enable show solution",
                "description": "Add a show solution button for the game",
                "default": true
              },
              {
                "name": "enableRetry",
                "type": "boolean",
                "label": "Enable retry",
                "description": "Add a retry button for the game",
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
                "name": "check",
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
                "label": "Text for \"Show Solution\" button",
                "importance": "low",
                "name": "showSolution",
                "type": "text",
                "default": "Show Solution"
              },
              {
                "label": "Counter text",
                "importance": "low",
                "name": "found",
                "type": "text",
                "default": "@found of @totalWords found",
                "description": "Feedback text, variables available: @found and @totalWords. Example: @found of @totalWords found"
              },
              {
                "label": "Time spent text",
                "importance": "low",
                "name": "timeSpent",
                "type": "text",
                "default": "Time Spent",
                "description": "label for showing the time spent while playing the game"
              },
              {
                "label": "Feedback text",
                "importance": "low",
                "name": "score",
                "type": "text",
                "default": "You got @score of @total points",
                "description": "Feedback text, variables available: @score and @total. Example: You got @score of @total possible points"
              },
              {
                "label": "Word list header",
                "importance": "low",
                "name": "wordListHeader",
                "type": "text",
                "default": "Find the words",
                "maxLength": 20
              }
            ]
          }
        ]
        ';
      }
}
