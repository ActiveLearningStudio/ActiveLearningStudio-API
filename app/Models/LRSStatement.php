<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LRSStatement extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lrs_statements_data';

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
        'project_id',
        'playlist_id',
        'page_completed',
        'assignment_submitted',
        'object_id',
        'duration',
        'datetime',
        'options',
        'answer',
        'object_name',
        'activity_category',
        'actor_homepage',
        'verb',
        'platform',
        'project_name',
        'score_scaled',
        'score_min',
        'score_max',
        'score_raw',
        'playlist_name',
        'assignment_id',
        'assignment_name',
        'page',
        'question',
        'glass_alternate_course_id',
        'glass_enrollment_code',
        'course_name',
        'chapter_name',
        'chapter_index',
        'referrer',
        'submission_id',
        'attempt_id'
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        
        'actor_id' => null,
        'actor_homepage' => null,
        'class_id' => null,
        'project_id' => null,
        'project_name' => null,
        'playlist_id' => null,
        'playlist_name' => null,
        'assignment_id' => null,
        'assignment_name' => null,
        'question' => null,
        'options' => null,
        'answer' => null,
        'assignment_submitted' => false,
        'platform' => null,
        'object_name' => null,
        'activity_category' => null,
        'duration' => null,
        'score_scaled' => null,
        'score_min' => null,
        'score_max' => null,
        'score_raw' => null,
        'page' => null,
        'page_completed' => false,
        'glass_alternate_course_id' => null,
        'glass_enrollment_code' => null,
        'course_name' => null,
        'chapter_name' => 0,
        'chapter_index' => null,
        'referrer' => null,
        'submission_id' => null,
        'attempt_id' => null
    ];
}
