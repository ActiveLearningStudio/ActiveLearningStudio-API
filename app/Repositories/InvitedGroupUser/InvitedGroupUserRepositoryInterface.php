<?php

namespace App\Repositories\InvitedGroupUser;

use App\Repositories\EloquentRepositoryInterface;

interface InvitedGroupUserRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Search by email and name
     *
     * @param $email
     */
    public function searchByEmail($email);

}
