<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationRoleType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name'
    ];

    /**
     * The permissions that belong to the role.
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Models\OrganizationPermissionType', 'organization_role_permissions', 'organization_role_type_id', 'organization_permission_type_id')->withTimestamps();
    }

    /**
     * The UI permissions that belong to the role.
     */
    public function uiModulePermissions()
    {
        return $this->belongsToMany('App\Models\UiModulePermission', 'organization_role_ui_permissions', 'organization_role_type_id', 'ui_module_permission_id')->withTimestamps();
    }

    /**
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'organization_user_roles')->using('App\Models\OrganizationUserRole')->withPivot('organization_role_type_id')->withTimestamps();
    }
}
