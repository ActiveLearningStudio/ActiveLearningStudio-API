<?php

namespace App\Repositories\UserLogin;

use App\Repositories\BaseRepository;
use App\Repositories\UserLogin\UserLoginRepositoryInterface;
use App\Models\UserLogin;
use Illuminate\Support\Collection;

class UserLoginRepository extends BaseRepository implements UserLoginRepositoryInterface
{
    /**
     * UserLoginRepository constructor.
     *
     * @param UserLogin $model
     */
    public function __construct(UserLogin $model)
    {
        parent::__construct($model);
    }
}
