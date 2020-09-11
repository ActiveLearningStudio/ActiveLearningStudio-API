<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUser;
use App\Http\Requests\Admin\UpdateUser;
use App\Http\Resources\V1\Admin\UserResource;
use App\Repositories\Admin\User\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\View\View;

class UserController extends Controller
{
    private $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = resolve(UserRepository::class);
//        $this->authorizeResource(User::class, 'user');
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return UserResource::collection($this->userRepository->getUsers($request->start, $request->length));
    }

    /**
     * @param $id
     * @return UserResource
     */
    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        return new UserResource($user);
    }

    /**
     * @param StoreUser $request
     * @return UserResource
     * @throws GeneralException
     */
    public function store(StoreUser $request)
    {
        $user = $this->userRepository->createUser($request->only('email', 'password', 'first_name', 'last_name', 'name', 'organization_name', 'job_title'));
        return new UserResource($user);
    }

    /**
     * @param StoreUser $request
     * @return UserResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(UpdateUser $request, $id)
    {
        $response = $this->userRepository->updateUser($id, $request->only('email', 'password', 'first_name', 'last_name', 'name'), $request->clone_project_id);
        return response(['message' => $response], 200);
    }

    /**
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->userRepository->destroyUser($id)], 200);
    }
}
