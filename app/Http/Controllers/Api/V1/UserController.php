<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\ProfileUpdateRequest;
use App\Http\Requests\V1\SharedProjectRequest;
use App\Http\Requests\V1\SuborganizationAddNewUser;
use App\Http\Requests\V1\SuborganizationUpdateUserDetail;
use App\Http\Requests\V1\UserCheckRequest;
use App\Http\Requests\V1\UserSearchRequest;
use App\Http\Resources\V1\Admin\ProjectResource;
use App\Http\Resources\V1\UserForTeamResource;
use App\Http\Resources\V1\UserResource;
use App\Http\Resources\V1\OrganizationResource;
use App\Repositories\User\UserRepositoryInterface;
use App\Rules\StrongPassword;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\V1\NotificationListResource;
use App\Http\Resources\V1\UserStatsResource;
use App\Models\Organization;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

/**
 * @group 2. User
 *
 * APIs for user management
 */
class UserController extends Controller
{
    private $userRepository;
    private $organizationRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        OrganizationRepositoryInterface $organizationRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->organizationRepository = $organizationRepository;

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
    public function getAllUsers(UserSearchRequest $userSearchRequest)
    {
        $data = $userSearchRequest->validated();

        return response([
            'users' => UserForTeamResource::collection($this->userRepository->searchByEmailAndName($data['search'])),
        ], 200);
    }

    /**
     * Get All Organization Users
     *
     * Get a list of the organization users.
     *
     * @urlParam  Organization $suborganization
     * @bodyParam search string required Search string for User Example: Abby
     *
     * @responseFile responses/user/users-for-team.json
     *
     * @param UserSearchRequest $userSearchRequest
     * @return Response
     */
    public function getOrgUsers(UserSearchRequest $userSearchRequest, Organization $suborganization)
    {
        $data = $userSearchRequest->validated();

        return response([
            'users' => UserForTeamResource::collection($this->organizationRepository->getOrgUsers($data, $suborganization)),
        ], 200);
    }

    /**
     * Check Organization User
     *
     * Check if organization user exist in specific organization or not.
     *
     * @urlParam  Organization $suborganization
     * @bodyParam user_id inetger required user Id Example: 1
     * @bodyParam organization_id inetger required organization Id Example: 1
     *
     * @response {
     *   "invited": true,
     *   "message": "Success"
     * }
     *
     * @response 400 {
     *   "invited": false,
     *   "message": "error"
     * }
     *
     * @param UserCheckRequest $userCheckRequest
     * @return Response
     */
    public function checkOrgUser(UserCheckRequest $userCheckRequest, Organization $suborganization)
    {
        $data = $userCheckRequest->validated();
        $exist_user_id = $suborganization->users()->where('user_id', $data['user_id'])->first();

        if (!$exist_user_id) {
            return response([
                'message' => 'This user must be added in ' . $suborganization->name . ' organization first.',
                'invited' => false,
            ], 400);
        }

        return response([
            'message' => 'Success',
            'invited' => true,
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
        return response([
            'errors' => ['Forbidden. Please use register to create new user.'],
        ], 403);
    }

    /**
     * Create New Organization User
     *
     * Create a new user in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function addNewUser(SuborganizationAddNewUser $addNewUserrequest, Organization $suborganization)
    {
        $this->authorize('addUser', $suborganization);
        $data = $addNewUserrequest->validated();

        $data['password'] = Hash::make($data['password']);
        $data['remember_token'] = Str::random(64);
        $data['email_verified_at'] = now();

        return \DB::transaction(function () use ($suborganization, $data) {

            $user = $this->userRepository->create(Arr::except($data, ['role_id']));
            $suborganization->users()->attach($user->id, ['organization_role_type_id' => $data['role_id']]);

            return response([
                'user' => new UserResource($this->userRepository->find($user->id)),
                'message' => 'User has been created successfully.',
            ], 200);

        });

        return response([
            'errors' => ['Failed to create user.'],
        ], 500);
    }

    /**
     * Update Organization User Detail
     *
     * Update user detail in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function updateUserDetail(SuborganizationUpdateUserDetail $addNewUserrequest, Organization $suborganization)
    {
        $this->authorize('updateUser', $suborganization);
        $data = $addNewUserrequest->validated();

        return \DB::transaction(function () use ($suborganization, $data) {

            if (isset($data['password']) && $data['password'] !== '') {
                $data['password'] = Hash::make($data['password']);
            }
            $user = $this->userRepository->update(Arr::except($data, ['user_id', 'role_id']), $data['user_id']);
            $suborganization->users()->updateExistingPivot($data['user_id'], ['organization_role_type_id' => $data['role_id']]);

            return response([
                'user' => new UserResource($this->userRepository->find($data['user_id'])),
                'message' => 'User has been updated successfully.',
            ], 200);

        });

        return response([
            'errors' => ['Failed to update user.'],
        ], 500);
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
     * Read All Notifications
     *
     * Read all notifications of the specified user.
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

    /**
     * Get All User Organizations
     *
     * Get a list of the users organizations
     *
     * @responseFile responses/organization/organizations.json
     *
     * @return Response
     */
    public function getOrganizations()
    {
        return OrganizationResource::collection(auth()->user()->organizations()->with('parent')->get());
    }

    /**
     * Set Default Organization
     *
     * Set default organization for the user.
     *
     * @bodyParam organization_id int required The id of the organization to be set as default Example: 1
     *
     * @response {
     *   "message": "Default organization has been set successfully."
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
     *     "Failed to set default organization."
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function setDefaultOrganization(Request $request)
    {
        $authenticated_user = auth()->user();

        $data = $request->validate([
            'organization_id' => [
                'required',
                Rule::exists('organization_user_roles')->where(function ($query) use ($authenticated_user) {
                    $query->where('user_id', $authenticated_user->id);
                }),
            ],
        ]);

        $authenticated_user = auth()->user();

        $is_updated = $this->userRepository->update([
            'default_organization' => $data['organization_id'],
        ], $authenticated_user->id);

        if ($is_updated) {
            return response([
                'message' => 'Default organization has been set successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to set default organization.'],
        ], 500);
    }

    /**
     * Get All Shared Projects
     *
     * Get a list of the shared projects of a user.
     *
     * @responseFile responses/project/projects.json
     *
     * @return Response
     */
    public function sharedProjects(SharedProjectRequest $request)
    {
        $user = User::with(['projects' => function($q){
                        $q->where('shared', true);
                    }])
                    ->whereId($request->user_id)->first();

        return response([
            'projects' => ProjectResource::collection($user->projects),
        ], 200);
    }

    /**
     * Users Basic Report
     *
     * Returns the paginated response of the users with basic reporting.
     *
     * @queryParam size Limit for getting the paginated records, Default 25. Example: 25
     * @queryParam query for getting the search records by name and email. Example: Test
     *
     * @responseFile responses/admin/user/users_report.json
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function reportBasic(Request $request)
    {
        return UserStatsResource::collection($this->userRepository->reportBasic($request->all()), 200);
    }

}
