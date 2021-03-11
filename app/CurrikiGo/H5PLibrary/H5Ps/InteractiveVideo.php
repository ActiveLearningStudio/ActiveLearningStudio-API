<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\Helpers\H5PHelper;

/**
 * InteractiveVideo H5P library
 */
class InteractiveVideo implements H5PLibraryInterface
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
            if (isset($this->content['interactiveVideo']) && isset($this->content['interactiveVideo']['assets']) 
            && !empty($this->content['interactiveVideo']['assets'])) {
                foreach ($this->content['interactiveVideo']['assets']['interactions'] as $item) {
                    $meta[] = H5PHelper::buildElement($item['action']);
                }
                if (isset($this->content['interactiveVideo']['summary']) && isset($this->content['interactiveVideo']['summary']['task']) && !empty($this->content['interactiveVideo']['summary']['task'])) {
                    $meta[] = H5PHelper::buildElement($this->content['interactiveVideo']['summary']['task']);
                }
            }
        }
        return $meta;
    }

}
