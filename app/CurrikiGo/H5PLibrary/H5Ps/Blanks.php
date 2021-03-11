<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;

/**
 * Blanks H5P library
 */
class Blanks implements H5PLibraryInterface
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
            if (isset($this->content['questions'])) {
                $meta['questions'] = $this->content['questions'];
            }
        }
        return $meta;
    }

}
