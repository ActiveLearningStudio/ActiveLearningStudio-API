<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Membership types are used by the SaaS system to control
// the quotas and limits assigned to each user

class MembershipType extends Model
{
    protected $fillable = [
        'name',
        'label',
        'description',
        'total_storage',	// bytes
        'total_bandwidth',	// bytes
        'price'				// cents
    ];

    public function users()
    {
        return $this->hasMany('App\Models\User', 'user_id');
    }
}