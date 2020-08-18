<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the user.
     *
     * @return Response
     */
    public function index()
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->role === 'admin') {
            return response([
                'users' => UserResource::collection($this->userRepository->all()),
            ], 200);
        }

        return response([
            'errors' => ['Forbidden.'],
        ], 403);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO: do we need user create functionality for admin
        return response([
            'errors' => ['Forbidden. Please user register to create new user.'],
        ], 403);
    }

    /**
     * Display the specified user.
     *
     * @param User $user
     * @return Response
     */
    public function show(User $user)
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->role === 'admin' || $authenticated_user->id === $user->id) {
            return response([
                'user' => new UserResource($user),
            ], 200);
        }

        return response([
            'errors' => ['Forbidden. You are trying to get other user\'s profile.'],
        ], 403);
    }

    /**
     * Display the authenticated user.
     *
     * @return Response
     */
    public function me()
    {
        $authenticated_user = auth()->user();
        return response([
            'user' => new UserResource($authenticated_user),
        ], 200);
    }

    /**
     * Update the specified user in storage.
     *
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->role !== 'admin' && $authenticated_user->id !== $user->id) {
            return response([
                'errors' => ['Forbidden. You are trying to change other user\'s profile.'],
            ], 403);
        }

        $is_updated = $this->userRepository->update($request->only([
            'first_name',
            'last_name',
            // 'name',
            // 'email',
            'organization_name',
            'organization_type',
            'website',
            'job_title',
            'address',
            'phone_number',
        ]), $user->id);

        if ($is_updated) {
            return response([
                'user' => new UserResource($this->userRepository->find($user->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to update profile.'],
        ], 500);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $authenticated_user = auth()->user();

        if ($authenticated_user->role !== 'admin' && $authenticated_user->id !== $user->id) {
            return response([
                'errors' => ['Forbidden. You are trying to delete other user\'s profile.'],
            ], 403);
        }

        $is_deleted = $this->userRepository->delete($user->id);

        if ($is_deleted) {
            return response([
                'message' => 'User is deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete profile.'],
        ], 500);
    }
}
