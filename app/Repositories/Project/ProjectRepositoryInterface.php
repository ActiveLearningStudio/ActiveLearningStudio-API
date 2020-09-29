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
     * @param $authenticated_user
     * @param Project $project
     * @param string $token Authenticated user token
     */
    public function clone($authenticated_user, Project $project, $token);

    /**
     * To fetch project based on LMS settings
     * @param $lms_url
     * @param $lti_client_id
     * @return Project $project
     */
    public function fetchByLmsUrlAndLtiClient($lms_url, $lti_client_id);

    /**
     * To fetch recent public project
     * @return Project $projects
     */
    public function fetchRecentPublic($limit);

    /**
     * To fetch recent public projects
     * @return Project $projects
     */
    public function fetchDefault($defaultEmail);
}
