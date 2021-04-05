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
     * @param Object $h5pLibraryObject
     * @return array
     */
    public function getBrightcoveVideo($accountId, $videoId)
    {
        return  $this->model::select('id')->where('parameters', 'like', '%' . "videoId=$videoId" . '%')->first();
    }
}
