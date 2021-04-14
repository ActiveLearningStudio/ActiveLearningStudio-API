<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;

/**
 * NonScoreableDragQuestion  H5P library
 */
class NonScoreableDragQuestion extends H5PLibraryBase implements H5PLibraryInterface
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
            // $meta['question'] = $this->content['question'];
        }
        return $meta;
    }

}
