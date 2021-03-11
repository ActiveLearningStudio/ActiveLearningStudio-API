<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;

/**
 * MarkTheWords H5P library
 */
class MarkTheWords implements H5PLibraryInterface
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
