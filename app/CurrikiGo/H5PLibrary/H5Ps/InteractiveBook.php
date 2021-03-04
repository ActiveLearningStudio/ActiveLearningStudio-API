<?php

namespace App\CurrikiGo\H5PLibrary\H5Ps;

use App\CurrikiGo\H5PLibrary\H5PLibraryInterface;
use App\CurrikiGo\H5PLibrary\H5PLibraryFactory;

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
                foreach($this->content['chapters'] as $chapter) {
                    $meta[] = $this->buildChapter($chapter['chapter']);
                }
            }
        }
        return $meta;
    }

    private function buildChapter($chapter)
    {
        $data = [];
        $data['sub-content-id'] = $chapter['subContentId'];
        $data['library'] = $chapter['library'];
        $data['content-type'] = $chapter['metadata']['contentType'];
        $data['title'] = $chapter['metadata']['title'];
        $data['content'] = $this->loadContentByLibrary($data['library'], $chapter['params']);
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
