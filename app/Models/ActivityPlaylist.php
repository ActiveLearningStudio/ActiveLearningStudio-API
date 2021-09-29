<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityPlaylist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_playlist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'activity_id',
        'playlist_id',
        'order'
    ];


}
