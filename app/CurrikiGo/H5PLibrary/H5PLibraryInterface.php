<?php

namespace App\CurrikiGo\H5PLibrary;

/**
 * Interface for Curriki H5P Libraries
 */
interface H5PLibraryInterface
{

   /**
     * Build meta from content
     *
     * @return array
     */
    public function buildMeta();
}
