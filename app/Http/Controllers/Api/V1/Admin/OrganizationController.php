<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\Organization\OrganizationRepository;
use App\Http\Resources\V1\Admin\OrganizationResource;
use App\Http\Requests\Admin\OrganizationCreate;
use App\Http\Requests\Admin\OrganizationUpdate;
use App\Http\Requests\Admin\OrganizationRemoveUser;
use App\Http\Resources\V1\Admin\UserResource;
use App\Models\Organization;
use App\User;

/**
 * @authenticated
 *
 * @group  Admin Organization API
 *
 * Admin APIs for Organization
 */
class OrganizationController extends Controller
{
    private $organizationRepository;

    /**
     * OrganizationController constructor.
     *
     * @param OrganizationRepository $organizationRepository
     */
    public function __construct(OrganizationRepository $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }
    
    /**
     * Get Organizations
     *
     * Get a list of the Organizations.
     *
     * @responseFile responses/admin/organization/organizations.json
     * 
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return OrganizationResource::collection($this->organizationRepository->getAll($request->all()));
    }

    /**
     * Create Organization
     *
     * Create a new organization.
     *
     * @bodyParam name string required Name of a organization Example: tfa
     * @bodyParam description string required Description of a organization Example: This is a test organization.
     * @bodyParam domain string required Domain of a organization Example: tfa
     * @bodyParam image image required Image to upload Example: (binary)
     * @bodyParam admin_id int required Id of the organization admin user Example: 1
     * @bodyParam parent_id int Id of the parent organization Example: 1
     *
     * @responseFile responses/admin/organization/organization.json
     *
     * @param OrganizationCreate $request
     * @return OrganizationResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(OrganizationCreate $request)
    {
        $validated = $request->validated();
        $response = $this->organizationRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new OrganizationResource($response['data'])], 200);
    }

    /**
     * Get Organization
     *
     * Get the specified organization detail.
     *
     * @urlParam id required The Id of the organization Example: 1
     *
     * @responseFile responses/admin/organization/organization-detail.json
     *
     * @param $id
     * @return OrganizationResource
     * @throws GeneralException
     */
    public function show($id)
    {
        $organization = $this->organizationRepository->find($id);
        return new OrganizationResource($organization);
    }

    /**
     * Update Organization
     *
     * Update the specified organization.
     *
     * @urlParam id required The Id of a organization Example: 1
     * @bodyParam name string required Name of a organization Example: tfa
     * @bodyParam description string required Description of a organization Example: This is a test organization.
     * @bodyParam domain string required Domain of a organization Example: tfa
     * @bodyParam image image Image to upload Example: (binary)
     * @bodyParam member_id int Id of the user to add as member in organization Example: 1
     * @bodyParam parent_id int Id of the parent organization Example: 1
     *
     * @responseFile responses/admin/organization/organization.json
     *
     * @param OrganizationUpdate $request
     * @param $id
     * @return OrganizationResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(OrganizationUpdate $request, $id)
    {
        $validated = $request->validated();
        $response = $this->organizationRepository->update($id, $validated, $request->clone_project_id, $request->member_id);
        return response(['message' => $response['message'], 'data' => new OrganizationResource($response['data'])], 200);
    }

    /**
     * Remove Organization
     *
     * Remove the specified organization.
     *
     * @urlParam id int required The Id of a organization Example: 1
     *
     * @response {
     *   "message": "Organization Deleted!"
     * }
     *
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->organizationRepository->destroy($id)], 200);
    }

    /**
     * Organization Basic Report
     *
     * Returns the paginated response of the Organization with basic reporting (DataTables are fully supported - All Params).
     *
     * @queryParam start Offset for getting the paginated response, Default 0. Example: 0
     * @queryParam length Limit for getting the paginated records, Default 25. Example: 25
     *
     * @responseFile responses/admin/organization/organizations_report.json
     *
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function reportBasic(Request $request)
    {
        return response( $this->organizationRepository->reportBasic($request->all()), 200);
    }

    /**
     * Display parent organizations options
     *
     * Display a listing of the parent organizations options, other then itself and its exiting children.
     *
     * @urlParam id int required The Id of a organization Example: 1
     *
     * @responseFile responses/admin/organization/organization-parent-options.json
     *
     * @param Request $request
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function showParentOptions(Request $request, $id)
    {
        return OrganizationResource::collection($this->organizationRepository->getParentOptions($request->all(), $id));
    }


    /**
     * Display member options
     *
     * Display a listing of the user member options, other then the exiting ones.
     *
     * @urlParam id int required The Id of a organization Example: 1
     *
     * @responseFile responses/admin/organization/organization-member-options.json
     *
     * @param Request $request
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function showMemberOptions(Request $request, $id)
    {
        return UserResource::collection($this->organizationRepository->getMemberOptions($request->all(), $id));
    }

    /**
     * Remove Organization User
     *
     * Remove the user from the specified organization.
     *
     * @urlParam organization int required Id of the organization to deleted user from. Example: 1
     * @urlParam user int required Id of the user to be deleted. Example: 1
     *
     * @response {
     *   "message": "Organization User Deleted!",
     * }
     *
     * @response 500 {
     *   "errors": [
     *     "Failed to delete user."
     *   ]
     * }
     *
     * @param OrganizationRemoveUser $request
     * @param int $organization
     * @param int $user
     * @return Response
     */
    public function deleteUser(OrganizationRemoveUser $request, $organization, $user)
    {
        $validated = $request->validated();

        return response(['message' => $this->organizationRepository->deleteUser($organization, $user)], 200);
    }
}
