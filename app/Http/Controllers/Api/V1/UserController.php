<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProfileUpdateRequest;
use App\Http\Resources\V1\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Rules\StrongPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

/**
 * @group  User management
 *
 * APIs for managing users
 */
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

        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the user.
     *
     * @return Response
     */
    public function index()
    {
        return response([
            'users' => UserResource::collection($this->userRepository->all()),
        ], 200);
    }

    /**
     * Store a newly created user in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO: do we need user create functionality for admin ?
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
        return response([
            'user' => new UserResource($user),
        ], 200);
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
     * Subscribe.
     *
     * @param Request $request
     * @return Response
     */
    public function subscribe(Request $request)
    {
        $authenticated_user = auth()->user();
        $is_subscribed = $this->userRepository->update([
            'subscribed' => true,
            'subscribed_ip' => $request->ip(),
        ], $authenticated_user->id);

        if ($is_subscribed) {
            return response([
                'user' => new UserResource($this->userRepository->find($authenticated_user->id)),
            ], 200);
        }

        return response([
            'errors' => ['Failed to subscribe.'],
        ], 500);
    }

    /**
     * Update the specified user in storage.
     *
     * @param ProfileUpdateRequest $profileUpdateRequest
     * @param User $user
     * @return Response
     */
    public function update(ProfileUpdateRequest $profileUpdateRequest, User $user)
    {
        $data = $profileUpdateRequest->validated();

        $is_updated = $this->userRepository->update($data, $user->id);

        if ($is_updated) {
            return response([
                'user' => new UserResource($this->userRepository->find($user->id)),
                'message' => 'Profile has been updated successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to update profile.'],
        ], 500);
    }

    /**
     * Update password of the specified user in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function updatePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', new StrongPassword],
        ]);

        $authenticated_user = auth()->user();
        if (!Hash::check($data['current_password'], $authenticated_user->password)) {
            return response([
                'errors' => ['Invalid request.'],
            ], 400);
        }

        $is_updated = $this->userRepository->update([
            'password' => Hash::make($data['password']),
        ], $authenticated_user->id);

        if ($is_updated) {
            return response([
                'message' => 'Password has been updated successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to update password.'],
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
