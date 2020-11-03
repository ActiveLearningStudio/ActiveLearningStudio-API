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
}
