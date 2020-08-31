<?php

namespace App\Repositories\ActivityItem;

use App\Models\ActivityItem;
use App\Repositories\BaseRepository;
use App\Repositories\ActivityItem\ActivityItemRepositoryInterface;
use Illuminate\Support\Collection;

class ActivityItemRepository extends BaseRepository implements ActivityItemRepositoryInterface
{
    /**
     * ActivityItemRepository constructor.
     *
     * @param ActivityItem $model
     */
    public function __construct(ActivityItem $model)
    {
        parent::__construct($model);
    }
}
