<?php

namespace App\Repositories\ActivityItem;

use App\Models\ActivityItem;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ActivityItemRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data);
}
