<?php

namespace App\CurrikiGo\H5PLibrary;

use App\CurrikiGo\H5PLibrary\H5Ps\InteractiveBook;
use App\CurrikiGo\H5PLibrary\H5Ps\Column;
use App\CurrikiGo\H5PLibrary\H5Ps\Common;

/**
 * Factory Class for H5P Libraries
 */
class H5PLibraryFactory
{
    
    /**
     * Initialize and return an library object.
     *
     * @param string $library The library name
     * @param array $content The meta data content.
     * @return H5PLibraryFactory
     */
    public function init($library, $content)
    {
        $library = $this->cleanString($library);
        switch ($library) {
            case 'InteractiveBook':
                return new InteractiveBook($content);
            case 'Column':
                return new Column($content);
            default:
                // When there is no interaction type
                return new Common($content);
        }
    }

    /**
     * Clean library name
     *
     * @param string $library The library name
     * @return string
     */
    private function cleanString($library)
    {
        $pattern = "/H5P.(\w+)(-|\s)(.*)/";
        if (preg_match($pattern, $library, $matches)) {
            return $matches[1];
        }
        return '';
    }
}
