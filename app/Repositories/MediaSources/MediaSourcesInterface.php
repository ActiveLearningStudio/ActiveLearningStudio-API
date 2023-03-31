<?php

namespace App\Repositories\MediaSources;

use App\Repositories\EloquentRepositoryInterface;

interface MediaSourcesInterface extends EloquentRepositoryInterface
{
    /**
     * To get media source id by name
     *
     * @param $mediaSourceName string
     * @return int
     */
    public function getMediaSourceIdByName($mediaSourceName);

    /**
     * To get media sources by type
     *
     * @param $mediaSourceName string
     * @return object
     */
    public function getMediaSourcesByType($mediaSourceType);
}
