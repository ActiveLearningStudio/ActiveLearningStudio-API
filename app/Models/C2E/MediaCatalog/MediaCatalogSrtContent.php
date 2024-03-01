<?php

namespace App\Models\C2E\MediaCatalog;

use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\C2E\MediaCatalog\MediaCatalogAPISetting;

class MediaCatalogSrtContent extends Model
{
    use SoftDeletes, GlobalScope;
    protected $table = 'media_catalog_srt_contents';
    /**
     * @author        Asim Sarwar
     * The attributes that are mass assignable.     *
     * @var array
     */
    protected $fillable = [
        'media_catalog_api_setting_id',    
        'video_id',
        'content'
    ];

    /** 
    * @author        Asim Sarwar
    * Detail         Define belongs to relationship with media catalog api settings table,
    * @return        Relationship
    */
    public function apiSetting()
    {
        return $this->belongsTo(MediaCatalogAPISetting::class, 'media_catalog_api_setting_id');
    }

}
