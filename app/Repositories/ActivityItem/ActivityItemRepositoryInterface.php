<?php

namespace App\Repositories\ActivityItem;

use App\Models\ActivityItem;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

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
