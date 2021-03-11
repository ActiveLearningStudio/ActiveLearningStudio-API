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
            if (isset($this->content['chapters']) && !empty($this->content['chapters'])) {
                foreach ($this->content['chapters'] as $chapter) {
                    $meta[] = H5PHelper::buildElement($chapter['chapter']);
                }
            }
        }
        return $meta;
    }

    /*private function buildChapter($chapter)
    {
        $data = [];
        $data['sub-content-id'] = $chapter['subContentId'];
        $data['library'] = $chapter['library'];
        $data['content-type'] = $chapter['metadata']['contentType'];
        $data['title'] = $chapter['metadata']['title'];
        $data['content'] = H5PHelper::loadContentByLibrary($data['library'], $chapter['params']);
        return $data;
    }*/

}
