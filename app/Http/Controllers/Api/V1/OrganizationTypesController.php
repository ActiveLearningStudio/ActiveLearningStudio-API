<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;
use App\Http\Resources\V1\OrganizationTypeResource;

class OrganizationTypesController extends Controller
{
    private $organizationTypeRepository;

    public function __construct(OrganizationTypeRepositoryInterface $organizationTypeRepository)
    {
        $this->organizationTypeRepository = $organizationTypeRepository;
    }

    public function index()
    {
		$collection = $this->organizationTypeRepository->all();
        return OrganizationTypeResource::collection($this->organizationTypeRepository->all());
    }
}
