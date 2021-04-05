<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;
use App\CurrikiGo\H5PLibrary\Helpers\H5PHelper;

/**
 * Column H5P library
 */
class Column extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['content']) && !empty($this->content['content'])) {
                foreach ($this->content['content'] as $item) {
                    $meta[] = H5PHelper::buildElement($item['content'], $this->parent);
                }
            }
        }
        return $meta;
    }

}
