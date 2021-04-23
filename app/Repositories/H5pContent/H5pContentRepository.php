<?php

namespace App\Repositories\H5pContent;

use Djoudi\LaravelH5p\Eloquents\H5pContent;
use App\Repositories\BaseRepository;
use App\Repositories\H5pContent\H5pContentRepositoryInterface;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class H5pContentRepository extends BaseRepository implements H5pContentRepositoryInterface
{
    /**
     * H5pContentRepository constructor.
     *
     * @param H5pContent $model
     */
    public function __construct(H5pContent $model)
    {
        parent::__construct($model);
    }

    /**
     * Get the h5p content for brightcove video.
     *
     * @param int $accountId for brightcove video
     * @param int $videoId for brightcove video
     * @param int $dataPlayer for brightcove video
     * @param int $dataEmbed for brightcove video
     * @return array
     */
    public function getBrightcoveVideo($accountId, $videoId, $dataPlayer, $dataEmbed)
    {
        $brightcoveVideoUrl = "https:\\\/\\\/players.brightcove.net\\\/$accountId\\\/{$dataPlayer}_{$dataEmbed}\\\/index.html?videoId=$videoId";
        return  $this->model::select('id')->where('parameters', 'like', '%' . $brightcoveVideoUrl . '%')->whereHas('activity')->first();
    }
}
