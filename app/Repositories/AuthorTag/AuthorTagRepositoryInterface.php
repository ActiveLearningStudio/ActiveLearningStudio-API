<?php

namespace App\Repositories\AuthorTag;

use App\Repositories\EloquentRepositoryInterface;

interface AuthorTagRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data);
}
