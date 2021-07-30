<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\GlobalScope;
use App\Models\DeepRelations\HasManyDeep;
use App\Models\DeepRelations\HasRelationships;

class Organization extends Model
{
    use SoftDeletes, GlobalScope, HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'domain',
        'image',
        'parent_id',
        'self_registration',
        'account_id',
        'api_key',
        'unit_path'
    ];

    /**
     * The users that belong to the organization.
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'organization_user_roles')->using('App\Models\OrganizationUserRole')->withPivot('organization_role_type_id')->withTimestamps();
    }

    /**
     * The admin users that belong to the organization.
     */
    public function admins()
    {
        return $this->users()->wherePivot('organization_role_type_id', 1);
    }

    /**
     * Get the projects for the organization.
     */
    public function projects()
    {
        return $this->hasMany('App\Models\Project');
    }

    /**
     * Get the teams for the organization.
     */
    public function teams()
    {
        return $this->hasMany('App\Models\Team');
    }

    /**
     * Get the groups for the organization.
     */
    public function groups()
    {
        return $this->hasMany('App\Models\Group');
    }

    /**
     * Get playlists directly from organizations model via hasManyThrough
     * @return HasManyThrough
     */
    public function playlists()
    {
        return $this->hasManyThrough('App\Models\Playlist', 'App\Models\Project', 'organization_id', 'project_id', 'id', 'id');
    }

    /**
     * Get far away relations data using custom Deep classes
     * @return HasManyDeep
     */
    public function activities()
    {
        return $this->hasManyDeep(
            'App\Models\Activity',
            // Intermediate models, beginning at the far parent (Organizations).
            ['App\Models\Project', 'App\Models\Playlist'],
            [
                // Foreign key on the "project" table.
                'organization_id',
                // Foreign key on the "playlist" table.
                'project_id',
                // Foreign key on the "activity" table.
                'playlist_id'
            ],
            [
                // Local key on the "organizations" table.
                'id',
                // Local key on the "project" table.
                'id',
                // Local key on the "playlist" table.
                'id'
            ]
        );
    }

    /**
     * Get the children for the organization.
     */
    public function children()
    {
        return $this->hasMany('App\Models\organization', 'parent_id')->with('children');
    }

    /**
     * Get the parent that owns the organization.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Organization');
    }

    /**
     * Get the roles for the organization.
     */
    public function roles()
    {
        return $this->hasMany('App\Models\OrganizationRoleType');
    }

    /**
     * The user roles that belong to the organization.
     */
    public function userRoles()
    {
        return $this->belongsToMany('App\Models\OrganizationRoleType', 'organization_user_roles');
    }
}
