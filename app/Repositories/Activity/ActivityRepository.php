<?php

namespace App\Repositories\Activity;

use App\Models\Activity;
use App\Repositories\BaseRepository;
use App\Repositories\Activity\ActivityRepositoryInterface;
use Illuminate\Support\Collection;

class ActivityRepository extends BaseRepository implements ActivityRepositoryInterface
{
    /**
     * ActivityRepository constructor.
     *
     * @param Activity $model
     */
    public function __construct(Activity $model)
    {
        parent::__construct($model);
    }
}
