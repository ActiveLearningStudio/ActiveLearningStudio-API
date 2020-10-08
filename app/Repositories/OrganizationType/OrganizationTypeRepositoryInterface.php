<?php

namespace App\Repositories\OrganizationType;

use Illuminate\Http\Request;

use App\Models\OrganizationType;

interface OrganizationTypeRepositoryInterface
{
    /**
     * Get all organization types in order
     */
    public function all();

    /**
     * Create a new organization type
     * @param String $name
     * @param String $label
     */    
    public function create($name, $label);

    /**
     * Update an existing organization type
     * @param Illuminate\Http\Request
     * @param App\Models\OrganizationType
     */    
    public function update(Request $request, OrganizationType $orgType);

    /**
     * Delete an existing organization type
     * @param App\Models\OrganizationType
     */    
    public function delete(OrganizationType $orgType);
}
