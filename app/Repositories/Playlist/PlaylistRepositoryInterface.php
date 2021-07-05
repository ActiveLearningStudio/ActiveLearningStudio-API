<?php

namespace App\Repositories\Playlist;

use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

interface PlaylistRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param Project $project
     * @return int
     */
    public function getOrder(Project $project);

    /**
     * Save Playlist array
     *
     * @param array $playlists
     */
    public function saveList(array $playlists);

    /**
     * Clone Project,PlayList and activities
     * @param Project $project
     * @param Playlist $playlist
     * @param string $token
     */
    public function clone(Project $project, Playlist $playlist, string $token);

    /**
     * Get Playlists for Preview
     *
     * @param Playlist $playlist
     */
    public function getPlaylistForPreview(Playlist $playlist);

    /**
     * Get Playlists for Preview
     *
     * @param Playlist $playlist
     * @return array
     */
    public function getPlaylistWithProject(Playlist $playlist);

    /**
     * To Import Playlist and associated activities
     *
     * @param Project $project
     * @param string $authUser
     * @param string $extracted_folder
     * @param string $playlist_dir
     */
    public function playlistImport(Project $project, $authUser, $extracted_folder, $playlist_dir="");
}
