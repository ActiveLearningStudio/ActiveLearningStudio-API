<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityType extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'order',
        'image',
    ];

    /**
     * Get the activity items for activity type
     */
    public function activityItems()
    {
        return $this->hasMany('App\Models\ActivityItem', 'activity_type_id');
    }
}
