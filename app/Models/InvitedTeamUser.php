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
     * Cascade on create/update the user invite
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function(InvitedTeamUser $invitedTeamUser) {
            $invitedTeamUser->invited_email = strtolower($invitedTeamUser->invited_email);
        });

        self::updating(function (InvitedTeamUser $invitedTeamUser) {
            if($invitedTeamUser->invited_email){
                $invitedTeamUser->invited_email = strtolower($invitedTeamUser->invited_email);
            }
        });
    }

    /**
     * Scope for email search
     *
     * @param $query
     * @param $value
     * @return mixed
     */
    public function scopeSearchByEmail($query, $value)
    {
        return $query->orWhereRaw("invited_email = '" . strtolower($value) . "'");
    }

}
