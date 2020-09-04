<?php

namespace App\Repositories\Project;

use App\Models\Project;
use App\Repositories\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

interface ProjectRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * To clone a project and its associated playlist,activities
     * @param \App\Repositories\Project\Request $request
     * @param Project $project
     */
    public function clone(Request $request, Project $project);
}
