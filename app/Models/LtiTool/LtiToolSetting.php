<?php

namespace App\Models\LtiTool;

use App\Models\Organization;
use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;
use App\Models\LtiTool\LtiToolTypesConfig;
use App\Models\MediaSource;

class LtiToolSetting extends Model
{
    use SoftDeletes, GlobalScope;
    protected $table = 'lti_tool_settings';
    /**
     * @author        Asim Sarwar
     * Date           11-10-2021
     * The attributes that are mass assignable.     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'organization_id',
        'tool_name',
        'tool_url',
        'tool_domain',
        'lti_version',
        'tool_consumer_key',
        'tool_secret_key',
        'tool_description',
        'tool_custom_parameter',
        'tool_content_selection_url',
        'tool_client_id',
        'tool_proxy_id',
        'tool_enabled_capability',
        'tool_icon',
        'tool_secure_icon',
        'media_source_id'
    ];

    /** 
    * @author        Asim Sarwar
    * Date           11-10-2021
    * Detail         Define belongs to relationship with users table,
    * @return        Relationship
    */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** 
    * @author        Asim Sarwar
    * Date           11-10-2021
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

}
