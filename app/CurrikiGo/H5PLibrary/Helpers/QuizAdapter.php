<?php

namespace App\CurrikiGo\H5PLibrary\Helpers;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;

/**
 * Quiz Adapter class
 */
class QuizAdapter implements H5PLibraryInterface
{
    /**
     * Library content
     *
     */
    protected $content;
    
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
            $meta['question'] = $this->content['question'];
        }
        return $meta;
    }

}
