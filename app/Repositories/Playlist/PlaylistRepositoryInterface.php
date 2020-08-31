<?php

namespace App\Repositories\Playlist;

use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
}
