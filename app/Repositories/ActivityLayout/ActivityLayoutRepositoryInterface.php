<?php

namespace App\Repositories\ActivityLayout;

use App\Repositories\EloquentRepositoryInterface;

interface ActivityLayoutRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $suborganization
     * @param $data
     * @return mixed
     */
    public function getAll($suborganization, $data);

    /**
     * @param $libraryName
     * @param $libraryMajorVersion
     * @param $libraryMinorVerison
     * @return mixed
     */
    public function getActivityItem($libraryName, $libraryMajorVersion, $libraryMinorVerison);
}
