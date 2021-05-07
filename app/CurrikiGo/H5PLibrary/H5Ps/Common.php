<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;

/**
 * Common H5P library
 */
class Common extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['title'])) {
                $meta['title'] = $this->content['title'];
            }
        }
        return $meta;
    }

}
