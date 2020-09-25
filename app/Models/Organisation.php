<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\GlobalScope;
use App\Models\DeepRelations\HasManyDeep;
use App\Models\DeepRelations\HasRelationships;

class Organisation extends Model
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
        'parent_id'
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
     * Get playlists directly from organisations model via hasManyThrough
     * @return HasManyThrough
     */
    public function playlists()
    {
        return $this->hasManyThrough('App\Models\Playlist', 'App\Models\Project', 'organisation_id', 'project_id', 'id', 'id');
    }

    /**
     * Get far away relations data using custom Deep classes
     * @return HasManyDeep
     */
    public function activities()
    {
        return $this->hasManyDeep(
            'App\Models\Activity',
            ['App\Models\Project', 'App\Models\Playlist'], // Intermediate models, beginning at the far parent (Organisations).
            [
                'organisation_id', // Foreign key on the "project" table.
                'project_id',    // Foreign key on the "playlist" table.
                'playlist_id'     // Foreign key on the "activity" table.
            ],
            [
                'id', // Local key on the "users" table.
                'id', // Local key on the "project" table.
                'id'  // Local key on the "playlist" table.
            ]
        );
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
