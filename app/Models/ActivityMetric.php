<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Activity metrics keep track of how much storage an
// activity is using, how many times it has been viewed
// and the bandwidth that has been consumed serving it

class ActivityMetric extends Model
{
    protected $fillable = [
        'activity_id',
        'view_count',   // Amount of times the activity has been viewed by users (Final consumers, not CurrikiStudio)
        'share_count',  // Amount of times the activity has been published/shared
        'used_storage', // The amount of storage space the activity is using (in bytes)
        'used_bandwidth'// The amount of bandwidth viewers of the activity have consumed over time (in bytes)
    ];

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity', 'activity_id');
    }
}