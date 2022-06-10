<?php

namespace App\Repositories\ActivityItem;

use App\Repositories\EloquentRepositoryInterface;

interface ActivityItemRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * @param $suborganization
     * @param $data
     * 
     * @return mixed
     */
    public function getAll($suborganization, $data);

    /**
     * @return mixed
     */
    public function getActivityLayouts();

    /**
     * @param $libraryName
     * @param $libraryMajorVersion
     * @param $libraryMinorVerison
     * @return mixed
     */
    public function getActivityItem($libraryName, $libraryMajorVersion, $libraryMinorVerison);
}
