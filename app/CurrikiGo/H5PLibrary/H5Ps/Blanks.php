<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;

/**
 * Blanks H5P library
 */
class Blanks extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['questions'])) {
                $meta['questions'] = $this->content['questions'];
            }
        }
        return $meta;
    }

}
