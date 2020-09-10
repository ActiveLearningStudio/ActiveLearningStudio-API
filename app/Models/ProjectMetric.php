<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Project metrics keep track of how much storage an
// Project is using, how many times it has been viewed
// and the bandwidth that has been consumed serving it
// This just accounts for the project storage itself, not the activities within.
// Activities are counted by ActivityMetric

class ProjectMetric extends Model
{
    protected $fillable = [
        'project_id',
        'view_count',   // Amount of times the project has been viewed by users (Final consumers, not CurrikiStudio)
        'share_count',  // Amount of times the project has been published/shared
        'used_storage', // The amount of storage space the project is using (in bytes)
        'used_bandwidth'// The amount of bandwidth viewers of the project have consumed over time (in bytes)
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }
}