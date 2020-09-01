<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Playlist extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'project_id',
        'order',
        'is_public',
    ];

    /**
     * Get the project that owns the playlist
     */
    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }

    /**
     * Get the activities for the playlist
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Activity', 'playlist_id');
    }

    /**
     * Cascade on delete the playlist
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Playlist $playlist) {
            foreach ($playlist->activities as $activity)
            {
                $activity->delete();
            }
        });
    }
}
