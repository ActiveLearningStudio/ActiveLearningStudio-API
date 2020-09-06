<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Activity view logs keep track of each time
// an activity has been viewed

class ActivityViewLog extends Model
{
    protected $fillable = [
        'activity_id',
        'message'
    ];

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity', 'activity_id');
    }
}