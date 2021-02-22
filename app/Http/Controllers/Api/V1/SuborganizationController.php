<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Http\Resources\V1\OrganizationResource;
use App\Http\Requests\V1\SuborganizationSave;
use App\Http\Requests\V1\SuborganizationAddUser;
use App\Http\Requests\V1\SuborganizationUpdateUser;
use App\Http\Requests\V1\SuborganizationInviteMember;
use App\Http\Resources\V1\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Organization;
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

        $this->authorizeResource(Organization::class, 'suborganization');
    }
    
    /**
     * Get All Suborganization
     *
     * Get a list of the suborganizations for a user's default organization.
     *
     * @responseFile responses/organization/suborganizations.json
     *
     * @return Response
     */
    public function index()
    {
        $authenticatedUser = auth()->user();

        return response([
            'suborganization' => OrganizationResource::collection($this->organizationRepository->fetchSuborganizations($authenticatedUser->default_organization)),
        ], 200);
    }

    /**
     * Upload thumbnail
     *
     * Upload thumbnail image for a suborganization
     *
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
     * @return Response
     */
    public function uploadThumb(Request $request)
    {
        $this->authorize('uploadThumb', Organization::class);

        $validator = Validator::make($request->all(), [
            'thumb' => 'required|image|max:102400',
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
     * @bodyParam admin_id int required Id of the suborganization admin user Example: 1
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
        $authenticatedUser = auth()->user();
        $data['parent_id'] = $authenticatedUser->default_organization;
        $suborganization = $this->organizationRepository->createSuborganization($data);

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
        return response([
            'suborganization' => new OrganizationResource($suborganization->load('parent')->loadCount(['projects', 'children', 'users'])),
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
     * @bodyParam admin_id int required Id of the suborganization admin user Example: 1
     *
     * @responseFile responses/organization/suborganization.json
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to update suborganization."
     *   ]
     * }
     *
     * @param SuborganizationSave $request
     * @param Organization $suborganization
     * @return Response
     */
    public function update(SuborganizationSave $request, Organization $suborganization)
    {
        $data = $request->validated();

        $is_updated = $this->organizationRepository->update($suborganization->id, $data);

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
     * Display a listing of the user member options for default suborganization, other then the exiting ones.
     *
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
     * @return Response
     */
    public function showMemberOptions(Request $request)
    {
        $this->authorize('viewMemberOptions', Organization::class);

        $validator = Validator::make($request->all(), [
            'query' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $authenticatedUser = auth()->user();
        $id = $authenticatedUser->default_organization;

        return response([
            'member-options' => UserResource::collection($this->organizationRepository->getMemberOptions($request->all(), $id))
        ], 200);
    }

    /**
     * Add Suborganization User
     *
     * Add user for the specified role in default suborganization
     *
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
     * @return Response
     */
    public function addUser(SuborganizationAddUser $request)
    {
        $this->authorize('addUser', Organization::class);

        $data = $request->validated();

        $authenticatedUser = auth()->user();
        $id = $authenticatedUser->default_organization;

        $is_added = $this->organizationRepository->addUser($id, $data);

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
     * Invite a organization member to the team.
     *
     * @bodyParam email string required The email of the user Example: abby@curriki.org
     *
     * @response {
     *   "message": "Users have been invited to the organization successfully."
     * }
     *
     * @response 403 {
     *   "errors": [
     *     "You do not have permission to invite users to the organization."
     *   ]
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to invite users to the organization."
     *   ]
     * }
     *
     * @param SuborganizationInviteMember $request
     * @return Response
     */
    public function inviteMembers(SuborganizationInviteMember $request)
    {
        $this->authorize('inviteMembers', Organization::class);

        $data = $request->validated();

        $authenticatedUser = auth()->user();
        $defaultOrganization = $authenticatedUser->defaultOrganization;

        $admin = $this->organizationRepository->getAdmin($defaultOrganization);

        if ($admin->id === $authenticatedUser->id) {
            $invited = $this->organizationRepository->inviteMember($authenticatedUser, $defaultOrganization, $data);

            if ($invited) {
                return response([
                    'message' => 'Users have been invited to the organization successfully.',
                ], 200);
            }

            return response([
                'errors' => ['Failed to invite users to the organization.'],
            ], 500);
        }

        return response([
            'message' => 'You do not have permission to invite users to the organization.',
        ], 403);
    }

    /**
     * Update Suborganization User
     *
     * Update user for the specified role in default suborganization
     *
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
     * @return Response
     */
    public function updateUser(SuborganizationUpdateUser $request)
    {
        $this->authorize('updateUser', Organization::class);

        $data = $request->validated();

        $authenticatedUser = auth()->user();
        $id = $authenticatedUser->default_organization;

        $is_updated = $this->organizationRepository->updateUser($id, $data);

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
     * @return Response
     */
    public function deleteUser(Request $request)
    {
        $this->authorize('deleteUser', Organization::class);

        $authenticatedUser = auth()->user();
        $default_organization = $authenticatedUser->default_organization;

        $validator = Validator::make($request->all(), [
            'user_id' => [
                'required',
                'integer',
                'exists:App\User,id',
                Rule::exists('organization_user_roles')->where(function ($query) use ($default_organization) {
                    return $query->where('organization_id', $default_organization);
                })
            ]
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $is_deleted = $this->organizationRepository->deleteUser($default_organization, $request->all());

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
     * Get All Users For Default Suborganization
     *
     * Get a list of the users for a user's default organization.
     *
     * @responseFile responses/organization/organization-users.json
     *
     * @return Response
     */
    public function getUsers()
    {
        $this->authorize('viewAnyUser', Organization::class);

        $authenticatedUser = auth()->user();

        return response([
            'organization-users' => UserResource::collection($this->organizationRepository->fetchOrganizationUsers($authenticatedUser->default_organization))
        ], 200);
    }
}
