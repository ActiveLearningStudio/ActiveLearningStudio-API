<?php

namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Support\Collection;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }
}
