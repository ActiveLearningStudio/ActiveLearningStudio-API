<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsertCpH5PContentTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('h5p_content_templates')->insert([
            'id' => '1',
            'name' => 'Course Presentation',
            'library' => 'H5P.CoursePresentation 1.24',
            'template' => '{
    "params": {
        "presentation": {
            "slides": [
                @foreach ($data[\'slides\'] as $index => $slide)
                    @php
                        $activityType = isset($slide[\'activityType\']) ? $slide[\'activityType\'] : \'text\';
                        $title = isset($slide[\'title\']) ? $slide[\'title\'] : \'\';
                    @endphp
                    {
                        "elements": [
                                @switch($activityType)
                                    @case(\'multiple-choice\')
                                        {
                                            "x": 30,
                                            "y": 20,
                                            "width": 40,
                                            "height": 40,
                                            "action": {
                                                "library": "H5P.MultiChoice 1.16",
                                                "params": {
                                                    "media": {
                                                        "type": {
                                                            "params": {
                                                            }
                                                        },
                                                        "disableImageZooming": false
                                                    },
                                                    "answers": [
                                                        @foreach($slide[\'options\'] as $optionIndex => $option)
                                                            {
                                                                "correct": @if (in_array($option, isset($slide[\'correctAnswers\']) ? $slide[\'correctAnswers\'] : [])) true @else false @endif ,
                                                                "tipsAndFeedback": {
                                                                    "tip": "",
                                                                    "chosenFeedback": "",
                                                                    "notChosenFeedback": ""
                                                                },
                                                                "text": "<div>{{$option}}</div>\n"
                                                            }
                                                            @if ($optionIndex < count($slide[\'options\']) - 1) , @endif
                                                        @endforeach
                                                    ],
                                                    "overallFeedback": [
                                                        {
                                                            "from": 0,
                                                            "to": 100
                                                        }
                                                    ],
                                                    "behaviour": {
                                                        "enableRetry": true,
                                                        "enableSolutionsButton": true,
                                                        "enableCheckButton": true,
                                                        "type": "auto",
                                                        "singlePoint": false,
                                                        "randomAnswers": true,
                                                        "showSolutionsRequiresInput": true,
                                                        "confirmCheckDialog": false,
                                                        "confirmRetryDialog": false,
                                                        "autoCheck": false,
                                                        "passPercentage": 100,
                                                        "showScorePoints": true
                                                    },
                                                    "UI": {
                                                        "checkAnswerButton": "Check",
                                                        "submitAnswerButton": "Submit",
                                                        "showSolutionButton": "Show solution",
                                                        "tryAgainButton": "Retry",
                                                        "tipsLabel": "Show tip",
                                                        "scoreBarLabel": "You got :num out of :total points",
                                                        "tipAvailable": "Tip available",
                                                        "feedbackAvailable": "Feedback available",
                                                        "readFeedback": "Read feedback",
                                                        "wrongAnswer": "Wrong answer",
                                                        "correctAnswer": "Correct answer",
                                                        "shouldCheck": "Should have been checked",
                                                        "shouldNotCheck": "Should not have been checked",
                                                        "noInput": "Please answer before viewing the solution",
                                                        "a11yCheck": "Check the answers. The responses will be marked as correct, incorrect, or unanswered.",
                                                        "a11yShowSolution": "Show the solution. The task will be marked with its correct solution.",
                                                        "a11yRetry": "Retry the task. Reset all responses and start the task over again."
                                                    },
                                                    "confirmCheck": {
                                                        "header": "Finish ?",
                                                        "body": "Are you sure you wish to finish ?",
                                                        "cancelLabel": "Cancel",
                                                        "confirmLabel": "Finish"
                                                    },
                                                    "confirmRetry": {
                                                        "header": "Retry ?",
                                                        "body": "Are you sure you wish to retry ?",
                                                        "cancelLabel": "Cancel",
                                                        "confirmLabel": "Confirm"
                                                    },
                                                    "question": "@foreach($slide[\'content\'] as $content)<p>{{$content}}\n</p>@endforeach"
                                                },
                                                "subContentId": "{{ \Illuminate\Support\Str::uuid() }}",
                                                "metadata": {
                                                    "contentType": "Multiple Choice",
                                                    "license": "U",
                                                    "title": "{{$title}}",
                                                    "authors": [
                                                    ],
                                                    "changes": [
                                                    ],
                                                    "extraTitle": "{{$title}}"
                                                }
                                            },
                                            "alwaysDisplayComments": false,
                                            "backgroundOpacity": 0,
                                            "displayAsButton": false,
                                            "buttonSize": "big",
                                            "goToSlideType": "specified",
                                            "invisible": false,
                                            "solution": ""
                                        }
                                    @break

                                    @case(\'2\')
                                    <p>Value is 2</p>
                                    @break

                                    @case(\'text\')
                                    @default
                                        {
                                            "x": 25,
                                            "y": 10,
                                            "width": 40,
                                            "height": 40,
                                            "action": {
                                                "library": "H5P.AdvancedText 1.1",
                                                "params": {
                                                    "text": "@foreach($slide[\'content\'] as $content)<p>{{$content}}\n\n</p>@endforeach"
                                                },
                                                "subContentId": "{{ \Illuminate\Support\Str::uuid() }}",
                                                "metadata": {
                                                    "contentType": "Text",
                                                    "license": "U",
                                                    "title": "{{$title}}",
                                                    "authors": [
                                                    ],
                                                    "changes": [
                                                    ],
                                                    "extraTitle": "{{$title}}"
                                                }
                                            },
                                            "alwaysDisplayComments": true,
                                            "backgroundOpacity": 0,
                                            "displayAsButton": false,
                                            "buttonSize": "big",
                                            "goToSlideType": "specified",
                                            "invisible": false,
                                            "solution": ""
                                        }
                                @endswitch
                        ],
                        "keywords": [
                        ],
                        "slideBackgroundSelector": {
                            "fillSlideBackground": null
                        }
                    }
                @if ($index < count($data[\'slides\']) - 1) , @endif
                @endforeach
            ],
            "keywordListEnabled": true,
            "globalBackgroundSelector": {
                "fillGlobalBackground": null
            },
            "keywordListAlwaysShow": false,
            "keywordListAutoHide": false,
            "keywordListOpacity": 90
        },
        "override": {
            "activeSurface": false,
            "hideSummarySlide": false,
            "summarySlideSolutionButton": true,
            "summarySlideRetryButton": true,
            "lockslide": false,
            "enablePrintButton": false,
            "social": {
                "showFacebookShare": false,
                "facebookShare": {
                    "url": "@currentpageurl",
                    "quote": "I scored @score out of @maxScore on a task at @currentpageurl."
                },
                "showTwitterShare": false,
                "twitterShare": {
                    "statement": "I scored @score out of @maxScore on a task at @currentpageurl.",
                    "url": "@currentpageurl",
                    "hashtags": "h5p, course"
                },
                "showGoogleShare": false,
                "googleShareUrl": "@currentpageurl"
            }
        },
        "l10n": {
            "slide": "Slide",
            "score": "Score",
            "yourScore": "Your Score",
            "maxScore": "Max Score",
            "total": "Total",
            "totalScore": "Total Score",
            "showSolutions": "Show solutions",
            "retry": "Retry",
            "exportAnswers": "Export text",
            "hideKeywords": "Hide keywords list",
            "showKeywords": "Show keywords list",
            "fullscreen": "Fullscreen",
            "exitFullscreen": "Exit fullscreen",
            "prevSlide": "Previous slide",
            "nextSlide": "Next slide",
            "currentSlide": "Current slide",
            "lastSlide": "Last slide",
            "solutionModeTitle": "Exit solution mode",
            "solutionModeText": "Solution Mode",
            "summaryMultipleTaskText": "Multiple tasks",
            "scoreMessage": "You achieved:",
            "shareFacebook": "Share on Facebook",
            "shareTwitter": "Share on Twitter",
            "shareGoogle": "Share on Google+",
            "summary": "Summary",
            "solutionsButtonTitle": "Show comments",
            "printTitle": "Print",
            "printIngress": "How would you like to print this presentation?",
            "printAllSlides": "Print all slides",
            "printCurrentSlide": "Print current slide",
            "noTitle": "No title",
            "accessibilitySlideNavigationExplanation": "Use left and right arrow to change slide in that direction whenever canvas is selected",
            "accessibilityCanvasLabel": "Presentation canvas. Use left and right arrow to move between slides.",
            "containsNotCompleted": "@slideName contains not completed interaction",
            "containsCompleted": "@slideName contains completed interaction",
            "slideCount": "Slide @index of @total",
            "containsOnlyCorrect": "@slideName only has correct answers",
            "containsIncorrectAnswers": "@slideName has incorrect answers",
            "shareResult": "Share Result",
            "accessibilityTotalScore": "You got @score of @maxScore points in total",
            "accessibilityEnteredFullscreen": "Entered fullscreen",
            "accessibilityExitedFullscreen": "Exited fullscreen",
            "confirmDialogHeader": "Submit your answers",
            "confirmDialogText": "This will submit your results, do you want to continue?",
            "confirmDialogConfirmText": "Submit and see results"
        }
    },
    "metadata": {
        "title": "{{$data[\'activityTitle\']}}",
        "license": "U",
        "authors": [
        ],
        "changes": [
        ],
        "extraTitle": "{{$data[\'activityTitle\']}}"
    }
}'
        ]);
    }
}
