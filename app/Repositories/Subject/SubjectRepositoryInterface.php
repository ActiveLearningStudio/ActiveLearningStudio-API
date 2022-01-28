<?php

namespace App\Repositories\Subject;

use App\Repositories\EloquentRepositoryInterface;

interface SubjectRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data);
}
