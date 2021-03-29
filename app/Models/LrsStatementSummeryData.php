<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LrsStatementSummeryData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'class_id',
        'assignment_name',
        'user_id',
        'page_name',
        'is_page_accessed',
        'is_event_interacted',
        'interacted_count',
        'total_interactions',
        'score'
    ];
}
