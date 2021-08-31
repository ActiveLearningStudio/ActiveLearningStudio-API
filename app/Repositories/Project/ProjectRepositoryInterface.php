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
     * @param int $organization_id
     */
    public function clone($authenticated_user, Project $project, $token, $organization_id = null);

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
     * @param $organization_id
     * @return Project $projects
     */
    public function fetchRecentPublic($limit, $organization_id);

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
     * @param $organization_id
     * @return bool
     */
    public function checkIsDuplicate($authenticated_user, $project_id, $organization_id);

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

    /**
     * @param $authenticated_user
     * @param $project
     * @param $organization_id
     * @return bool
     */
    public function favoriteUpdate($authenticated_user, $project, $organization_id);

    /**
     * @param $data
     * @param $suborganization
     * @return mixed
     */
    public function getAll($data, $suborganization);

    /**
     * @param $data
     * @param $suborganization
     * @return mixed
     */
    public function getTeamProjects($data, $suborganization);

    /**
     * @param $project
     * @param $index
     * @return Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function updateIndex($project, $index);

    /**
     * @param $projects
     * @param $flag
     * @return string
     * @throws GeneralException
     */
    public function toggleStarter($project, $index);

    /** 
     * To export project and associated playlists
     * 
     * @param $authUser
     * @param Project $project
     * @throws GeneralException
     */
    public function exportProject($authUser, Project $project);

    /**
     * To import project and associated playlists
     *
     * @param $authUser
     * @param Project $path
     * @param int $suborganization_id
     * @throws GeneralException
     */
    public function importProject($authUser, $path, $suborganization_id);
    
}
