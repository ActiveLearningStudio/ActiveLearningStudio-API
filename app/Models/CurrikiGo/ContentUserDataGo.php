<?php

namespace App\Models\CurrikiGo;

use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentUserDataGo extends Model
{
    use GlobalScope;

    protected $table = 'h5p_contents_user_data_go';
    protected $primaryKey = null;
    public $incrementing = false;
    const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content_id',
        'user_id',
        'sub_content_id',
        'data_id',
        'data',
        'preload',
        'invalidate',
        'updated_at',
        'go_integration',
        'submission_id'
    ];

}
