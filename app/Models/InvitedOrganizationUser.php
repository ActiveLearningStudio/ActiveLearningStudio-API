<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvitedOrganizationUser extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'invited_email',
        'organization_id',
        'organization_role_type_id',
        'token',
    ];

    /**
     * Cascade on create/update the user invite
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function(InvitedOrganizationUser $invitedOrganizationUser) {
            $invitedOrganizationUser->invited_email = strtolower($invitedOrganizationUser->invited_email);
        });

        self::updating(function (InvitedOrganizationUser $invitedOrganizationUser) {
            if($invitedOrganizationUser->invited_email){
                $invitedOrganizationUser->invited_email = strtolower($invitedOrganizationUser->invited_email);
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
