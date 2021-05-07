<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;
use App\CurrikiGo\H5PLibrary\Helpers\H5PHelper;

/**
 * QuestionSet H5P library
 */
class QuestionSet extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['questions']) && !empty($this->content['questions'])) {
                foreach ($this->content['questions'] as $item) {
                    $meta[] = H5PHelper::buildElement($item, $this->parent);
                }
            }
        }
        return $meta;
    }

}
