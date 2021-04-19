<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;

/**
 * Summary H5P library
 */
class Summary extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['intro'])) {
                $meta['title'] = $this->content['intro'];
            }
            if (!empty($this->content['summaries'])) {
                $meta['children'] = [];
                foreach ($this->content['summaries'] as $summary) {
                    if (isset($summary['subContentId'])) {
                        $entry = [];
                        $entry['sub-content-id'] = $summary['subContentId'];
                        $entry['relation-sub-content-id'] = $this->getRelationSubContentID($summary['subContentId']);

                        if (isset($summary['summary'])) {
                            $entry['question'] = $summary['summary'];
                        }
                        $meta['children'][] = $entry;
                    }
                }
            }
        }
        return $meta;
    }

}
