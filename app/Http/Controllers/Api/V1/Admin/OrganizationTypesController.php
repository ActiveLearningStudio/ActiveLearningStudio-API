<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;
use App\Models\OrganizationType;

class OrganizationTypesController extends Controller
{

    private $organizationTypeRepository;

    public function __construct(OrganizationTypeRepositoryInterface $organizationTypeRepository)
    {
        $this->organizationTypeRepository = $organizationTypeRepository;
    }

    public function index(Request $req)
    {
        return $this->organizationTypeRepository->all();
    }

    public function show(OrganizationType $organization_type)
    {
        return response($organization_type, 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:organization_types',
            'label' => 'required|string|max:255'
        ]);

        $newOrgType = $this->organizationTypeRepository->create($request->input('name'), $request->input('label'));
        return response($newOrgType, 201);

    }

    public function update(Request $request, OrganizationType $organization_type)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:organization_types,name,'.$organization_type->id,
            'label' => 'required|string|max:255|unique:organization_types,name,'.$organization_type->id,
            'order' => 'integer'
        ]);

        $orgType = $this->organizationTypeRepository->update($request, $organization_type);
        return response($orgType, 201);
    }

    public function destroy(OrganizationType $organization_type)
    {
        $result = $this->organizationTypeRepository->delete($organization_type);
        if($result)
            return response(['message'=>'Organization type deleted successfully.'], 200);

        return response(['message'=>'Failed to delete organization type.'], 500);
    }

}
