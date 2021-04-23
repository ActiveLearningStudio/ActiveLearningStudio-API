<?php

namespace App\Repositories\InvitedOrganizationUser;

use App\Repositories\EloquentRepositoryInterface;

interface InvitedOrganizationUserRepositoryInterface extends EloquentRepositoryInterface
{

    /**
     * Search by email and name
     *
     * @param $email
     */
    public function searchByEmail($email);

}
