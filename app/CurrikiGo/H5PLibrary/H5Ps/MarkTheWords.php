<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;

/**
 * MarkTheWords H5P library
 */
class MarkTheWords extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['taskDescription'])) {
                $meta['description'] = $this->content['taskDescription'];
            }
            if (isset($this->content['textField'])) {
                $meta['text'] = $this->content['textField'];
            }
        }
        return $meta;
    }

}
