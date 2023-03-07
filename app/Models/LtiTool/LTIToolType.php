<?php

namespace App\Models\LtiTool;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LtiToolType extends Model
{
    use SoftDeletes;
    protected $table = 'lti_tool_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];
}
