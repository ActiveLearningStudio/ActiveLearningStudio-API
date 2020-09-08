<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Playlist metrics keep track of how much storage an
// Playlist is using, how many times it has been viewed
// and the bandwidth that has been consumed serving it
// This just accounts for the playlist storage itself, not the activities within.
// Activities are counted by ActivityMetric

class PlaylistMetric extends Model
{
    protected $fillable = [
        'playlist_id',
        'view_count',   // Amount of times the playlist has been viewed by users (Final consumers, not CurrikiStudio)
        'share_count',  // Amount of times the playlist has been published/shared
        'used_storage', // The amount of storage space the playlist is using (in bytes)
        'used_bandwidth'// The amount of bandwidth viewers of the playlist have consumed over time (in bytes)
    ];

    public function playlist()
    {
        return $this->belongsTo('App\Models\Playlist', 'playlist_id');
    }
}