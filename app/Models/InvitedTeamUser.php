<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitedTeamUser extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invited_email',
        'team_id',
        'token',
    ];

    /**
     * Scope for email search
     *
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSearchByEmail($query, $value)
    {
        return $query->orWhereRaw("invited_email = '" . $value . "'");
    }

}
