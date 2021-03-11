<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;

/**
 * SingleChoiceSet H5P library
 */
class SingleChoiceSet implements H5PLibraryInterface
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
            if (isset($this->content['choices'])) {
                $meta['children'] = [];
                foreach ($this->content['choices'] as $choice) {
                    $entry = [];
                    $entry['sub-content-id'] = $choice['subContentId'];
                    $entry['question'] = $choice['question'];
                    $entry['answers'] = $choice['answers'];
                    $meta['children'][] = $entry;
                }
            }
        }
        return $meta;
    }

}
