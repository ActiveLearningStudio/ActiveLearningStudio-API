<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;

/**
 * StarRating H5P library
 */
class StarRating extends H5PLibraryBase implements H5PLibraryInterface
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
