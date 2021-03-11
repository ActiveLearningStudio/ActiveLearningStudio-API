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
     * @return H5PLibraryFactory
     */
    public function init($library, $content)
    {
        $library = $this->cleanString($library);
        switch ($library) {
            case 'InteractiveBook':
                return new InteractiveBook($content);
            case 'Column':
                return new Column($content);
            case 'Questionnaire':
                return new Questionnaire($content);
            case 'OpenEndedQuestion':
                return new OpenEndedQuestion($content);
            case 'SimpleMultiChoice':
                return new SimpleMultiChoice($content);
            case 'NonscoreableDragQuestion':
                return new NonScoreableDragQuestion($content);
            case 'MultiChoice':
                return new MultiChoice($content);
            case 'CoursePresentation':
                return new CoursePresentation($content);
            case 'InteractiveVideo':
                return new InteractiveVideo($content);
            case 'Summary':
                return new Summary($content);
            case 'TrueFalse':
                return new TrueFalse($content);
            case 'Blanks':
                return new Blanks($content);
            case 'DragQuestion':
                return new DragQuestion($content);          
            case 'DragText':
                return new DragText($content);          
            case 'MarkTheWords':
                return new MarkTheWords($content);
            case 'SingleChoiceSet':
                return new SingleChoiceSet($content);
            case 'StarRating':
                return new StarRating($content);
            default:
                // When there is no interaction type
                return new Common($content);
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
