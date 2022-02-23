<?php

namespace App\Repositories\EducationLevel;

use App\Repositories\EloquentRepositoryInterface;

interface EducationLevelRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $suborganization
     * @param $data
     * 
     * @return mixed
     */
    public function getAll($suborganization, $data);
}
