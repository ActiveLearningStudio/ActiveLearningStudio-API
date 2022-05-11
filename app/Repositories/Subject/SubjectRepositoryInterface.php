<?php

namespace App\Repositories\Subject;

use App\Repositories\EloquentRepositoryInterface;

interface SubjectRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $suborganization
     * @param $data
     * @return mixed
     */
    public function getAll($data, $suborganization);

    /**
     * @param $subjectIds
     *
     * @return mixed
     */
    public function getSubjectIdsWithMatchingName($subjectIds);
}
