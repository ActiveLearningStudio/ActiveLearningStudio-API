<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Playlist extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'project_id',
        'shared',
        'order'
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
        return $this->hasMany('App\Models\Activity', 'playlist_id')->where('activity_type', config('constants.activity_type.activity'));
    }

    /**
     * Cascade on delete the playlist
     */
    public static function boot()
    {
        parent::boot();

        self::deleting(function (Playlist $playlist) {
            $isForceDeleting = $playlist->isForceDeleting();
            foreach ($playlist->activities as $activity)
            {
                if ($isForceDeleting) {
                    if (File::exists(public_path($activity->thumb_url))) {
                        File::delete(public_path($activity->thumb_url));
                    }
                    if (File::exists(public_path('storage/h5p/content/' . $activity->h5p_content_id))) {
                        File::deleteDirectory(public_path('storage/h5p/content/' . $activity->h5p_content_id));
                    }
                    $activity->forceDelete();
                    $activity->h5p_content()->forceDelete();
                } else {
                    $activity->delete();
                }
            }
        });
    }

    /**
     * Get the playlists's project's user.
     *
     * @return object
     */
    public function getUserAttribute()
    {
        if (isset($this->project) && isset($this->project->users)) {
            return $this->project->users()->wherePivot('role', 'owner')->first();
        }

        return null;
    }

    /**
     * Get the model type.
     *
     * @return string
     */
    public function getModelTypeAttribute()
    {
        return 'Playlist';
    }

    /**
     * Get the playlists's project's thumb_url.
     *
     * @return object
     */
    public function getThumbUrlAttribute()
    {
        if (isset($this->project) && isset($this->project->thumb_url)) {
            return $this->project->thumb_url;
        }

        return null;
    }
}
