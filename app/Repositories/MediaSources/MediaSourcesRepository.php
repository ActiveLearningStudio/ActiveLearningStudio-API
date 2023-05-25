<?php

namespace App\Repositories\MediaSources;

use App\Repositories\BaseRepository;
use App\Models\MediaSource;

class MediaSourcesRepository extends BaseRepository implements MediaSourcesInterface
{

    /**
     * MediaSourcesRepository constructor
     * @param MediaSource $model
     */
    public function __construct(MediaSource $model)
    {
        $this->model = $model;
    }

    /**
     * To get media source id by name
     *
     * @param $mediaSourceName string
     * @return int
     */
    public function getMediaSourceIdByName($mediaSourceName)
    {
        $mediaSourcesRow = $this->model->where('name', $mediaSourceName)
                              ->where('media_type', 'Video')
                              ->first();
        $mediaSourcesId = !empty($mediaSourcesRow) ? $mediaSourcesRow->id : 0;
        return $mediaSourcesId;
    }

    /**
     * To get media sources by type
     *
     * @param $mediaSourceName string
     * @return object
     */
    public function getMediaSourcesByType($mediaSourceType)
    {
        $mediaSources = $this->model->where('media_type', $mediaSourceType)
                              ->where('name','!=', 'Safari Montage')  
                              ->get();
        return $mediaSources;
    }
    
}
