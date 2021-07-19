<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrganizationUserRole extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'organization_user_roles';

    /**
     * Get the role for user in the organization.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\OrganizationRoleType', 'organization_role_type_id');
    }
}
