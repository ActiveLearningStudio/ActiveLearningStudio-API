<?php

namespace App\CurrikiGo\H5PLibrary;

/**
 * Factory Class for H5P Libraries
 */
class H5PLibraryBase
{
    
    /**
     * Library content
     *
     */
    protected $content;

    /**
     * Parent id
     *
     */
    protected $parent;
    
    /**
     * Initialize
     *
     * @param array $content
     * @param string $parent
     */
    public function __construct(array $content, string $parent)
    {
        $this->content = $content;
        $this->parent = $parent;
    }

    /**
     * Build parent child subcontent id
     *
     * @param string $subContentID
     * @return array
     */
    protected function getRelationSubContentID($subContentID)
    {
        return ($this->parent ? $this->parent . '|' . $subContentID : $subContentID);
    }

}
