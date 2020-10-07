<?php

namespace App\Repositories\Team;

use App\Repositories\BaseRepository;
use App\Repositories\Team\TeamRepositoryInterface;
use App\Models\Team;
use Illuminate\Support\Collection;

class TeamRepository extends BaseRepository implements TeamRepositoryInterface
{
    /**
     * TeamRepository constructor.
     *
     * @param Team $model
     */
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }
}
