<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;
use App\Http\Resources\V1\OrganizationTypeResource;
use App\Http\Requests\Admin\StoreOrganizationType;
use App\Models\OrganizationType;

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

    public function index(Request $req)
    {
        $collection = $this->organizationTypeRepository->all();
        return OrganizationTypeResource::collection($collection);
    }

    public function show(OrganizationType $organization_type)
    {
        return response(new OrganizationTypeResource($organization_type), 200);
    }

    public function store(StoreOrganizationType $request)
    {
        $validated = $request->validated();
        $newOrgType = $this->organizationTypeRepository->create($validated);
        return response(new OrganizationTypeResource($newOrgType), 201);
    }

    public function update(StoreOrganizationType $request, OrganizationType $organization_type)
    {
        $validated = $request->validated();
        $orgType = $this->organizationTypeRepository->update($validated, $organization_type->id);
        return response(new OrganizationTypeResource($orgType), 201);
    }

    public function destroy(OrganizationType $organization_type)
    {
        $result = $this->organizationTypeRepository->delete($organization_type->id);
        if($result)
            return response(['message'=>'Organization type deleted successfully.'], 200);

        return response(['message'=>'Failed to delete organization type.'], 500);
    }
}
