<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\Helpers\H5PHelper;

/**
 * CoursePresentation H5P library
 */
class CoursePresentation implements H5PLibraryInterface
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
            if (isset($this->content['presentation']) && isset($this->content['presentation']['slides']) 
            && !empty($this->content['presentation']['slides'])) {
                foreach ($this->content['presentation']['slides'] as $item) {
                    $meta[] = $this->buildSlide($item['elements']);
                }
            }
        }
        return $meta;
    }

    private function buildSlide($elements)
    {
        $data = [];
        foreach ($elements as $item) {
            $element = [];
            $content = $item['action'];
            $element['sub-content-id'] = $content['subContentId'];
            $element['library'] = $content['library'];
            $element['content-type'] = $content['metadata']['contentType'];
            $element['title'] = $content['metadata']['title'];
            $element['content'] = H5PHelper::loadContentByLibrary($element['library'], $content['params']);
            $data[] = $element;
        }
        return $data;
    }

}
