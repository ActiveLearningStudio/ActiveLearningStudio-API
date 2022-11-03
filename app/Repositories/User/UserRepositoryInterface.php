<?php

namespace App\Repositories\User;

use App\Repositories\EloquentRepositoryInterface;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Search by name
     *
     * @param $name
     */
    public function searchByName($name);

    /**
     * Search by name and email
     *
     * @param $email
     */
    public function searchByEmailAndName($email);

    /**
     * To fetch user from token
     * @param $accessToken
     * @return bool
     */
    public function parseToken($accessToken);

    /**
     * To arrange listing of notifications
     * @param $notifications
     * @return mixed
     */
    public function fetchListing($notifications);

    /**
     * Check if user has the specified permission in the provided organization
     *
     * @param $user
     * @param $permission
     * @param $organization
     * @return boolean
     */
    public function hasPermissionTo($user, $permission, $organization);

    /**
     * Users basic report, projects, playlists and activities count
     * @param $data
     * @return mixed
     */
    public function reportBasic($data);

    /**
     * To get exported project list of last 10 days
     * @param $days_limit
     * @param $suborganization
     * @return array
     */
    public function getUsersExportProjectList($days_limit, $suborganization);

    /**
     * To get exported project list of last 10 days
     * @param $suborganization
     * @param $data
     * @return mixed
     */
    public function getUsersExportIndependentActivitiesList($suborganization, $data);

    /**
     * To fix the dual signups in users table
     * @return bool
     */
    public function eliminateDualSignup();
}
