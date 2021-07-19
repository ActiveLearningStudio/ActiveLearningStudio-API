<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\UserLmsSettings\UserLmsSettingsRepositoryInterface;
use App\Http\Resources\V1\UserLmsSettingResource;

class UserLmsSettingsController extends Controller
{
    private $repo;

    /**
     * UserLmsSettingsController constructor.
     *
     * @param UserLmsSettingsRepositoryInterface $repo
     */
    public function __construct(UserLmsSettingsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Get a full list of LMS settings available to the user
     *
     * @return Response
     */
    public function index()
    {
        if ($this->repo->all() == '[null]') {
            return response([
                'errors' => ['LMS settings not found for this user.'],
            ], 200);
        }

        return UserLmsSettingResource::collection($this->repo->all());
    }
}
