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
     * @param Request $request
     * @param Project $project
     * @param Playlist $playlist
     *
     */
    public function clone(Request $request, Project $project, Playlist $playlist);

    /**
     * Get Playlists for Preview
     *
     * @param Playlist $playlist
     */
    public function getPlaylistForPreview(Playlist $playlist);
}
