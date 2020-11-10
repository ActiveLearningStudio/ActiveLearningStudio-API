<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\Organization\OrganizationRepository;
use App\Http\Resources\V1\Admin\OrganizationResource;
use App\Http\Requests\Admin\SaveOrganization;
use App\Http\Resources\V1\Admin\UserResource;

/**
 * @authenticated
 *
 * @group  Organization API
 *
 * APIs for Organization
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
     * Display a listing of the Organization.
     * 
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return OrganizationResource::collection($this->organizationRepository->getAll($request->all()));
    }

    /**
     * Store a newly created Organization in storage.
     *
     * @param SaveOrganization $request
     * @return OrganizationResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(SaveOrganization $request)
    {
        $validated = $request->validated();
        $response = $this->organizationRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new OrganizationResource($response['data'])], 200);
    }

    /**
     * Display the specified Organization.
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
     * Update the specified Organization in storage.
     *
     * @param SaveOrganization $request
     * @param $id
     * @return OrganizationResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(SaveOrganization $request, $id)
    {
        $validated = $request->validated();
        $response = $this->organizationRepository->update($id, $validated, $request->clone_project_id, $request->member_id);
        return response(['message' => $response['message'], 'data' => new OrganizationResource($response['data'])], 200);
    }

    /**
     * Remove the specified Organization from storage.
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
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function reportBasic(Request $request){
        return response( $this->organizationRepository->reportBasic($request->all()), 200);
    }

    /**
     * Display a listing of the parent organizations options, other then itself and its exiting children.
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
     * Display a listing of the user member options, other then the exiting ones.
     *
     * @param Request $request
     * @param $id
     * @return AnonymousResourceCollection
     */
    public function showMemberOptions(Request $request, $id)
    {
        return UserResource::collection($this->organizationRepository->getMemberOptions($request->all(), $id));
    }
}
