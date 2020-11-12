<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GcClasswork extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'classwork_id',
        'path',
        'course_id'
    ];
}
