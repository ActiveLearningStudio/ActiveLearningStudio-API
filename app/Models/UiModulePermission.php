<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UiModulePermission extends Model
{
    /**
     * The organization permission types that belong to the ui module permission.
     */
    public function organizationPermissionTypes()
    {
        return $this->belongsToMany('App\Models\OrganizationPermissionType', 'ui_organization_permission_mappings', 'ui_module_permission_id', 'organization_permission_type_id')->withTimestamps();
    }

    /**
     * The organization role types that belong to the ui module permission.
     */
    public function organizationRoleTypes()
    {
        return $this->belongsToMany('App\Models\OrganizationRoleType', 'organization_role_ui_permissions', 'ui_module_permission_id', 'organization_role_type_id')->withTimestamps();
    }
}
