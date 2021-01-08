<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Organization\OrganizationRepositoryInterface;
use App\Http\Resources\V1\OrganizationResource;
use Illuminate\Support\Facades\Validator;

/**
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
     * @response 400 {
     *   "errors": [
     *     "Invalid domain."
     *   ]
     * }
     *
     * @param Request $request
     * @return Response
     */
    public function getByDomain(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'domain' => 'required|string|max:255|exists:App\Models\Organization,domain',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => ['Invalid domain.']
            ], 400);
        }

        return response([
            'organization' => new OrganizationResource($this->organizationRepository->findByField('domain', $request->domain)),
        ], 200);
    }
}
