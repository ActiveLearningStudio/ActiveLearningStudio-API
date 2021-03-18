<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;

/**
 * PersonalityQuiz H5P library
 */
class PersonalityQuiz implements H5PLibraryInterface
{
    /**
     * Library content
     *
     */
    private $content;
    
    /**
     * Initialize
     *
     * @param array $content
     */
    public function __construct(array $content)
    {
        $this->content = $content;
    }

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
