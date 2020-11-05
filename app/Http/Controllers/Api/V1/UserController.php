<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProfileUpdateRequest;
use App\Http\Requests\V1\UserSearchRequest;
use App\Http\Resources\V1\UserForTeamResource;
use App\Http\Resources\V1\UserResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Rules\StrongPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\NotificationListResource;

/**
 * @group 2. User
 *
 * APIs for user management
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
     * Get All Users
     *
     * Get a list of the users.
     *
     * @responseFile responses/user/users.json
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
     * Get All Users for Team
     *
     * Get a list of the users for Team.
     *
     * @bodyParam search string required Search string for User Example: Abby
     *
     * @responseFile responses/user/users-for-team.json
     *
     * @param UserSearchRequest $userSearchRequest
     * @return Response
     */
    public function getUsersForTeam(UserSearchRequest $userSearchRequest)
    {
        $data = $userSearchRequest->validated();

        return response([
            'users' => UserForTeamResource::collection($this->userRepository->searchByEmailAndName($data['search'])),
        ], 200);
    }

    /**
     * Create User
     *
     * Create a new user in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // TODO: do we need user create functionality for admin ?
        return response([
            'errors' => ['Forbidden. Please use register to create new user.'],
        ], 403);
    }

    /**
     * Get User
     *
     * Get the specified user detail.
     *
     * @urlParam user required The Id of a user Example: 1
     *
     * @responseFile responses/user/user.json
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
     * Get Authenticated User
     *
     * Get the authenticated user detail.
     *
     * @responseFile responses/user/user.json
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
     * Accept Terms
     *
     * Accept Terms and Privacy Policy.
     *
     * @responseFile responses/user/user.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to subscribe."
     *   ]
     * }
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
     * Update User
     *
     * Update the specified user in storage.
     *
     * @urlParam user required The Id of a user Example: 1
     * @bodyParam first_name string required First name of a user Example: John
     * @bodyParam last_name string required Last name of a user Example: Doe
     * @bodyParam organization_name string Organization name of a user Example: Curriki
     * @bodyParam website string Website url of a user Example: www.currikistudio.org
     * @bodyParam job_title string Job title of a user Example: Developer
     * @bodyParam address string Address of a user Example: 20660 Stevens Creek Blvd #332, Cupertino, CA 95014
     * @bodyParam phone_number string Phone number of a user Example: +1234567890
     *
     * @response {
     *   "user": {
     *     "id": 1,
     *     "first_name": "John",
     *     "last_name": "Doe",
     *     "email": "john.doe@currikistudio.org",
     *     "organization_name": "Curriki",
     *     "organization_type": null,
     *     "job_title": "Developer",
     *     "address": "20660 Stevens Creek Blvd #332, Cupertino, CA 95014",
     *     "phone_number": "+1234567890",
     *     "website": "www.currikistudio.org",
     *     "subscribed": true
     *   },
     *   "message": "Profile has been updated successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update profile."
     *   ]
     * }
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
     * Update Password
     *
     * Update password of the specified user in storage.
     *
     * @bodyParam current_password string required Current password of a user Example: Password123
     * @bodyParam password string required New password to be set for a user Example: Password321
     * @bodyParam password_confirmation string required Password confirmation of new password Example: Password321
     *
     * @response {
     *   "message": "Password has been updated successfully."
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid request."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update password."
     *   ]
     * }
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
     * Delete User
     *
     * Remove the specified user from storage.
     *
     * @urlParam user required The Id of a user Example: 1
     *
     * @response {
     *   "message": "User has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete profile."
     *   ]
     * }
     *
     * @param User $user
     * @return Response
     */
    public function destroy(User $user)
    {
        $is_deleted = $this->userRepository->delete($user->id);

        if ($is_deleted) {
            return response([
                'message' => 'User has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete profile.'],
        ], 500);
    }

    /**
     * Get All User Notifications
     *
     * Get a list of the users unread notification
     *
     * @responseFile responses/notifications/notifications.json
     *
     * @param Request $request
     * @return Response
     */
    public function listNotifications(Request $request)
    {
        return response([
            'notifications' => $this->userRepository->fetchListing(auth()->user()->notifications()),
            'unread_count' => auth()->user()->unreadNotifications->count()
        ], 200);
    }

    /**
     * Read Notification
     *
     * Read notification of the specified user.
     *
     * @urlParam $notification_id string required Current id of a notification Example: 123
     *
     * @responseFile responses/notifications/notifications.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to read notification."
     *   ]
     * }
     *
     * @param Request $request
     * @param $notification_id
     * @return Response
     */
    public function readNotification(Request $request, $notification_id)
    {
        $notification = auth()->user()->unreadNotifications()->find($notification_id);
        if ($notification) {
            $notification->markAsRead();

            return response([
                'notifications' => $this->userRepository->fetchListing(auth()->user()->notifications),
            ], 200);
        }

        return response([
            'errors' => ['Failed to read notification.'],
        ], 500);
    }

    /**
     * Read All Notification
     *
     * Read all notification of the specified user.
     *
     * @responseFile responses/notifications/notifications.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to read notifications."
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function readAllNotification(Request $request)
    {
        $notifications = auth()->user()->unreadNotifications;
        if ($notifications) {
            $notifications->markAsRead();

            return response([
                'notifications' => $this->userRepository->fetchListing(auth()->user()->notifications()),
            ], 200);
        }

        return response([
            'errors' => ['Failed to read notifications.'],
        ], 500);

    }

    /**
     * Delete Notification
     *
     * Remove the specified notification from storage.
     *
     * @urlParam $notification_id string required Current id of a notification Example: 123
     *
     * @response {
     *   "message": "Notification has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete notification."
     *   ]
     * }
     *
     * @param $notification_id
     * @return Response
     */
    public function deleteNotification(Request $request, $notification_id)
    {
        $is_deleted = auth()->user()->notifications()->find($notification_id)->delete();

        if ($is_deleted) {
            return response([
                'message' => 'Notification has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete notification.'],
        ], 500);
    }


}
