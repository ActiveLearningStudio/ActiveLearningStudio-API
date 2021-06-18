<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Models\Playlist;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Support\Collection;

interface ActivityRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get the search request
     *
     * @param array $data
     * @return Collection
     */
    public function searchForm($data);

    /**
     * Get the advance search request
     *
     * @param array $data
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
     * To clone a playlist and associated activities
     *
     * @param Playlist $playlist
     * @param Activity $activity
     * @param string $token
     */
    public function clone(Playlist $playlist, Activity $activity, $token);

    /**
     * To Clone H5P content associated to an Activity
     *
     * @param $token
     * @param $h5p_content_id
     */
    public function download_and_upload_h5p($token, $h5p_content_id);

    /**
     * @param $playlistId
     */
    public function getPlaylistIsPublicValue($playlistId);
    
    /**
     * Get latest order of activity for Playlist
     * @param $playlist_id
     * @return int
     */
    public function getOrder($playlist_id);

    /**
     * To Populate missing order number, One time script
     */
    public function populateOrderNumber();

     /**
     * To clone Activity
     * @param Playlist $playlist
     * @param Activity $authUser
     * @param string $playlist_dir
     * @param string $activity_dir
     * @param string $extracted_folder
     * 
     */
    public function importActivity(Playlist $playlist, $authUser, $playlist_dir, $activity_dir, $extracted_folder);
}
