<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateCoursePresentationSemanticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $h5pDialogcardsLibParams = ['name' => "H5P.CoursePresentation", "major_version" => 1, "minor_version" => 24];
        $h5pDialogcardsLib = DB::table('h5p_libraries')->where($h5pDialogcardsLibParams)->first();
        $h5pCPLibId = $h5pDialogcardsLib->id;
        if ($h5pDialogcardsLib) {
            DB::table('h5p_libraries')->where($h5pDialogcardsLibParams)->update([
                'semantics' => $this->updatedSemantics()
            ]);
        }
        
        DB::table('h5p_libraries_languages')
            ->updateOrInsert(
                ['language_code' => 'en', 'library_id' => $h5pCPLibId],
                ['translation' => json_encode(json_decode('{"semantics":[{"fields":[{"field":{"fields":[{"field":{"fields":[{},{},{},{},{},{"label":"Comments","description":"The comments are shown when the user displays the suggested answers for all slides."},{"label":"Always display comments"},{"label":"Background Opacity"},{"label":"Display as button"},{"label":"Button size","options":[{"label":"Small"},{"label":"Big"}]},{"label":"Title"},{"label":"Go to","options":[{"label":"Specific slide number"},{"label":"Next slide"},{"label":"Previous slide"}]},{"label":"Specific slide number","description":"Only applicable when \'Specific slide number\' is selected"},{"label":"Invisible","description":"Default cursor, no title and no tab index. Warning: Users with disabilities or keyboard only users will have trouble using this element."}]}},{},{"fields":[{"label":"Image","description":"Image background should have a 2:1 width to height ratio to avoid stretching. High resolution images will display better on larger screens."},{"label":"Pick a color"}]}]}},{},{"label":"Keyword list"},{"label":"Always show"},{"label":"Auto hide"},{"label":"Opacity"},{"fields":[{"label":"Image","description":"Image background should have a 2:1 width to height ratio to avoid stretching. High resolution images will display better on larger screens."},{"label":"Pick a color"}]}]},{"label":"Localize","fields":[{"label":"Translation for \"Slide\"","default":"Slide"},{"label":"Translation for \"Score\"","default":"Score"},{"label":"Translation for \"Your Score\"","default":"Your Score"},{"label":"Translation for \"Max Score\"","default":"Max Score"},{"label":"Translation for \"Total\"","default":"Total"},{"label":"Translation for \"Total Score\"","default":"Total Score"},{"label":"Title for show solutions button","default":"Show solutions"},{"label":"Text for the retry button","default":"Retry"},{"label":"Text for the export text button","default":"Export text"},{"label":"Hide keywords list button title","default":"Hide keywords list"},{"label":"Show keywords list button title","default":"Show keywords list"},{"label":"Fullscreen label","default":"Fullscreen"},{"label":"Exit fullscreen label","default":"Exit fullscreen"},{"label":"Previous slide label","default":"Previous slide"},{"label":"Next slide label","default":"Next slide"},{"label":"Current slide label","default":"Current slide"},{"label":"Last slide label","default":"Last slide"},{"label":"Exit solution mode text","default":"Exit solution mode"},{"label":"Solution mode text","default":"Solution Mode"},{"label":"Text when multiple tasks on a page","default":"Multiple tasks"},{"label":"Score message text","default":"You achieved:"},{"label":"Share to Facebook text","default":"Share on Facebook"},{"label":"Share to Twitter text","default":"Share on Twitter"},{"label":"Share to Google text","default":"Share on Google+"},{"label":"Title for summary slide","default":"Summary"},{"label":"Title for the comments icon","default":"Show comments"},{"label":"Title for print button","default":"Print"},{"label":"Print dialog ingress","default":"How would you like to print this presentation?"},{"label":"Label for \"Print all slides\" button","default":"Print all slides"},{"label":"Label for \"Print current slide\" button","default":"Print current slide"},{"label":"Label for slides without a title","default":"No title"},{"label":"Explanation of slide navigation for assistive technologies","default":"Use left and right arrow to change slide in that direction whenever canvas is selected"},{"label":"Canvas label for assistive technologies","default":"Presentation canvas. Use left and right arrow to move between slides."},{"label":"Label for uncompleted interactions","default":"@slideName contains not completed interaction"},{"label":"Label for completed interactions","default":"@slideName contains completed interaction"},{"label":"Label for slide counter. Variables are @index, @total","default":"Slide @index of @total"},{"label":"Label for slides that only contains correct answers","default":"@slideName only has correct answers"},{"label":"Label for slides that has incorrect answers","default":"@slideName has incorrect answers"},{"label":"Label for social sharing bar","default":"Share Result"},{"label":"Total score announcement for assistive technologies","default":"You got @score of @maxScore points in total","description":"Available variables are @score and @maxScore"},{"label":"Entered fullscreen announcement for assistive technologies","default":"Entered fullscreen"},{"label":"Exited fullscreen announcement for assistive technologies","default":"Exited fullscreen"},{"label":"Header of submit result confirmation dialog","default":"Submit your answers"},{"label":"Text of submit result confirmation dialog","default":"This will submit your results, do you want to continue?"},{"label":"Confirmation button of submit result confirmation dialog","default":"Submit and see results"}]},{"label":"Behaviour settings.","description":"These options will let you override behaviour settings.","fields":[{"label":"Activate Active Surface Mode","description":"Removes navigation controls for the end user. Use Go To Slide to navigate."},{"label":"Hide Summary Slide","description":"Hides the summary slide."},{"label":"Override \"Show Solution\" button","description":"This option determines if the \"Show Solution\" button will be shown for all questions, disabled for all or configured for each question individually.","options":[{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Override \"Retry\" button","description":"This option determines if the \"Retry\" button will be shown for all questions, disabled for all or configured for each question individually.","options":[{"label":"Enabled"},{"label":"Disabled"}]},{"label":"Show \"Show solution\" button in the summary slide","description":"If enabled, the learner will be able to show the solutions for all question when they reach the summary slide"},{"label":"Show \"Retry\" button in the summary slide","description":"If enabled, the learner will be able to retry all questions when they reach the summary slide. Be advised that by refreshing the page the learners will be able to retry even if this button isn\'t showing."},{"label":"Required to view entire presentation","description":"Lock Summary Page in interactive Book until user view complete presentation"},{"label":"Enable print button","description":"Enables the print button."},{"label":"Social Settings","description":"These options will let you override social behaviour settings. Empty values will be filled in automatically if a link is provided, otherwise all values will be generated.","fields":[{"label":"Display Facebook share icon"},{"label":"Facebook share settings","fields":[{"label":"Share to Facebook link","default":"@currentpageurl"},{"label":"Share to Facebook quote","default":"I scored @score out of @maxScore on a task at @currentpageurl."}]},{"label":"Display Twitter share icon"},{"label":"Twitter share settings","fields":[{"label":"Share to Twitter statement","default":"I scored @score out of @maxScore on a task at @currentpageurl."},{"label":"Share to Twitter link","default":"@currentpageurl"},{"label":"Share to Twitter hashtags","default":"h5p, course"}]},{"label":"Display Google+ share icon"},{"label":"Share to Google link","default":"@currentpageurl"}]}]}]}'), JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE)]
            );
    }
    
    private function updatedSemantics() {
        return '[
            {
              "name": "presentation",
              "type": "group",
              "importance": "high",
              "widget": "coursepresentation",
              "fields": [
                {
                  "name": "slides",
                  "importance": "high",
                  "type": "list",
                  "field": {
                    "name": "slide",
                    "importance": "high",
                    "type": "group",
                    "fields": [
                      {
                        "name": "elements",
                        "importance": "high",
                        "type": "list",
                        "field": {
                          "name": "element",
                          "importance": "high",
                          "type": "group",
                          "fields": [
                            {
                              "name": "x",
                              "importance": "low",
                              "type": "number",
                              "widget": "none"
                            },
                            {
                              "name": "y",
                              "importance": "low",
                              "type": "number",
                              "widget": "none"
                            },
                            {
                              "name": "width",
                              "importance": "low",
                              "type": "number",
                              "widget": "none",
                              "optional": true
                            },
                            {
                              "name": "height",
                              "importance": "low",
                              "type": "number",
                              "widget": "none",
                              "optional": true
                            },
                            {
                              "name": "action",
                              "type": "library",
                              "importance": "high",
                              "options": [
                                "H5P.AdvancedText 1.1",
                                "H5P.Link 1.3",
                                "H5P.Image 1.1",
                                "H5P.Shape 1.0",
                                "H5P.Video 1.6",
                                "H5P.Audio 1.5",
                                "H5P.Blanks 1.14",
                                "H5P.SingleChoiceSet 1.11",
                                "H5P.MultiChoice 1.16",
                                "H5P.TrueFalse 1.8",
                                "H5P.DragQuestion 1.14",
                                "H5P.Summary 1.10",
                                "H5P.DragText 1.10",
                                "H5P.MarkTheWords 1.11",
                                "H5P.Dialogcards 1.9",
                                "H5P.ContinuousText 1.2",
                                "H5P.ExportableTextArea 1.3",
                                "H5P.Table 1.1",
                                "H5P.InteractiveVideo 1.24",
                                "H5P.TwitterUserFeed 1.0",
                                "H5P.AudioRecorder 1.0"
                              ],
                              "optional": true
                            },
                            {
                              "name": "solution",
                              "type": "text",
                              "widget": "html",
                              "optional": true,
                              "label": "Comments",
                              "importance": "low",
                              "description": "The comments are shown when the user displays the suggested answers for all slides.",
                              "enterMode": "p",
                              "tags": [
                                "strong",
                                "em",
                                "del",
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
                              "name": "alwaysDisplayComments",
                              "type": "boolean",
                              "label": "Always display comments",
                              "importance": "low",
                              "optional": true
                            },
                            {
                              "name": "backgroundOpacity",
                              "type": "number",
                              "label": "Background Opacity",
                              "importance": "low",
                              "min": 0,
                              "max": 100,
                              "step": 5,
                              "default": 0,
                              "optional": true
                            },
                            {
                              "name": "displayAsButton",
                              "type": "boolean",
                              "label": "Display as button",
                              "importance": "medium",
                              "default": false,
                              "optional": true
                            },
                            {
                              "name": "buttonSize",
                              "type": "select",
                              "label": "Button size",
                              "importance": "low",
                              "optional": false,
                              "default": "big",
                              "options": [
                                {
                                  "value": "small",
                                  "label": "Small"
                                },
                                {
                                  "value": "big",
                                  "label": "Big"
                                }
                              ]
                            },
                            {
                              "name": "title",
                              "type": "text",
                              "optional": true,
                              "label": "Title",
                              "importance": "medium"
                            },
                            {
                              "name": "goToSlideType",
                              "type": "select",
                              "label": "Go to",
                              "importance": "medium",
                              "optional": false,
                              "options": [
                                {
                                  "value": "specified",
                                  "label": "Specific slide number"
                                },
                                {
                                  "value": "next",
                                  "label": "Next slide"
                                },
                                {
                                  "value": "previous",
                                  "label": "Previous slide"
                                }
                              ],
                              "default": "specified"
                            },
                            {
                              "name": "goToSlide",
                              "type": "number",
                              "label": "Specific slide number",
                              "description": "Only applicable when \'Specific slide number\' is selected",
                              "importance": "low",
                              "min": 1,
                              "optional": true
                            },
                            {
                              "name": "invisible",
                              "type": "boolean",
                              "label": "Invisible",
                              "importance": "low",
                              "description": "Default cursor, no title and no tab index. Warning: Users with disabilities or keyboard only users will have trouble using this element.",
                              "default": false
                            }
                          ]
                        }
                      },
                      {
                        "name": "keywords",
                        "importance": "low",
                        "type": "list",
                        "optional": true,
                        "field": {
                          "name": "keyword",
                          "importance": "low",
                          "type": "group",
                          "optional": true,
                          "fields": [
                            {
                              "name": "main",
                              "importance": "low",
                              "type": "text",
                              "optional": true
                            },
                            {
                              "name": "subs",
                              "importance": "low",
                              "type": "list",
                              "optional": true,
                              "field": {
                                "name": "keyword",
                                "importance": "low",
                                "type": "text"
                              }
                            }
                          ]
                        }
                      },
                      {
                        "name": "slideBackgroundSelector",
                        "importance": "medium",
                        "type": "group",
                        "widget": "radioSelector",
                        "fields": [
                          {
                            "name": "imageSlideBackground",
                            "type": "image",
                            "label": "Image",
                            "importance": "medium",
                            "optional": true,
                            "description": "Image background should have a 2:1 width to height ratio to avoid stretching. High resolution images will display better on larger screens."
                          },
                          {
                            "name": "fillSlideBackground",
                            "importance": "medium",
                            "type": "text",
                            "widget": "colorSelector",
                            "label": "Pick a color",
                            "spectrum": {
                              "flat": true,
                              "showInput": true,
                              "allowEmpty": true,
                              "showButtons": false
                            },
                            "default": null,
                            "optional": true
                          }
                        ]
                      }
                    ]
                  }
                },
                {
                  "name": "ct",
                  "importance": "low",
                  "type": "text",
                  "widget": "none",
                  "optional": true,
                  "tags": [
                    "strong",
                    "em",
                    "del",
                    "br",
                    "p",
                    "a",
                    "h2",
                    "h3",
                    "pre",
                    "code"
                  ]
                },
                {
                  "name": "keywordListEnabled",
                  "type": "boolean",
                  "label": "Keyword list",
                  "importance": "low",
                  "default": true
                },
                {
                  "name": "keywordListAlwaysShow",
                  "type": "boolean",
                  "label": "Always show",
                  "importance": "low",
                  "default": false
                },
                {
                  "name": "keywordListAutoHide",
                  "type": "boolean",
                  "label": "Auto hide",
                  "importance": "low",
                  "default": false
                },
                {
                  "name": "keywordListOpacity",
                  "type": "number",
                  "label": "Opacity",
                  "importance": "low",
                  "min": 0,
                  "max": 100,
                  "default": 90
                },
                {
                  "name": "globalBackgroundSelector",
                  "importance": "medium",
                  "type": "group",
                  "widget": "radioSelector",
                  "fields": [
                    {
                      "name": "imageGlobalBackground",
                      "type": "image",
                      "label": "Image",
                      "importance": "medium",
                      "optional": true,
                      "description": "Image background should have a 2:1 width to height ratio to avoid stretching. High resolution images will display better on larger screens."
                    },
                    {
                      "name": "fillGlobalBackground",
                      "type": "text",
                      "widget": "colorSelector",
                      "label": "Pick a color",
                      "importance": "medium",
                      "spectrum": {
                        "flat": true,
                        "showInput": true,
                        "allowEmpty": true,
                        "showButtons": false
                      },
                      "default": null,
                      "optional": true
                    }
                  ]
                }
              ]
            },
            {
              "name": "l10n",
              "type": "group",
              "label": "Localize",
              "importance": "low",
              "common": true,
              "fields": [
                {
                  "name": "slide",
                  "type": "text",
                  "label": "Translation for \"Slide\"",
                  "importance": "low",
                  "default": "Slide"
                },
                {
                  "name": "score",
                  "type": "text",
                  "label": "Translation for \"Score\"",
                  "importance": "low",
                  "default": "Score"
                },
                {
                  "name": "yourScore",
                  "type": "text",
                  "label": "Translation for \"Your Score\"",
                  "importance": "low",
                  "default": "Your Score"
                },
                {
                  "name": "maxScore",
                  "type": "text",
                  "label": "Translation for \"Max Score\"",
                  "importance": "low",
                  "default": "Max Score"
                },
                {
                  "name": "total",
                  "type": "text",
                  "label": "Translation for \"Total\"",
                  "importance": "low",
                  "default": "Total"
                },
                {
                  "name": "totalScore",
                  "type": "text",
                  "label": "Translation for \"Total Score\"",
                  "importance": "low",
                  "default": "Total Score"
                },
                {
                  "name": "showSolutions",
                  "type": "text",
                  "label": "Title for show solutions button",
                  "importance": "low",
                  "default": "Show solutions"
                },
                {
                  "name": "retry",
                  "type": "text",
                  "label": "Text for the retry button",
                  "importance": "low",
                  "default": "Retry",
                  "optional": true
                },
                {
                  "name": "exportAnswers",
                  "type": "text",
                  "label": "Text for the export text button",
                  "importance": "low",
                  "default": "Export text"
                },
                {
                  "name": "hideKeywords",
                  "type": "text",
                  "label": "Hide sidebar navigation menu button title",
                  "importance": "low",
                  "default": "Hide sidebar navigation menu"
                },
                {
                  "name": "showKeywords",
                  "type": "text",
                  "label": "Show sidebar navigation menu button title",
                  "importance": "low",
                  "default": "Show sidebar navigation menu"
                },
                {
                  "name": "fullscreen",
                  "type": "text",
                  "label": "Fullscreen label",
                  "importance": "low",
                  "default": "Fullscreen"
                },
                {
                  "name": "exitFullscreen",
                  "type": "text",
                  "label": "Exit fullscreen label",
                  "importance": "low",
                  "default": "Exit fullscreen"
                },
                {
                  "name": "prevSlide",
                  "type": "text",
                  "label": "Previous slide label",
                  "importance": "low",
                  "default": "Previous slide"
                },
                {
                  "name": "nextSlide",
                  "type": "text",
                  "label": "Next slide label",
                  "importance": "low",
                  "default": "Next slide"
                },
                {
                  "name": "currentSlide",
                  "type": "text",
                  "label": "Current slide label",
                  "importance": "low",
                  "default": "Current slide"
                },
                {
                  "name": "lastSlide",
                  "type": "text",
                  "label": "Last slide label",
                  "importance": "low",
                  "default": "Last slide"
                },
                {
                  "name": "solutionModeTitle",
                  "type": "text",
                  "label": "Exit solution mode text",
                  "importance": "low",
                  "default": "Exit solution mode"
                },
                {
                  "name": "solutionModeText",
                  "type": "text",
                  "label": "Solution mode text",
                  "importance": "low",
                  "default": "Solution Mode"
                },
                {
                  "name": "summaryMultipleTaskText",
                  "type": "text",
                  "label": "Text when multiple tasks on a page",
                  "importance": "low",
                  "default": "Multiple tasks"
                },
                {
                  "name": "scoreMessage",
                  "type": "text",
                  "label": "Score message text",
                  "importance": "low",
                  "default": "You achieved:"
                },
                {
                  "name": "shareFacebook",
                  "type": "text",
                  "label": "Share to Facebook text",
                  "importance": "low",
                  "default": "Share on Facebook"
                },
                {
                  "name": "shareTwitter",
                  "type": "text",
                  "label": "Share to Twitter text",
                  "importance": "low",
                  "default": "Share on Twitter"
                },
                {
                  "name": "shareGoogle",
                  "type": "text",
                  "label": "Share to Google text",
                  "importance": "low",
                  "default": "Share on Google+"
                },
                {
                  "name": "summary",
                  "type": "text",
                  "label": "Title for summary slide",
                  "importance": "low",
                  "default": "Summary"
                },
                {
                  "name": "solutionsButtonTitle",
                  "type": "text",
                  "label": "Title for the comments icon",
                  "importance": "low",
                  "default": "Show comments"
                },
                {
                  "name": "printTitle",
                  "type": "text",
                  "label": "Title for print button",
                  "importance": "low",
                  "default": "Print"
                },
                {
                  "name": "printIngress",
                  "type": "text",
                  "label": "Print dialog ingress",
                  "importance": "low",
                  "default": "How would you like to print this presentation?"
                },
                {
                  "name": "printAllSlides",
                  "type": "text",
                  "label": "Label for \"Print all slides\" button",
                  "importance": "low",
                  "default": "Print all slides"
                },
                {
                  "name": "printCurrentSlide",
                  "type": "text",
                  "label": "Label for \"Print current slide\" button",
                  "importance": "low",
                  "default": "Print current slide"
                },
                {
                  "name": "noTitle",
                  "type": "text",
                  "label": "Label for slides without a title",
                  "importance": "low",
                  "default": "No title"
                },
                {
                  "name": "accessibilitySlideNavigationExplanation",
                  "type": "text",
                  "label": "Explanation of slide navigation for assistive technologies",
                  "importance": "low",
                  "default": "Use left and right arrow to change slide in that direction whenever canvas is selected."
                },
                {
                  "name": "accessibilityCanvasLabel",
                  "type": "text",
                  "label": "Canvas label for assistive technologies",
                  "importance": "low",
                  "default": "Presentation canvas. Use left and right arrow to move between slides."
                },
                {
                  "name": "containsNotCompleted",
                  "type": "text",
                  "label": "Label for uncompleted interactions",
                  "importance": "low",
                  "default": "@slideName contains not completed interaction"
                },
                {
                  "name": "containsCompleted",
                  "type": "text",
                  "label": "Label for completed interactions",
                  "importance": "low",
                  "default": "@slideName contains completed interaction"
                },
                {
                  "name": "slideCount",
                  "type": "text",
                  "label": "Label for slide counter. Variables are @index, @total",
                  "importance": "low",
                  "default": "Slide @index of @total"
                },
                {
                  "name": "containsOnlyCorrect",
                  "type": "text",
                  "label": "Label for slides that only contains correct answers",
                  "importance": "low",
                  "default": "@slideName only has correct answers"
                },
                {
                  "name": "containsIncorrectAnswers",
                  "type": "text",
                  "label": "Label for slides that has incorrect answers",
                  "importance": "low",
                  "default": "@slideName has incorrect answers"
                },
                {
                  "name": "shareResult",
                  "type": "text",
                  "label": "Label for social sharing bar",
                  "importance": "low",
                  "default": "Share Result"
                },
                {
                  "name": "accessibilityTotalScore",
                  "type": "text",
                  "label": "Total score announcement for assistive technologies",
                  "default": "You got @score of @maxScore points in total",
                  "description": "Available variables are @score and @maxScore"
                },
                {
                  "name": "accessibilityEnteredFullscreen",
                  "type": "text",
                  "label": "Entered fullscreen announcement for assistive technologies",
                  "default": "Entered fullscreen"
                },
                {
                  "name": "accessibilityExitedFullscreen",
                  "type": "text",
                  "label": "Exited fullscreen announcement for assistive technologies",
                  "default": "Exited fullscreen"
                },
                {
                  "name": "confirmDialogHeader",
                  "type": "text",
                  "label": "Header of submit result confirmation dialog",
                  "default": "Submit your answers"
                },
                {
                  "name": "confirmDialogText",
                  "type": "text",
                  "label": "Text of submit result confirmation dialog",
                  "default": "This will submit your results, do you want to continue?"
                },
                {
                  "name": "confirmDialogConfirmText",
                  "type": "text",
                  "label": "Confirmation button of submit result confirmation dialog",
                  "default": "Submit and see results"
                }
              ]
            },
            {
              "name": "override",
              "type": "group",
              "label": "Behaviour settings.",
              "importance": "low",
              "description": "These options will let you override behaviour settings.",
              "optional": true,
              "fields": [
                {
                  "name": "activeSurface",
                  "type": "boolean",
                  "widget": "disposableBoolean",
                  "label": "Activate Active Surface Mode",
                  "importance": "low",
                  "description": "Removes navigation controls for the end user. Use Go To Slide to navigate.",
                  "default": false
                },
                {
                  "name": "hideSummarySlide",
                  "type": "boolean",
                  "label": "Hide Summary Slide",
                  "importance": "low",
                  "description": "Hides the summary slide.",
                  "default": false
                },
                {
                  "name": "showSolutionButton",
                  "type": "select",
                  "label": "Override \"Show Solution\" button",
                  "importance": "low",
                  "description": "This option determines if the \"Show Solution\" button will be configured for each question individually (default) shown for all questions (Enabled) or disabled for all questions (Disabled) ",
                  "optional": true,
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
                  "description": "This option determines if the \"Retry\" button will be configured for each question individually (default) shown for all questions (Enabled) or disabled for all questions (Disabled)",
                  "optional": true,
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
                  "name": "summarySlideSolutionButton",
                  "type": "boolean",
                  "label": "Show \"Show solution\" button in the summary slide",
                  "description": "If enabled, the learner will be able to show the solutions for all question when they reach the summary slide",
                  "default": true,
                  "importance": "low",
                  "widget": "showWhen",
                  "showWhen": {
                    "rules": [
                      {
                        "field": "hideSummarySlide",
                        "equals": false
                      }
                    ]
                  }
                },
                {
                  "name": "summarySlideRetryButton",
                  "type": "boolean",
                  "label": "Show \"Retry\" button in the summary slide",
                  "description": "If enabled, the learner will be able to retry all questions when they reach the summary slide. Be advised that by refreshing the page the learners will be able to retry even if this button isn\'t showing.",
                  "default": true,
                  "importance": "low",
                  "widget": "showWhen",
                  "showWhen": {
                    "rules": [
                      {
                        "field": "hideSummarySlide",
                        "equals": false
                      }
                    ]
                  }
                },
                {
                  "name": "lockslide",
                  "type": "boolean",
                  "label": "Required to view entire presentation",
                  "description": "Lock Summary Page in interactive Book until user view complete presentation",
                  "default": false
                },
                {
                  "name": "enablePrintButton",
                  "type": "boolean",
                  "label": "Enable print button",
                  "importance": "low",
                  "description": "Enables the print button.",
                  "default": false
                },
                {
                  "name": "social",
                  "type": "group",
                  "label": "Social Settings",
                  "widget": "showWhen",
                  "showWhen": {
                    "rules": [
                      {
                        "field": "hideSummarySlide",
                        "equals": false
                      }
                    ]
                  },
                  "importance": "low",
                  "description": "These options will let you override social behaviour settings. Empty values will be filled in automatically if a link is provided, otherwise all values will be generated.",
                  "optional": true,
                  "fields": [
                    {
                      "name": "showFacebookShare",
                      "type": "boolean",
                      "label": "Display Facebook share icon",
                      "importance": "low",
                      "default": false
                    },
                    {
                      "name": "facebookShare",
                      "importance": "low",
                      "type": "group",
                      "expanded": true,
                      "label": "Facebook share settings",
                      "widget": "showWhen",
                      "showWhen": {
                        "rules": [
                          {
                            "field": "showFacebookShare",
                            "equals": true
                          }
                        ]
                      },
                      "fields": [
                        {
                          "name": "url",
                          "type": "text",
                          "label": "Share to Facebook link",
                          "importance": "low",
                          "default": "@currentpageurl"
                        },
                        {
                          "name": "quote",
                          "type": "text",
                          "label": "Share to Facebook quote",
                          "importance": "low",
                          "default": "I scored @score out of @maxScore on a task at @currentpageurl."
                        }
                      ]
                    },
                    {
                      "name": "showTwitterShare",
                      "type": "boolean",
                      "label": "Display Twitter share icon",
                      "importance": "low",
                      "default": false
                    },
                    {
                      "name": "twitterShare",
                      "importance": "low",
                      "type": "group",
                      "expanded": true,
                      "label": "Twitter share settings",
                      "widget": "showWhen",
                      "showWhen": {
                        "rules": [
                          {
                            "field": "showTwitterShare",
                            "equals": true
                          }
                        ]
                      },
                      "fields": [
                        {
                          "name": "statement",
                          "type": "text",
                          "label": "Share to Twitter statement",
                          "importance": "low",
                          "default": "I scored @score out of @maxScore on a task at @currentpageurl."
                        },
                        {
                          "name": "url",
                          "type": "text",
                          "label": "Share to Twitter link",
                          "importance": "low",
                          "default": "@currentpageurl"
                        },
                        {
                          "name": "hashtags",
                          "type": "text",
                          "label": "Share to Twitter hashtags",
                          "importance": "low",
                          "default": "h5p, course"
                        }
                      ]
                    },
                    {
                      "name": "showGoogleShare",
                      "type": "boolean",
                      "label": "Display Google+ share icon",
                      "importance": "low",
                      "default": false
                    },
                    {
                      "name": "googleShareUrl",
                      "type": "text",
                      "label": "Share to Google link",
                      "importance": "low",
                      "default": "@currentpageurl",
                      "widget": "showWhen",
                      "showWhen": {
                        "rules": [
                          {
                            "field": "showGoogleShare",
                            "equals": true
                          }
                        ]
                      }
                    }
                  ]
                }
              ]
            }
          ]';
    }
}
