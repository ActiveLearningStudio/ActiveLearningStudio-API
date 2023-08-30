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

    /**
     * Get UI module permission ids for organization permission type ids
     *
     * @param array $organizationPermissionTypeIds
     * @return array $uiModulePermissionIds
     */
    public function getUiModulePermissionIds($organizationPermissionTypeIds);
}
