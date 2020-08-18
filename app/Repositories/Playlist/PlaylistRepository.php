<?php

namespace App\Repositories\Playlist;

use App\Models\Playlist;
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
}
