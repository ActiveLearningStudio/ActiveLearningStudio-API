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
    ];

    /**
     * Get the playlist that owns the activity
     */
    public function playlist()
    {
        return $this->belongsTo('App\Models\Playlist', 'playlist_id');
    }
}
