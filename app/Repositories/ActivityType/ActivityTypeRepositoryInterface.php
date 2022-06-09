<?php

namespace App\Repositories\ActivityType;

use App\Repositories\EloquentRepositoryInterface;

interface ActivityTypeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $suborganization
     * @param $data
     * 
     * @return mixed
     */
    public function getAll($suborganization, $data);

}
