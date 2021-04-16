<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Organization;
use App\Models\OrganizationRoleType;
use App\Models\OrganizationVisibilityType;
use Illuminate\Validation\Rule;

/**
 * @authenticated
 *
 * @group  Suborganization API
 *
 * APIs for Suborganization
 */
class SuborganizationController extends Controller
{
    private $organizationRepository;

    /**
     * SuborganizationController constructor.
     *
     * @param OrganizationRepositoryInterface $organizationRepository
     */
    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
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
     * @param Request $request
     * @param Organization $suborganization
     * @return Response
     */
    public function index(Request $request, Organization $suborganization)
    {
        $this->authorize('viewAny', $suborganization);

        $validator = Validator::make($request->all(), [
            'query' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        return response([
            'suborganization' => OrganizationResource::collection($this->organizationRepository->fetchSuborganizations($request->all(), $suborganization)),
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
     * @param Request $request
     * @param Organization $suborganization
     * @return Response
     */
    public function uploadThumb(Request $request, Organization $suborganization)
    {
        $this->authorize('uploadThumb', $suborganization);

        $validator = Validator::make($request->all(), [
            'thumb' => 'required|mimes:jpg,jpeg,png,svg|max:102400',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid image.']
            ], 400);
        }

        $path = $request->file('thumb')->store('/public/organizations');

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

        $organization = Organization::find($data['parent_id']);
        $this->authorize('create', $organization);

        $suborganization = $this->organizationRepository->createSuborganization($organization, $data);

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
        $userOrganization = $authenticatedUser->organizations()->find($suborganization->id);

        if (!$userOrganization) {
            $userOrganization = $suborganization;
        }

        return response([
            'suborganization' => new OrganizationResource($userOrganization->load('parent')->loadCount(['projects', 'children', 'users', 'groups', 'teams'])),
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
     * @response 400 {
     *   "errors": [
     *     "The query field is required."
     *   ]
     * }
     *
     * @param Request $request
     * @param Organization $suborganization
     * @return Response
     */
    public function showMemberOptions(Request $request, Organization $suborganization)
    {
        $this->authorize('viewMemberOptions', $suborganization);

        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
            'page' => 'required|in:create,update',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        return response([
            'member-options' => UserResource::collection($this->organizationRepository->getMemberOptions($request->all(), $suborganization))
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
     * @response 400 {
     *   "errors": [
     *     "The user_id field is required."
     *   ]
     * }
     *
     * @param Request $request
     * @param Organization $suborganization
     * @return Response
     */
    public function deleteUser(Request $request, Organization $suborganization)
    {
        $this->authorize('deleteUser', $suborganization);

        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                'integer',
                'exists:App\User,id',
                Rule::exists('organization_user_roles')->where(function ($query) use ($suborganization) {
                    return $query->where('organization_id', $suborganization->id);
                })
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $is_deleted = $this->organizationRepository->deleteUser($suborganization, $request->all());

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
     * Get All Users For a Suborganization
     *
     * Get a list of the users for a suborganization.
     *
     * @urlParam suborganization required The Id of a suborganization Example: 1
     * @urlParam page The pagination page no to show  Example: 1
     * @bodyParam query string required Query to search suborganization users against Example: Leo
     *
     * @responseFile responses/organization/organization-users.json
     *
     * @param Request $request
     * @param Organization $suborganization
     * @return Response
     */
    public function getUsers(Request $request, Organization $suborganization)
    {
        $this->authorize('viewAnyUser', $suborganization);

        $validator = Validator::make($request->all(), [
            'query' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        return UserResource::collection($this->organizationRepository->fetchOrganizationUsers($request->all(), $suborganization));
    }

    /**
     * Get User Roles For Suborganization
     *
     * Get a list of the users roles for suborganization.
     *
     * @responseFile responses/organization/organization-roles.json
     *
     * @return Response
     */
    public function getRoles()
    {
        return OrganizationRoleResource::collection(OrganizationRoleType::all());
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

        $is_added = $this->organizationRepository->addRole($suborganization, $data);

        if ($is_added) {
            return response([
                'message' => 'Role has been added successfully.',
            ], 200);
        }

        return response([
            'errors' => ['Failed to add role.'],
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
     * @response 400 {
     *   "errors": [
     *     "The permission field is required."
     *   ]
     * }
     *
     * @param Request $request
     * @param Organization $suborganization
     * @return Response
     */
    public function userHasPermission(Request $request, Organization $suborganization)
    {
        $this->authorize('viewAnyUser', $suborganization);

        $validator = Validator::make($request->all(), [
            'permission' => 'required|string|max:255|exists:organization_permission_types,name',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        return response([
            'userHasPermission' => auth()->user()->hasPermissionTo($request->permission, $suborganization),
        ], 200);
    }
}
