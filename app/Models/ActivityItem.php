<?php

namespace App\Models;

use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityItem extends Model
{
    use SoftDeletes, GlobalScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'order',
        'activity_type_id',
        'demo_activity_id',
        'demo_video_id',
        'type',
        'h5pLib',
        'image',
    ];

    /**
     * Get the Activity type that owns the activity item
     */
    public function activityType()
    {
        return $this->belongsTo('App\Models\ActivityType', 'activity_type_id');
    }
}
