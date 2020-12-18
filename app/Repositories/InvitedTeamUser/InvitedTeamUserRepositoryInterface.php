<?php

namespace App\Repositories\InvitedTeamUser;

use App\Repositories\EloquentRepositoryInterface;

interface InvitedTeamUserRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Search by email and name
     *
     * @param $email
     */
    public function searchByEmail($email);

}
