<?php

namespace App\Repositories\ActivityType;

use App\Models\ActivityType;
use App\Repositories\BaseRepository;
use App\Repositories\ActivityType\ActivityTypeRepositoryInterface;
use Illuminate\Support\Collection;

class ActivityTypeRepository extends BaseRepository implements ActivityTypeRepositoryInterface
{
    /**
     * ActivityTypeRepository constructor.
     *
     * @param ActivityType $model
     */
    public function __construct(ActivityType $model)
    {
        parent::__construct($model);
    }
}
