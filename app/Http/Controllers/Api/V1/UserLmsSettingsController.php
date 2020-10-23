<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\UserLmsSettings\UserLmsSettingsRepositoryInterface;
use App\Http\Resources\V1\UserLmsSettingResource;

class UserLmsSettingsController extends Controller
{
    private $repo;

    public function __construct(UserLmsSettingsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return UserLmsSettingResource::collection($this->repo->all());
    }
}
