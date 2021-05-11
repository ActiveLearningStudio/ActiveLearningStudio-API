<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;

/**
 * SingleChoiceSet H5P library
 */
class SingleChoiceSet extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['choices'])) {
                $meta['children'] = [];
                foreach ($this->content['choices'] as $choice) {
                    $entry = [];
                    if (isset($choice['subContentId'])) {
                        $entry['sub-content-id'] = $choice['subContentId'];
                        $entry['relation-sub-content-id'] = $this->getRelationSubContentID($entry['sub-content-id']);
                    }
                    
                    $entry['question'] = $choice['question'];
                    $entry['answers'] = $choice['answers'];
                    $meta['children'][] = $entry;
                }
            }
        }
        return $meta;
    }

}
