<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamUserRole extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'team_user_roles';

    /**
     * Get the role for user in the team.
     */
    public function role()
    {
        return $this->belongsTo('App\Models\TeamRoleType', 'team_role_type_id');
    }
}
