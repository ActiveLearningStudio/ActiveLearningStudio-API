<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Playlist view logs keep track of each time
// an playlist has been viewed

class PlaylistViewLog extends Model
{
    protected $fillable = [
        'playlist_id',
        'message'
    ];

    public function playlist()
    {
        return $this->belongsTo('App\Models\Playlist', 'playlist_id');
    }
}