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
     * @return array
     */
    public static function loadContentByLibrary($library, $content)
    {
        $h5pFactory = new H5PLibraryFactory();
        $h5pLib = $h5pFactory->init($library, $content);
        $h5pMeta = [];
        if ($h5pLib) {
            $h5pMeta = $h5pLib->buildMeta();
        }
        return $h5pMeta;
    }

    public static function buildElement($content)
    {
        $data = [];
        $data['sub-content-id'] = $content['subContentId'];
        $data['library'] = $content['library'];
        $data['content-type'] = $content['metadata']['contentType'];
        $data['title'] = $content['metadata']['title'];
        $data['content'] = self::loadContentByLibrary($data['library'], $content['params']);
        return $data;
    }

}
