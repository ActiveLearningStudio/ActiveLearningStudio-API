<?php

namespace App\Models;

use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityType extends Model
{
    use SoftDeletes, GlobalScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'order',
        'image',
        'organization_id'
    ];

    /**
     * Get the activity items for activity type
     */
    public function activityItems()
    {
        return $this->hasMany('App\Models\ActivityItem', 'activity_type_id');
    }

    public function activityTypeTitle()
    {
        return $this->hasOne('App\Models\ActivityUiUpdates', 'activity_type_title', 'title');
    }

}
