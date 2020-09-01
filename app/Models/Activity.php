<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'playlist_id',
        'title',
        'type',
        'content',
        'shared',
        'order',
        'thumb_url',
        'subject_id',
        'education_level_id',
        'h5p_content_id',
        'is_public',
    ];

    /**
     * Get the playlist that owns the activity
     */
    public function playlist()
    {
        return $this->belongsTo('App\Models\Playlist', 'playlist_id');
    }

    /**
    * Get the H5P Content relation
    */
    public function h5p_content()
    {
        return $this->belongsTo('Djoudi\LaravelH5p\Eloquents\H5pContent', 'h5p_content_id');
    }
}
