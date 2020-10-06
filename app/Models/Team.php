<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the users for the team
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_team')->withPivot('role')->withTimestamps();
    }
}
