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
     *
     * @param $authenticated_user
     * @param Project $project
     * @param string $token Authenticated user token
     */
    public function clone($authenticated_user, Project $project, $token);

    /**
     * To fetch project based on LMS settings
     *
     * @param $lms_url
     * @param $lti_client_id
     * @return Project $project
     */
    public function fetchByLmsUrlAndLtiClient($lms_url, $lti_client_id);

    /**
     * To fetch recent public project
     *
     * @param $limit
     * @return Project $projects
     */
    public function fetchRecentPublic($limit);

    /**
     * To fetch recent public projects
     *
     * @param $default_email
     * @return Project $projects
     */
    public function fetchDefault($default_email);

    /**
     * To reorder the list of projects
     * @param array $projects
     */
    public function saveList(array $projects);
    /**
     * To Populate missing order number, One time script
     */
    public function populateOrderNumber();

    /**
     * Get latest order of project for User
     * @param $authenticated_user
     * @return int
     */
    public function getOrder($authenticated_user);

    /**
     * @param $authenticated_user
     * @param $project_id
     * @return bool
     */
    public function checkIsDuplicate($authenticated_user,$project_id);

    /**
     * @param $project
     * @return mixed
     */
    public function indexing($project);

    /**
     * @param $project
     * @return mixed
     */
    public function statusUpdate($project);
}
