<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;

/**
 * StarRating H5P library
 */
class StarRating implements H5PLibraryInterface
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
            if (isset($this->content['text'])) {
                $meta['text'] = $this->content['text'];
            }
            if (isset($this->content['starcounter'])) {
                $meta['starcounter'] = $this->content['starcounter'];
            }
        }
        return $meta;
    }

}
