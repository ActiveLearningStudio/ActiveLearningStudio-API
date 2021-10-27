<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\OrganizationSearchRequest;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Http\Resources\V1\OrganizationResource;
use App\Http\Requests\V1\OrganizationRequest;
use Illuminate\Http\Request;

/**
 * @group 18. Organization API
 *
 * APIs for Organization
 */
class OrganizationController extends Controller
{
    private $organizationRepository;

    /**
     * OrganizationController constructor.
     *
     * @param OrganizationRepositoryInterface $organizationRepository
     */
    public function __construct(OrganizationRepositoryInterface $organizationRepository)
    {
        $this->organizationRepository = $organizationRepository;
    }

    /**
     * Get By Domain
     *
     * Get organization by domain
     *
     * @bodyParam domain string required Organization domain to get data for Example: curriki
     *
     * @responseFile responses/organization/organization.json
     *
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "domain": [
     *       "The selected domain is invalid."
     *     ]
     *   }
     * }
     *
     * @param OrganizationRequest $organizationRequest
     * @return Response
     */
    public function getByDomain(OrganizationRequest $organizationRequest)
    {
        $data = $organizationRequest->validated();

        return response([
            'organization' => new OrganizationResource($this->organizationRepository->findByField('domain', $data['domain'])),
        ], 200);
    }

    /**
     * Search organization by name
     *
     * @bodyParam query string required Organization name to get data for Example: curriki
     * @param OrganizationSearchRequest $organizationSearchRequest
     * @return Response
     */
    public function searchOrganizationByName(OrganizationSearchRequest $organizationSearchRequest)
    {
        $data = $organizationSearchRequest->validated();
        $response = $this->organizationRepository->searchOrganizationByName($data);
        return response([
            'organization' => $response,
        ], 200);
    }
}
