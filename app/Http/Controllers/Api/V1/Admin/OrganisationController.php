<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Admin\Organisation\OrganisationRepository;
use App\Http\Resources\V1\Admin\OrganisationResource;
use App\Http\Requests\Admin\SaveOrganisation;

/**
 * @authenticated
 *
 * @group  Organisation API
 *
 * APIs for Organisation
 */
class OrganisationController extends Controller
{
    private $organisationRepository;

    /**
     * OrganisationController constructor.
     *
     * @param OrganisationRepository $organisationRepository
     */
    public function __construct(OrganisationRepository $organisationRepository)
    {
        $this->organisationRepository = $organisationRepository;
    }
    
    /**
     * Display a listing of the Organisations.
     * 
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request)
    {
        return OrganisationResource::collection($this->organisationRepository->getAll($request->all()));
    }

    /**
     * Store a newly created Organisation in storage.
     *
     * @param SaveOrganisation $request
     * @return OrganisationResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function store(SaveOrganisation $request)
    {
        $validated = $request->validated();
        $response = $this->organisationRepository->create($validated);
        return response(['message' => $response['message'], 'data' => new OrganisationResource($response['data'])], 200);
    }

    /**
     * Display the specified Organisation.
     *
     * @param $id
     * @return OrganisationResource
     * @throws GeneralException
     */
    public function show($id)
    {
        $organisation = $this->organisationRepository->find($id);
        return new OrganisationResource($organisation);
    }

    /**
     * Update the specified Organisation in storage.
     *
     * @param SaveOrganisation $request
     * @param $id
     * @return OrganisationResource|Application|ResponseFactory|Response
     * @throws GeneralException
     */
    public function update(SaveOrganisation $request, $id)
    {
        $validated = $request->validated();
        $response = $this->organisationRepository->update($id, $validated);
        return response(['message' => $response['message'], 'data' => new OrganisationResource($response['data'])], 200);
    }

    /**
     * Remove the specified Organisation from storage.
     *
     * @param $id
     * @return Application|Factory|View
     * @throws GeneralException
     */
    public function destroy($id)
    {
        return response(['message' => $this->organisationRepository->destroy($id)], 200);
    }

    /**
     * @param Request $request
     * @return Application|ResponseFactory|Response
     */
    public function reportBasic(Request $request){
        return response( $this->organisationRepository->reportBasic($request->all()), 200);
    }
}
