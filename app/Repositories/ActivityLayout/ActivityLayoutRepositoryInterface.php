<?php

namespace App\Repositories\ActivityLayout;

use App\Repositories\EloquentRepositoryInterface;

interface ActivityLayoutRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $data
     * @return mixed
     */
    public function getAll($data);

    /**
     * @param $libraryName
     * @param $libraryMajorVersion
     * @param $libraryMinorVerison
     * @return mixed
     */
    public function getActivityItem($libraryName, $libraryMajorVersion, $libraryMinorVerison);
}
