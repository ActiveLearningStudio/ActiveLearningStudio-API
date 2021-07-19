<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SsoLogin extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'provider',
        'token',
        'token_expired',
        'uniqueid',
        'tool_consumer_instance_name',
        'tool_consumer_instance_guid',
        'custom_school',
    ];

    /**
     * Get the user of the login information
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
