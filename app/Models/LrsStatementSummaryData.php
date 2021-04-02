<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LrsStatementSummaryData extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'statement_id',
        'statement_uuid',
        'actor_id',
        'class_id',
        'actor_homepage',
        'assignment_name',
        'page_name',
        'is_page_accessed',
        'is_event_interacted',
        'interacted_count',
        'total_interactions',
        'score'
    ];
}
