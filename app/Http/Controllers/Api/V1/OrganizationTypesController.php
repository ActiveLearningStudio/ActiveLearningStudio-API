<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Repositories\OrganizationType\OrganizationTypeRepositoryInterface;

class OrganizationTypesController extends Controller
{

    private $organizationTypeRepository;

    public function __construct(OrganizationTypeRepositoryInterface $organizationTypeRepository)
    {
        $this->organizationTypeRepository = $organizationTypeRepository;
    }

    public function index()
    {
        return $this->organizationTypeRepository->all();
    }
}
