<?php

namespace App\Repositories\Playlist;

use App\Models\Playlist;
use App\Models\Project;
use App\Repositories\BaseRepository;
use App\Repositories\Playlist\PlaylistRepositoryInterface;
use Illuminate\Support\Collection;

class PlaylistRepository extends BaseRepository implements PlaylistRepositoryInterface
{
    /**
     * PlaylistRepository constructor.
     *
     * @param Playlist $model
     */
    public function __construct(Playlist $model)
    {
        parent::__construct($model);
    }

    /**
     * Get latest order of playlist for Project
     *
     * @param Project $project
     * @return int
     */
    public function getOrder(Project $project)
    {
        $playlist = $this->model->where('project_id', $project->id)
            ->orderBy('order', 'desc')
            ->first();

        return ($playlist && $playlist->order) ? $playlist->order : 0;
    }

    /**
     * Save Playlist array
     *
     * @param array $playlists
     */
    public function saveList(array $playlists)
    {
        foreach ($playlists as $playlist) {
            $this->update([
                'order' => $playlist['order'],
            ], $playlist['id']);
        }
    }
}
