<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\GlobalScope;
use App\Models\DeepRelations\HasManyDeep;
use App\Models\DeepRelations\HasRelationships;
use Illuminate\Support\Arr;
use DB;

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
        'favicon',
        'parent_id',
        'self_registration',
        'account_id',
        'api_key',
        'unit_path',
        'noovo_client_id',
        'gcr_project_visibility',
        'gcr_playlist_visibility',
        'gcr_activity_visibility',
        'tos_type',
        'tos_url',
        'tos_content',
        'privacy_policy_type',
        'privacy_policy_url',
        'privacy_policy_content',
        'primary_color',
        'secondary_color',
        'tertiary_color',
        'primary_font_family',
        'secondary_font_family'
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

    /**
     * Get the media sources for the organization.
     */
    public function mediaSources()
    {
        return $this->belongsToMany('App\Models\MediaSource', 'organization_media_sources');
    }

    /**
     * Get organization IDs for the full tree (ancestors and children) of this org
     */
    public function getOrgTreeAttribute()
    {
        $results = DB::select( DB::raw("
            WITH RECURSIVE child_orgs AS (
                SELECT 
                    o1.id, o1.parent_id, o1.name 
                FROM 
                    organizations o1 
                WHERE 
                    o1.deleted_at IS NULL AND
                    o1.id = :thisOrgId
                
                UNION ALL
                
                SELECT 
                    o2.id, o2.parent_id, o2.name 
                FROM
                    organizations o2
                JOIN
                    child_orgs co
                ON 
                    co.id = o2.parent_id 
                WHERE
                    o2.deleted_at IS NULL
            ), parent_orgs AS (
                SELECT 
                    o3.id, o3.parent_id, o3.name 
                FROM 
                    organizations o3 
                WHERE 
                    o3.deleted_at IS NULL AND
                    o3.id = :thisOrgId
                
                UNION ALL
                
                SELECT 
                    o4.id, o4.parent_id, o4.name 
                FROM
                    organizations o4
                JOIN
                    parent_orgs po
                ON 
                    po.parent_id = o4.id
                WHERE
                    o4.deleted_at IS NULL
            )

            SELECT DISTINCT * FROM child_orgs

            UNION ALL

            SELECT DISTINCT * FROM parent_orgs
        "), array('thisOrgId' => $this->id));
        return Arr::pluck($results, 'id');
    }

    /**
     * Get the independent activities for the organization
     */
    public function independentActivities()
    {
        return $this->hasMany('App\Models\IndependentActivity', 'organization_id');
    }
}
