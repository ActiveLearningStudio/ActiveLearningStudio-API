<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Http\Resources\V1\OrganizationResource;
use App\Http\Resources\V1\OrganizationRoleResource;
use App\Http\Resources\V1\OrganizationVisibilityTypeResource;
use App\Http\Requests\V1\SuborganizationSave;
use App\Http\Requests\V1\SuborganizationUpdate;
use App\Http\Requests\V1\SuborganizationAddUser;
use App\Http\Requests\V1\SuborganizationAddRole;
use App\Http\Requests\V1\SuborganizationUpdateUser;
use App\Http\Requests\V1\SuborganizationInviteMember;
use App\Http\Requests\V1\SuborganizationSearchRequest;
use App\Http\Requests\V1\SuborganizationUploadThumbRequest;
use App\Http\Requests\V1\SuborganizationShowMemberOptionsRequest;
use App\Http\Requests\V1\SuborganizationDeleteUserRequest;
use App\Http\Requests\V1\SuborganizationGetUsersRequest;
use App\Http\Requests\V1\SuborganizationUpdateRole;
use App\Http\Requests\V1\SuborganizationUserHasPermissionRequest;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Storage;
use App\Models\Organization;
use App\Models\OrganizationRoleType;
use App\Models\OrganizationUserRole;
use App\Models\OrganizationVisibilityType;
use App\Repositories\User\UserRepositoryInterface;

/**
 * @authenticated
 *
 * @group 19. Suborganization API
 *
 * APIs for Suborganization
 */
class SuborganizationController extends Controller
{
    private $organizationRepository;
    private $userRepository;

    /**
     * SuborganizationController constructor.
     *
     * @param OrganizationRepositoryInterface $organizationRepository
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(OrganizationRepositoryInterface $organizationRepository, UserRepositoryInterface $userRepository)
    {
        $this->organizationRepository = $organizationRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Get All Suborganization
     *
     * Get a list of the suborganizations for a user's default organization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam query string required Query to search suborganization against Example: Vivensity
     *
     * @responseFile responses/organization/suborganizations.json
     *
     * @param SuborganizationSearchRequest $suborganizationSearchRequest
     * @param Organization $suborganization
     * @return Response
     */
    public function index(SuborganizationSearchRequest $suborganizationSearchRequest, Organization $suborganization)
    {
        $this->authorize('viewAny', $suborganization);

        $data = $suborganizationSearchRequest->validated();

        return response([
            'suborganization' => OrganizationResource::collection($this->organizationRepository->fetchSuborganizations($data, $suborganization)),
        ], 200);
    }

    /**
     * Upload thumbnail
     *
     * Upload thumbnail image for a suborganization
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam thumb image required Thumbnail image to upload Example: (binary)
     *
     * @response {
     *   "thumbUrl": "/storage/organizations/1fqwe2f65ewf465qwe46weef5w5eqwq.png"
     * }
     *
     * @response 400 {
     *   "errors": [
     *     "Invalid image."
     *   ]
     * }
     *
     * @param SuborganizationUploadThumbRequest $suborganizationUploadThumbRequest
     * @param Organization $suborganization
     * @return Response
     */
    public function uploadThumb(SuborganizationUploadThumbRequest $suborganizationUploadThumbRequest, Organization $suborganization)
    {
        $this->authorize('uploadThumb', $suborganization);

        $data = $suborganizationUploadThumbRequest->validated();

        $path = $suborganizationUploadThumbRequest->file('thumb')->store('/public/organizations');

        return response([
            'thumbUrl' => Storage::url($path),
        ], 200);
    }

    /**
     * Create Suborganization
     *
     * Create a new suborganization for a user's default organization.
     *
     * @bodyParam name string required Name of a suborganization Example: Old Campus
     * @bodyParam description string required Description of a suborganization Example: This is a test suborganization.
     * @bodyParam domain string required Domain of a suborganization Example: oldcampus
     * @bodyParam image string required Image path of a suborganization Example: /storage/organizations/jlvKGDV1XjzIzfNrm1Py8gqgVkHpENwLoQj6OMjV.jpeg
     * @bodyParam admins array required Ids of the suborganization admin users Example: [1, 2]
     * @bodyParam users array required Array of the "user_id" and "role_id" for suborganization users Example: [[user_id => 5, 3], [user_id => 6, 2]]
     * @bodyParam parent_id int required Id of the parent organization Example: 1
     * @bodyParam self_registration bool Enable/disable user self registration Example: false
     *
     * @responseFile 201 responses/organization/suborganization.json
     *
     * @response 500 {
     *   "errors": [
     *     "Could not create suborganization. Please try again later."
     *   ]
     * }
     *
     * @param SuborganizationSave $request
     * @return Response
     */
    public function store(SuborganizationSave $request)
    {
        $data = $request->validated();

        $organization = $this->organizationRepository->find($data['parent_id']);
        $this->authorize('create', $organization);

        $authenticatedUser = auth()->user();

        $suborganization = $this->organizationRepository->createSuborganization($organization, $data, $authenticatedUser);

        if ($suborganization) {
            return response([
                'suborganization' => new OrganizationResource($suborganization),
            ], 201);
        }

        return response([
            'errors' => ['Could not create suborganization. Please try again later.'],
        ], 500);
    }

    /**
     * Get Suborganization
     *
     * Get the specified suborganization detail.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile responses/organization/suborganization.json
     *
     * @param Organization $suborganization
     * @return Response
     */
    public function show(Organization $suborganization)
    {
        $this->authorize('view', $suborganization);

        $authenticatedUser = auth()->user();

        return response([
            'suborganization' => new OrganizationResource($this->organizationRepository->fetchOrganizationData($authenticatedUser, $suborganization)),
        ], 200);
    }

    /**
     * Update Suborganization
     *
     * Update the specified suborganization for a user.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required Name of a suborganization Example: Old Campus
     * @bodyParam description string required Description of a suborganization Example: This is a test suborganization.
     * @bodyParam domain string required Domain of a suborganization Example: oldcampus
     * @bodyParam image string required Image path of a suborganization Example: /storage/organizations/jlvKGDV1XjzIzfNrm1Py8gqgVkHpENwLoQj6OMjV.jpeg
     * @bodyParam admins array required Ids of the suborganization admin users Example: [1, 2]
     * @bodyParam users array required Array of the "user_id" and "role_id" for suborganization users Example: [[user_id => 5, 3], [user_id => 6, 2]]
     * @bodyParam parent_id int required Id of the parent organization Example: 1
     * @bodyParam self_registration bool Enable/disable user self registration Example: false
     *
     * @responseFile responses/organization/suborganization.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update suborganization."
     *   ]
     * }
     *
     * @param SuborganizationUpdate $request
     * @param Organization $suborganization
     * @return Response
     */
    public function update(SuborganizationUpdate $request, Organization $suborganization)
    {
        $this->authorize('update', $suborganization);

        $data = $request->validated();

        $is_updated = $this->organizationRepository->update($suborganization, $data);

        if ($is_updated) {
            $updated_suborganization = new OrganizationResource($this->organizationRepository->find($suborganization->id));

            return response([
                'suborganization' => $updated_suborganization,
            ], 200);
        }

        return response([
            'errors' => ['Failed to update suborganization.'],
        ], 500);
    }

    /**
     * Remove Suborganization
     *
     * Remove the specified suborganization.
     *
     * @urlParam suborganization int required The Id of a suborganization Example: 1
     *
     * @response {
     *   "message": "Suborganization has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete suborganization."
     *   ]
     * }
     *
     * @param Organization $suborganization
     * @return Response
     */
    public function destroy(Organization $suborganization)
    {
        $this->authorize('delete', $suborganization);

        $is_deleted = $this->organizationRepository->deleteSuborganization($suborganization->id);

        if ($is_deleted) {
            return response([
                'message' => 'Suborganization has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete suborganization.'],
        ], 500);
    }

    /**
     * Show Member Options
     *
     * Display a listing of the user member options for suborganization, other then the exiting ones.
     *
     * @urlParam suborganization int required The Id of a suborganization Example: 1
     * @bodyParam query string required Query to search users against Example: leo
     *
     * @responseFile responses/organization/member-options.json
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "query": [
     *       "The query field is required."
     *     ]
     *   }
     * }
     *
     * @param SuborganizationShowMemberOptionsRequest $suborganizationShowMemberOptionsRequest
     * @param Organization $suborganization
     * @return Response
     */
    public function showMemberOptions(SuborganizationShowMemberOptionsRequest $suborganizationShowMemberOptionsRequest, Organization $suborganization)
    {
        $this->authorize('viewMemberOptions', $suborganization);

        $data = $suborganizationShowMemberOptionsRequest->validated();

        return response([
            'member-options' => UserResource::collection($this->organizationRepository->getMemberOptions($data, $suborganization))
        ], 200);
    }

    /**
     * Add Suborganization User
     *
     * Add user for the specified role in suborganization
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam user_id int required Id of the user to be added Example: 1
     * @bodyParam role_id int required Id of the role for added user Example: 1
     *
     * @response {
     *   "message": "User has been added successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to add user."
     *   ]
     * }
     *
     * @param SuborganizationAddUser $request
     * @param Organization $suborganization
     * @return Response
     */
    public function addUser(SuborganizationAddUser $request, Organization $suborganization)
    {
        $this->authorize('addUser', $suborganization);
        $data = $request->validated();
        $is_added = $this->organizationRepository->addUser($suborganization, $data);

        if ($is_added) {
            return response([
                'message' => 'User has been added successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to add user.'],
        ], 500);
    }

    /**
     * Invite Organization Member
     *
     * Invite a member to the organization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam email string required The email of the user Example: abby@curriki.org
     * @bodyParam role_id int required Id of the role for invited user Example: 1
     * @bodyParam note string The note for the user Example: Welcome
     *
     * @response {
     *   "message": "User have been invited to the organization successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to invite user to the organization."
     *   ]
     * }
     *
     * @param SuborganizationInviteMember $request
     * @param Organization $suborganization
     * @return Response
     */
    public function inviteMembers(SuborganizationInviteMember $request, Organization $suborganization)
    {
        $this->authorize('inviteMembers', $suborganization);

        $data = $request->validated();

        $authenticatedUser = auth()->user();

        $user = $this->userRepository->findByField('email', $data['email']);
        if ($user) {
            $organizationUser = OrganizationUserRole::where("organization_id", $suborganization->id)->where("user_id", $user->id)->first();
            if ($organizationUser) {
                return response([
                    'message' => 'The given data was invalid',
                    'errors' => ['email' => [0 => 'This user is already invited to ' . $suborganization->name]]
                ], 422);
            }
        }


        $invited = $this->organizationRepository->inviteMember($authenticatedUser, $suborganization, $data);

        if ($invited) {
            return response([
                'message' => 'User have been invited to the organization successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to invite user to the organization.'],
        ], 500);
    }

    /**
     * Update Suborganization User
     *
     * Update user for the specified role in default suborganization
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam user_id int required Id of the user to be updated Example: 1
     * @bodyParam role_id int required Id of the role for updated user Example: 1
     *
     * @response {
     *   "message": "User has been updated successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update user."
     *   ]
     * }
     *
     * @param SuborganizationUpdateUser $request
     * @param Organization $suborganization
     * @return Response
     */
    public function updateUser(SuborganizationUpdateUser $request, Organization $suborganization)
    {
        $this->authorize('updateUser', $suborganization);

        $data = $request->validated();

        $is_updated = $this->organizationRepository->updateUser($suborganization, $data);

        if ($is_updated) {
            return response([
                'message' => 'User has been updated successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to update user.'],
        ], 500);
    }

    /**
     * Remove Suborganization User
     *
     * Remove the specified user from default suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam user_id int required Id of the user to be deleted Example: 1
     * @bodyParam preserve_data bool Whether to assign user data to admin or delete it Example: false
     *
     * @response {
     *   "message": "User has been deleted successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete user."
     *   ]
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "user_id": [
     *       "The user id field is required."
     *     ]
     *   }
     * }
     *
     * @param SuborganizationDeleteUserRequest $suborganizationDeleteUserRequest
     * @param Organization $suborganization
     * @return Response
     */
    public function deleteUser(SuborganizationDeleteUserRequest $suborganizationDeleteUserRequest, Organization $suborganization)
    {
        $this->authorize('deleteUser', $suborganization);

        $data = $suborganizationDeleteUserRequest->validated();

        $userObj = $this->userRepository->find($data['user_id']);

        if ($suborganization->parent && $userObj->hasPermissionTo('organization:view', $suborganization->parent)) {
            return response([
                'errors' => ['Can not delete user inherited from a parent org.'],
            ], 500);
        }

        $is_deleted = $this->organizationRepository->deleteUser($suborganization, $data);

        if ($is_deleted) {
            return response([
                'message' => 'User has been deleted successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to delete user.'],
        ], 500);
    }

    /**
     * Remove Suborganization User
     *
     * Remove the specified user from a particular organization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam user_id int required Id of the user to be removed Example: 1
     * @bodyParam preserve_data bool Whether to assign user data to admin or delete it Example: false
     *
     * @response {
     *   "message": "User has been removed successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to remove user."
     *   ]
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "user_id": [
     *       "The user id field is required."
     *     ]
     *   }
     * }
     *
     * @param SuborganizationDeleteUserRequest $suborganizationDeleteUserRequest
     * @param Organization $suborganization
     * @return Response
     */
    public function removeUser(SuborganizationDeleteUserRequest $suborganizationDeleteUserRequest, Organization $suborganization)
    {
        $this->authorize('removeUser', $suborganization);

        $data = $suborganizationDeleteUserRequest->validated();

        $userObj = $this->userRepository->find($data['user_id']);

        if ($suborganization->parent && $userObj->hasPermissionTo('organization:view', $suborganization->parent)) {
            return response([
                'errors' => ['Can not remove user inherited from a parent org.'],
            ], 500);
        }

        $authenticatedUser = auth()->user();

        $isRemoved = $this->organizationRepository->removeUser($authenticatedUser, $suborganization, $data);

        if ($isRemoved) {
            return response([
                'message' => 'User has been removed successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to remove user.'],
        ], 500);
    }

    /**
     * Get All Users For a Suborganization
     *
     * Get a list of the users for a suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam page The pagination page no to show  Example: 1
     * @bodyParam query string Query to search suborganization users against Example: Leo
     * @bodyParam size int Number of items to be displayed "per page" Example: 1
     * @bodyParam role int Organization role type id to filter by Example: 1
     *
     * @responseFile responses/organization/organization-users.json
     *
     * @param SuborganizationGetUsersRequest $suborganizationGetUsersRequest
     * @param Organization $suborganization
     * @return Response
     */
    public function getUsers(SuborganizationGetUsersRequest $suborganizationGetUsersRequest, Organization $suborganization)
    {
        $this->authorize('viewAnyUser', $suborganization);

        $data = $suborganizationGetUsersRequest->validated();

        return UserResource::collection($this->organizationRepository->fetchOrganizationUsers($data, $suborganization));
    }

    /**
     * Get User Roles For Suborganization
     *
     * Get a list of the users roles for suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile responses/organization/organization-roles.json
     *
     * @param Organization $suborganization
     * @return Response
     */
    public function getRoles(Organization $suborganization)
    {
        return OrganizationRoleResource::collection($suborganization->roles()->get());
    }

    /**
     * Get User Role detail For Suborganization
     *
     * Get detail of the user role for suborganization.
     *
     * @param Organization $suborganization
     * @param integer $roleId
     * @responseFile responses/organization/organization-roles.json
     *
     * @return Response
     */
    public function getRoleDetail(Organization $suborganization, $roleId)
    {
        $role = $suborganization->roles->where("id", $roleId);
        if ($role->first()) {
            return OrganizationRoleResource::collection($role);
        }

        return response([
            'errors' => ['Role detail not found.'],
        ], 404);
    }

    /**
     * Get Visibility Types For Suborganization
     *
     * Get a list of the visibility types for suborganization.
     *
     * @responseFile responses/organization/organization-visibility-types.json
     *
     * @return Response
     */
    public function getVisibilityTypes()
    {
        return OrganizationVisibilityTypeResource::collection(OrganizationVisibilityType::all());
    }

    /**
     * Add Suborganization Role
     *
     * Add role for the specified suborganization
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam name string required Name of a suborganization role Example: member
     * @bodyParam display_name string required Display name of a suborganization role Example: Member
     * @bodyParam permissions array required Ids of the permissions to assign the role Example: [1, 2]
     *
     * @response {
     *   "message": "Role has been added successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to add role."
     *   ]
     * }
     *
     * @param SuborganizationAddRole $request
     * @param Organization $suborganization
     * @return Response
     */
    public function addRole(SuborganizationAddRole $request, Organization $suborganization)
    {
        $this->authorize('addRole', $suborganization);

        $data = $request->validated();

        $role = $this->organizationRepository->addRole($suborganization, $data);

        if ($role) {
            return response([
                'message' => 'Role has been added successfully.',
                'data' => $role,
            ], 200);
        }

        return response([
            'errors' => ['Failed to add role.'],
        ], 500);
    }

    /**
     * Update Suborganization Role
     *
     * Update role for the specified suborganization
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam role_id integer required Id of a suborganization role Example: 1
     * @bodyParam permissions array required Ids of the permissions to assign the role Example: [1, 2]
     *
     * @response {
     *   "message": "Role has been updated successfully."
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update role."
     *   ]
     * }
     *
     * @param SuborganizationUpdateRole $request
     * @param Organization $suborganization
     * @return Response
     */
    public function updateRole(SuborganizationUpdateRole $request, Organization $suborganization)
    {
        $this->authorize('updateRole', $suborganization);

        $data = $request->validated();

        $role = $suborganization->roles->where("id", $data['role_id']);
        if (!$role->first()) {
            return response([
                'errors' => ['Role detail not found.'],
            ], 404);
        }

        $role = $this->organizationRepository->updateRole($data);

        if ($role) {
            return response([
                'message' => 'Role has been updated successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to update role.'],
        ], 500);
    }

    /**
     * Get User Permissions
     *
     * Get the logged-in user's permissions in the suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile responses/organization/organization-user-permissions.json
     *
     * @param Organization $suborganization
     * @return Response
     */
    public function getUserPermissions(Organization $suborganization)
    {
        $authenticatedUser = auth()->user();

        return response([
            'permissions' => $this->organizationRepository->fetchOrganizationUserPermissions($authenticatedUser, $suborganization),
        ], 200);
    }

    /**
     * Get Default Permissions
     *
     * Get the all default permissions in the suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     *
     * @responseFile responses/organization/organization-user-permissions.json
     *
     * @param Organization $suborganization
     * @return Response
     */
    public function getDefaultPermissions(Organization $suborganization)
    {
        return response([
            'permissions' => $this->organizationRepository->fetchOrganizationDefaultPermissions(),
        ], 200);
    }

    /**
     * User has permission
     *
     * Check if user has the specified permission in the provided organization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @bodyParam permission string required Permission to check user access Example: organization:view
     *
     * @response {
     *   "userHasPermission": true
     * }
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "permission": [
     *       "The permission field is required."
     *     ]
     *   }
     * }
     *
     * @param SuborganizationUserHasPermissionRequest $suborganizationUserHasPermissionRequest
     * @param Organization $suborganization
     * @return Response
     */
    public function userHasPermission(SuborganizationUserHasPermissionRequest $suborganizationUserHasPermissionRequest, Organization $suborganization)
    {
        $this->authorize('viewAnyUser', $suborganization);

        $data = $suborganizationUserHasPermissionRequest->validated();

        return response([
            'userHasPermission' => auth()->user()->hasPermissionTo($data['permission'], $suborganization),
        ], 200);
    }
}
