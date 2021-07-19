<?php

namespace App\CurrikiGo\H5PLibrary\Helpers;

use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;

/**
 * A Helper class for H5P libaries
 */
class H5PHelper 
{

    /**
     * Load Content by Library type
     * 
     * @param string $library The H5P library name
     * @param array $content The content array
     * @param string $parent The parent element subContentId
     * @return array
     */
    public static function loadContentByLibrary($library, $content, $parent)
    {
        $h5pFactory = new H5PLibraryFactory();
        $h5pLib = $h5pFactory->init($library, $content, $parent);
        $h5pMeta = [];
        if ($h5pLib) {
            $h5pMeta = $h5pLib->buildMeta();
        }
        return $h5pMeta;
    }

    /**
     * Prepare element from content meta data
     * 
     * @param array $content The content array
     * @param string $parent The parent subContentId
     * @return array
     */
    public static function buildElement($content, $parent = '')
    {
        $data = [];
        $data['sub-content-id'] = $content['subContentId'];
        $data['relation-sub-content-id'] = ($parent ? $parent . '|' . $data['sub-content-id'] : $data['sub-content-id']); 
        $data['library'] = $content['library'];
        $data['content-type'] = $content['metadata']['contentType'];
        $data['title'] = (isset($content['metadata']['title']) ? $content['metadata']['title'] : $data['content-type']);
        $data['content'] = self::loadContentByLibrary($data['library'], $content['params'], $content['subContentId']);
        return $data;
    }

}
