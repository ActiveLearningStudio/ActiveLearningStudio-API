<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrganizationPermissionType\OrganizationPermissionTypeRepositoryInterface;
use App\Http\Resources\V1\OrganizationPermissionTypeResource;
use Illuminate\Support\Facades\Validator;

/**
 * @authenticated
 *
 * @group  Organization Permission Type API
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
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            return response([
                'errors' => $validator->errors()->all()
            ], 400);
        }

        return OrganizationPermissionTypeResource::collection($this->organizationPermissionTypeRepository->fetchOrganizationPermissionTypes($request->all()));
    }
}
