<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaSource extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'media_type'
    ];

    /** 
    * Detail         Define belongs to many relationship with organization_media_sources table,
    * @return        Relationship
    */
    public function mediaSourcesOrg()
    {
        return $this->belongsToMany('App\Models\Organization', 'organization_media_sources');
    }
}
