<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;

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
            if (isset($this->content['presentation']) && isset($this->content['presentation']['slides']) && !empty($this->content['presentation']['slides'])) {
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
            $element['content'] = $this->loadContentByLibrary($element['library'], $content['params']);
            $data[] = $element;
        }
        return $data;
    }

    private function loadContentByLibrary($library, $content)
    {
        $h5pFactory = new H5PLibraryFactory();
        $h5pLib = $h5pFactory->init($library, $content);
        $h5pMeta = [];
        if ($h5pLib) {
            $h5pMeta = $h5pLib->buildMeta();
        }
        return $h5pMeta;
    }

}
