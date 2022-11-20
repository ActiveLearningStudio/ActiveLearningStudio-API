<?php

namespace App\Repositories\H5pContent;

use Djoudi\LaravelH5p\Eloquents\H5pContent;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface H5pContentRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Get the libraries's fields semantics.
     *
     * @param $accountId integer for brightcove video Example: 1
     * @param $videoId integer for brightcove video Example: 1
     * @param $dataPlayer integer for brightcove video Example: 1
     * @param $dataEmbed integer for brightcove video Example: 1
     * @return array
     */
    public function getBrightcoveVideo($accountId, $videoId, $dataPlayer, $dataEmbed);

    /**
     * Get the libraries's fields semantics.
     *
     * @param int $contentId for brightcove video
     * @return array
     */
    public function getLibrary($contentId);
     /**
     * Get the h5p content id for brightcove video.
     *
     * @param int $videoId for brightcove video
     * @return array
     */
    public function getH5pContentId($videoId);
}
