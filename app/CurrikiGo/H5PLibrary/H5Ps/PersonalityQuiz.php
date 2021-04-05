<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;

/**
 * PersonalityQuiz H5P library
 */
class PersonalityQuiz extends H5PLibraryBase implements H5PLibraryInterface
{
    
    /**
     * Build meta from content
     *
     * @return array
     */
    public function buildMeta()
    {
        $meta = [];
        if (!empty($this->content)) {
            if (isset($this->content['questions'])) {
                $meta['questions'] = [];
                foreach ($this->content['questions'] as $question) {
                    $meta['questions'][] = $question['text'];
                }
            }
        }
        return $meta;
    }

}
