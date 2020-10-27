<?php

namespace App\Repositories\GcClasswork;

use App\Models\GcClasswork;
use App\Repositories\BaseRepository;
use App\Repositories\GcClasswork\GcClassworkRepositoryInterface;
use Illuminate\Support\Collection;

class GcClassworkRepository extends BaseRepository implements GcClassworkRepositoryInterface
{
    /**
     * GcClassworkRepository constructor.
     *
     * @param GcClasswork $model
     */
    public function __construct(GcClasswork $model)
    {
        parent::__construct($model);
    }
}
