<?php

namespace App\CurrikiGo\H5PLibrary;

use App\CurrikiGo\H5PLibrary\H5Ps\InteractiveBook;
use App\CurrikiGo\H5PLibrary\H5Ps\Column;
use App\CurrikiGo\H5PLibrary\H5Ps\Common;
use App\CurrikiGo\H5PLibrary\H5Ps\Questionnaire;
use App\CurrikiGo\H5PLibrary\H5Ps\SimpleMultiChoice;
use App\CurrikiGo\H5PLibrary\H5Ps\OpenEndedQuestion;
use App\CurrikiGo\H5PLibrary\H5Ps\NonScoreableDragQuestion;
use App\CurrikiGo\H5PLibrary\H5Ps\MultiChoice;
use App\CurrikiGo\H5PLibrary\H5Ps\CoursePresentation;
use App\CurrikiGo\H5PLibrary\H5Ps\InteractiveVideo;
use App\CurrikiGo\H5PLibrary\H5Ps\TrueFalse;
use App\CurrikiGo\H5PLibrary\H5Ps\Summary;
use App\CurrikiGo\H5PLibrary\H5Ps\Blanks;
use App\CurrikiGo\H5PLibrary\H5Ps\DragQuestion;
use App\CurrikiGo\H5PLibrary\H5Ps\DragText;
use App\CurrikiGo\H5PLibrary\H5Ps\MarkTheWords;
use App\CurrikiGo\H5PLibrary\H5Ps\SingleChoiceSet;
use App\CurrikiGo\H5PLibrary\H5Ps\StarRating;
use App\CurrikiGo\H5PLibrary\H5Ps\PersonalityQuiz;
use App\CurrikiGo\H5PLibrary\H5Ps\QuestionSet;
use App\CurrikiGo\H5PLibrary\H5Ps\Essay;

/**
 * Factory Class for H5P Libraries
 */
class H5PLibraryFactory
{
    
    /**
     * Initialize and return an library object.
     *
     * @param string $library The library name
     * @param array $content The meta data content.
     * @param string $parent The parent element subContentId
     * @return H5PLibraryFactory
     */
    public function init($library, $content, $parent = '')
    {
        $library = $this->cleanString($library);
        switch ($library) {
            case 'InteractiveBook':
                return new InteractiveBook($content, $parent);
            case 'Column':
                return new Column($content, $parent);
            case 'Questionnaire':
                return new Questionnaire($content, $parent);
            case 'OpenEndedQuestion':
                return new OpenEndedQuestion($content, $parent);
            case 'SimpleMultiChoice':
                return new SimpleMultiChoice($content, $parent);
            case 'NonscoreableDragQuestion':
                return new NonScoreableDragQuestion($content, $parent);
            case 'MultiChoice':
                return new MultiChoice($content, $parent);
            case 'CoursePresentation':
                return new CoursePresentation($content, $parent);
            case 'InteractiveVideo':
                return new InteractiveVideo($content, $parent);
            case 'Summary':
                return new Summary($content, $parent);
            case 'TrueFalse':
                return new TrueFalse($content, $parent);
            case 'Blanks':
                return new Blanks($content, $parent);
            case 'DragQuestion':
                return new DragQuestion($content, $parent);          
            case 'DragText':
                return new DragText($content, $parent);          
            case 'MarkTheWords':
                return new MarkTheWords($content, $parent);
            case 'SingleChoiceSet':
                return new SingleChoiceSet($content, $parent);
            case 'StarRating':
                return new StarRating($content, $parent);
            case 'PersonalityQuiz':
                return new PersonalityQuiz($content, $parent);
            case 'QuestionSet':
                return new QuestionSet($content, $parent);
            case 'Essay':
                return new Essay($content, $parent);
            default:
                // When there is no interaction type
                return new Common($content, $parent);
        }
    }

    /**
     * Clean library name
     *
     * @param string $library The library name
     * @return string
     */
    private function cleanString($library)
    {
        $pattern = "/H5P.(\w+)(-|\s)(.*)/";
        if (preg_match($pattern, $library, $matches)) {
            return $matches[1];
        }
        return '';
    }
}
