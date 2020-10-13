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
     * @param array $attributes
     * @return Collection
     */    
    public function create($attributes);

    /**
     * Update an existing organization type
     * @param array $attributes
     * @param int $id
     * @return App\Models\OrganizationType
     */    
    public function update($attributes, $id);

    /**
     * Delete an existing organization type
     * @param int $id
     * @return int
     */    
    public function delete($id);
}
