<?php

namespace App\Models\C2E\MediaCatalog;

use App\Models\Organization;
use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Models\MediaSource;
use App\Models\C2E\MediaCatalog\MediaCatalogSrtContent;

class MediaCatalogAPISetting extends Model
{
    use SoftDeletes, GlobalScope;
    protected $table = 'media_catalog_api_settings';
    /**
     * @author        Asim Sarwar
     * The attributes that are mass assignable.     *
     * @var array
     */
    protected $fillable = [
        'user_id',    
        'organization_id',
        'media_source_id',
        'name',
        'api_setting_id',
        'email',
        'url',
        'description',
        'client_key',
        'secret_key',
        'custom_metadata'
    ];

    /** 
    * @author        Asim Sarwar
    * Detail         Define belongs to relationship with users table,
    * @return        Relationship
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** 
    * @author        Asim Sarwar
    * Detail         Define belongs to relationship with organizations table,
    * @return        Relationship
    */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    /** 
    * @author        Asim Sarwar
    * Detail         Define belongs to relationship with media_sources table,
    * @return        Relationship
    */
    public function mediaSources()
    {
        return $this->belongsTo(MediaSource::class, 'media_source_id', 'id');
    }

    /** 
    * @author        Asim Sarwar
    * Detail         Define belongs to relationship with media catalog srt contents table,
    * @return        Relationship
    */
    public function srtContent()
    {
        return $this->hasMany(MediaCatalogSrtContent::class, 'media_catalog_api_setting_id');
    }
}
