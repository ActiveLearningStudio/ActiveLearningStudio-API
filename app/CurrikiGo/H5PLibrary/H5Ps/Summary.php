<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;

/**
 * Summary H5P library
 */
class Summary implements H5PLibraryInterface
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
            if (isset($this->content['intro'])) {
                $meta['title'] = $this->content['intro'];
            }
            if (!empty($this->content['summaries'])) {
                $meta['children'] = [];
                foreach ($this->content['summaries'] as $summary) {
                    $entry = [];
                    $entry['sub-content-id'] = $summary['subContentId'];
                    if (isset($summary['summary'])) {
                        $entry['question'] = $summary['summary'];
                    }
                    $meta['children'][] = $entry;
                }
            }
        }
        return $meta;
    }

}
