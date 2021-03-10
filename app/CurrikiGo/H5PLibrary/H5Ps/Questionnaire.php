<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\Helpers\H5PHelper;

/**
 * Questionnaire H5P library
 */
class Questionnaire implements H5PLibraryInterface
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
            if (isset($this->content['questionnaireElements']) && !empty($this->content['questionnaireElements'])) {
                foreach ($this->content['questionnaireElements'] as $item) {
                    $meta[] = $this->buildIndex($item['library']);
                }
            }
        }
        return $meta;
    }

    private function buildIndex($content)
    {
        $data = [];
        $data['sub-content-id'] = $content['subContentId'];
        $data['library'] = $content['library'];
        $data['content-type'] = $content['metadata']['contentType'];
        $data['title'] = $content['metadata']['title'];
        $data['content'] = H5PHelper::loadContentByLibrary($data['library'], $content['params']);
        return $data;
    }

}
