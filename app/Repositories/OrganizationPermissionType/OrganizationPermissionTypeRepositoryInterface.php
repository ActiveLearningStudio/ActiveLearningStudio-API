<?php

namespace App\Repositories\OrganizationPermissionType;

use App\Models\OrganizationPermissionType;
use App\Repositories\EloquentRepositoryInterface;

interface OrganizationPermissionTypeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * To fetch organization permission types
     *
     * @param $data
     * @return OrganizationPermissionType $organizationPermissionTypes
     */
    public function fetchOrganizationPermissionTypes($data);

    /**
     * Get organization permission types by names
     *
     * @param $permissionNames
     * @return Model
     */
    public function getOrganizationPermissionTypesByNames($permissionNames);
}
