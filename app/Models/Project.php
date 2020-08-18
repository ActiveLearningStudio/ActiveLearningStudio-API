<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
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
        'thumb_url',
        'shared'
    ];

    /**
     * Get the users for the projects
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_project')->withPivot('role')->withTimestamps();
    }

    /**
     * Get the playlists for the project
     */
    public function playlists()
    {
        return $this->hasMany('App\Models\Playlist', 'project_id');
    }

    /**
     * Cascade on delete the project
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Project $project) {
            foreach ($project->playlists as $playlist)
            {
                $playlist->delete();
            }
        });
    }
}
