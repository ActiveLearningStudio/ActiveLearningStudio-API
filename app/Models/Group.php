<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'deleted_at',
    ];

        /**
     * Get the users for the group
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_group')->withPivot('role')->withTimestamps();
    }

    /**
     * Get the projects for the team
     */
    public function projects()
    {
        return $this->belongsToMany('App\Models\Project', 'group_project')->withTimestamps();
    }

    /**
     * Get the group's owner.
     *
     * @return object
     */
    public function getUserAttribute()
    {
        if (isset($this->users)) {
            return $this->users()->wherePivot('role', 'owner')->first();
        }

        return null;
    }

}
