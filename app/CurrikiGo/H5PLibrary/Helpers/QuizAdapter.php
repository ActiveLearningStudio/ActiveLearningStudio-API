<?php

namespace App\CurrikiGo\H5PLibrary\Helpers;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;

/**
 * Quiz Adapter class
 */
class QuizAdapter extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['question'])) {
                $meta['questions'] = $this->content['question'];
            }
        }
        return $meta;
    }

}
