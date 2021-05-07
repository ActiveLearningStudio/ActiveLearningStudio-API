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
     * The users that belong to the role.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'organization_user_roles')->using('App\Models\OrganizationUserRole')->withPivot('organization_role_type_id')->withTimestamps();
    }
}
