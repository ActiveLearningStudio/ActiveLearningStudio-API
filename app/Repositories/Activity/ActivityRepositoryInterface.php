<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Models\Playlist;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

interface ActivityRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get the search request
     *
     * @param  array  $data
     * @return Collection
     */
    public function searchForm($data);

    /**
     * Get the advance search request
     *
     * @param  array  $data
     * @return Collection
     */
    public function advanceSearchForm($data);

    /**
     * Get the H5P Elasticsearch Field Values.
     *
     * @param Object $h5pContent
     * @return array
     */
    public function getH5pElasticsearchFields($h5pContent);
    
    /**
     * Tio Clone Activity and H5P Content
     * @param Request $request
     * @param Playlist $playlist
     * @param Activity $activity
     */
    public function clone(Request $request, Playlist $playlist, Activity $activity);
    
    /**
     * To Clone H5P content associated to an Activity
     * @param type $token
     * @param type $h5p_content_id
     */
    public function download_and_upload_h5p($token, $h5p_content_id);
}
