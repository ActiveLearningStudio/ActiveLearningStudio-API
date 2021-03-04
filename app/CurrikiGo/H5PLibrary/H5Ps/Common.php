<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;

/**
 * Common H5P library
 */
class Common implements H5PLibraryInterface
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
            $meta = $this->content;
        }
        return $meta;
    }

}
