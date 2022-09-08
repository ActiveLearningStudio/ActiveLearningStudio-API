<?php

namespace App\Repositories\UiOrganizationPermissionMapping;

use App\Models\UiOrganizationPermissionMapping;
use App\Repositories\UiOrganizationPermissionMapping\UiOrganizationPermissionMappingRepositoryInterface;
use App\Repositories\BaseRepository;

class UiOrganizationPermissionMappingRepository extends BaseRepository implements UiOrganizationPermissionMappingRepositoryInterface
{
    /**
     * Organization Repository constructor.
     *
     * @param UiOrganizationPermissionMapping $model
     */
    public function __construct(UiOrganizationPermissionMapping $model)
    {
        parent::__construct($model);
    }

    /**
     * Get organization permission type ids for ui module permission ids
     *
     * @param array $uiModulePermissionIds
     * @return array $organizationPermissionTypeIds
     */
    public function getOrganizationPermissionTypeIds($uiModulePermissionIds)
    {
        $organizationPermissionTypeIds = $this->model
        ->whereIn('ui_module_permission_id', $uiModulePermissionIds)
        ->pluck('organization_permission_type_id')
        ->toArray();

        return $organizationPermissionTypeIds;
    }
}
