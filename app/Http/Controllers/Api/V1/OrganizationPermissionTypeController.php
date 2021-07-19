<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\OrganizationPermissionType\OrganizationPermissionTypeRepositoryInterface;
use App\Http\Resources\V1\OrganizationPermissionTypeResource;
use App\Http\Requests\V1\OrganizationPermissionTypeRequest;

/**
 * @authenticated
 *
 * @group 17. Organization Permission Type API
 *
 * APIs for Organization Permission Types
 */
class OrganizationPermissionTypeController extends Controller
{
    private $organizationPermissionTypeRepository;

    /**
     * OrganizationPermissionTypeController constructor.
     *
     * @param OrganizationPermissionTypeRepositoryInterface $organizationPermissionTypeRepository
     */
    public function __construct(OrganizationPermissionTypeRepositoryInterface $organizationPermissionTypeRepository)
    {
        $this->organizationPermissionTypeRepository = $organizationPermissionTypeRepository;
    }

    /**
     * Get All Organization Permission Type
     *
     * Get a list of the organization permission type.
     *
     * @bodyParam query string required Query to search organization permission types against Example: edit
     *
     * @responseFile responses/permission-type/permissions.json
     *
     * @param OrganizationPermissionTypeRequest $organizationPermissionTypeRequest
     * @return Response
     */
    public function index(OrganizationPermissionTypeRequest $organizationPermissionTypeRequest)
    {
        $data = $organizationPermissionTypeRequest->validated();

        return OrganizationPermissionTypeResource::collection($this->organizationPermissionTypeRepository->fetchOrganizationPermissionTypes($data));
    }
}
