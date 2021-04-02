<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\Helpers\H5PHelper;

/**
 * Interactive Book H5P class
 */
class InteractiveBook implements H5PLibraryInterface
{
    /**
     * Library content
     *
     */
    private $content;

    /**
     * Library content
     *
     */
    private $parent;
    
    /**
     * Initialize
     *
     * @param array $content
     */
    public function __construct(array $content, string $parent)
    {
        $this->content = $content;
        $this->parent = $parent;
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
            if (isset($this->content['chapters']) && !empty($this->content['chapters'])) {
                foreach ($this->content['chapters'] as $chapter) {
                    $meta[] = H5PHelper::buildElement($chapter['chapter'], $this->parent);
                }
            }
        }
        return $meta;
    }
}
