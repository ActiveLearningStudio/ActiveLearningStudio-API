<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// Project view logs keep track of each time
// an project has been viewed

class ProjectViewLog extends Model
{
    protected $fillable = [
        'project_id',
        'message'
    ];

    public function project()
    {
        return $this->belongsTo('App\Models\Project', 'project_id');
    }
}