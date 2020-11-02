<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;
use App\Http\Resources\V1\OrganizationTypeResource;
use App\Http\Requests\Admin\StoreOrganizationType;
use App\Models\OrganizationType;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group 1006. Admin/Organization Types
 *
 * APIs for organization types on admin panel.
 */
class OrganizationTypesController extends Controller
{
    private $organizationTypeRepository;

    public function __construct(OrganizationTypeRepositoryInterface $organizationTypeRepository)
    {
        $this->organizationTypeRepository = $organizationTypeRepository;
    }

    /**
     * Get All Organization Types
     *
     * Returns the all organization types.
     *
     * @responseFile responses/admin/organization-type/organization-types.json
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $collection = $this->organizationTypeRepository->all();
        return OrganizationTypeResource::collection($collection);
    }

    /**
     * Get Organization Type
     *
     * Get the specified Organization Type data.
     *
     * @urlParam organization_type required The Id of a organization type Example: 1
     *
     * @responseFile responses/admin/organization-type/organization-type.json
     *
     * @param OrganizationType $organization_type
     * @return Application|ResponseFactory|Response
     */
    public function show(OrganizationType $organization_type)
    {
        return response(new OrganizationTypeResource($organization_type), 200);
    }

    /**
     * Create Organization Type
     *
     * Creates the new organization type in database.
     *
     * @bodyParam name string required Unique organization type name. Example: randomzv2tga01uxb6q8ojri5ob6
     * @bodyParam label string required Unique label for organization type. Example: test
     * @bodyParam order integer required Order Sequence value. Example: 1
     *
     * @responseFile 201 responses/admin/organization-type/organization-type.json
     *
     * @param StoreOrganizationType $request
     * @return Application|ResponseFactory|Response
     */
    public function store(StoreOrganizationType $request)
    {
        $validated = $request->validated();
        $newOrgType = $this->organizationTypeRepository->create($validated);
        return response(new OrganizationTypeResource($newOrgType), 201);
    }

    /**
     * Update Organization Type
     *
     * Updates the organization type data in database.
     *
     * @urlParam organization_type required The Id of a organization type. Example: 1
     *
     * @bodyParam name string required Updated organization type name. Example: randomzv2tga01uxb6q8ojri5ob6
     * @bodyParam label string required Updated label for organization type. Example: test
     *
     * @responseFile 201 responses/admin/organization-type/organization-type.json
     *
     * @param StoreOrganizationType $request
     * @param OrganizationType $organization_type
     * @return Application|ResponseFactory|Response
     */
    public function update(StoreOrganizationType $request, OrganizationType $organization_type)
    {
        $validated = $request->validated();
        $orgType = $this->organizationTypeRepository->update($validated, $organization_type->id);
        return response(new OrganizationTypeResource($orgType), 201);
    }

    /**
     * Delete Organization Type
     *
     * Deletes the organization type from database.
     *
     * @urlParam organization_type required The Id of a organization type. Example: 1
     *
     * @response 200 {
     *   "message": "Organization type deleted successfully!"
     * }
     *
     * @response 500 {
     *   "message": "Failed to delete organization type."
     * }
     *
     * @param OrganizationType $organization_type
     * @return Application|ResponseFactory|Response
     */
    public function destroy(OrganizationType $organization_type)
    {
        $result = $this->organizationTypeRepository->delete($organization_type->id);
        if ($result) {
            return response(['message' => 'Organization type deleted successfully!'], 200);
        }
        return response(['message' => 'Failed to delete organization type.'], 500);
    }
}
