<?php

namespace App\Repositories\UiOrganizationPermissionMapping;

use App\Repositories\EloquentRepositoryInterface;

interface UiOrganizationPermissionMappingRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get organization permission type ids for ui module permission ids
     *
     * @param array $uiModulePermissionIds
     * @return array $organizationPermissionTypeIds
     */
    public function getOrganizationPermissionTypeIds($uiModulePermissionIds);
}
