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

    /**
     * @param $educationLevelIds
     *
     * @return mixed
     */
    public function getEducationLevelIdsWithMatchingName($educationLevelIds);
}
