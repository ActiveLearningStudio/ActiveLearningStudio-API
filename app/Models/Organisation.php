<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organisation extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * The users that belong to the organisation.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'organisation_user_roles')->withPivot('organisation_role_type_id')->withTimestamps();
    }

    /**
     * Get the projects for the organisation.
     */
    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }

    /**
     * Get the children for the organisation.
     */
    public function children()
    {
        return $this->hasMany('App\Models\Organisation', 'parent_id');
    }

    /**
     * Get the parent that owns the organisation.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Organisation');
    }
}
