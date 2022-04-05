<?php

namespace App\Repositories\ActivityType;

use App\Models\ActivityType;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface ActivityTypeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data);

}
