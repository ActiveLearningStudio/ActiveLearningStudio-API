<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryBase;
use App\CurrikiGo\H5PLibrary\Helpers\H5PHelper;

/**
 * CoursePresentation H5P library
 */
class CoursePresentation extends H5PLibraryBase implements H5PLibraryInterface
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
            if (isset($this->content['presentation']) && isset($this->content['presentation']['slides']) 
            && !empty($this->content['presentation']['slides'])) {
                foreach ($this->content['presentation']['slides'] as $item) {
                    $meta[] = $this->buildSlide($item['elements']);
                }
            }
        }
        return $meta;
    }

    /**
     * Build a slide meta
     *
     * @return array
     */
    private function buildSlide($elements)
    {
        $data = [];
        foreach ($elements as $item) {
            // Not all elements have 'action' property, e.g., Go to Slide
            if (isset($item['action'])) {
                $element = H5PHelper::buildElement($item['action'], $this->parent);
                $data[] = $element;
            }
        }
        return $data;
    }

}
