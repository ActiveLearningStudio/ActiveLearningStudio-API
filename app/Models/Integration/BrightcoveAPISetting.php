<?php

namespace App\Models\Integration;

use App\Models\Organization;
use App\Models\H5pBrightCoveVideoContents;
use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class BrightcoveAPISetting extends Model
{
    use SoftDeletes, GlobalScope;
    protected $table = 'brightcove_api_settings';
    /**
     * @author        Asim Sarwar
     * Date           09-12-2021
     * The attributes that are mass assignable.     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'organization_id',
        'account_id',
        'account_name',
        'account_email',
        'client_id',
        'client_secret',
        'description',
        'css_path'
    ];

    /** 
    * @author        Asim Sarwar
    * Date           09-12-2021
    * Detail         Define belongs to relationship with users table,
    * @return        Relationship
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** 
    * @author        Asim Sarwar
    * Date           09-12-2021
    * Detail         Define belongs to relationship with organizations table,
    * @return        Relationship
    */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

    /** 
    * @author        Asim Sarwar
    * Date           05-01-2022
    * Detail         Define has many relationship with h5p_brightcove_video_contents table,
    * @return        Relationship
    */
    public function h5pBrightcoveVideoContents()
    {
        return $this->hasMany(H5pBrightCoveVideoContents::class, 'brightcove_api_setting_id', 'id');
    }   

}
