<?php

namespace App\Repositories\GoogleClassroom;

use App\Models\GcClasswork;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface GoogleClassroomRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * To save course share to Google classroom
     *
     * @param $course
     */
    public function saveCourseShareToGcClass($course);
}
