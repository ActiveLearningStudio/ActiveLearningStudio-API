<?php

namespace App\Repositories\EducationLevel;

use App\Repositories\EloquentRepositoryInterface;

interface EducationLevelRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data);
}
