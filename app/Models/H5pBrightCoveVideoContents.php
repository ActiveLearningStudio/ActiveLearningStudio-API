<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Djoudi\LaravelH5p\Eloquents\H5pContent;
use App\Models\Integration\BrightcoveAPISetting;

class H5pBrightCoveVideoContents extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'h5p_brightcove_video_contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'brightcove_video_id',
        'h5p_content_id',
        'brightcove_api_setting_id'
    ];

    public function brightcove_api_setting()
    {
        return $this->hasOne(BrightcoveAPISetting::class,'id','brightcove_api_setting_id');
    }

    public function activities()
    {
        return $this->hasMany(Activity::class,'h5p_content_id','h5p_content_id');
    }
}