<?php

namespace App\Models;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DefaultSsoIntegrationSettings extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lms_url',
        'lms_access_token',
        'site_name',
        'lms_name',
        'lms_access_key',
        'lms_access_secret',
        'description',
        'lti_client_id',
        'organization_id',
        'guid',
        'role_id',
        'project_visibility',
        'playlist_visibility',
        'activity_visibility',
    ];
    /**
     * Get the organization for Default SSO Integration
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    /**
     * Get the Role for Default SSO Integration
     */
    public function role()
    {
        return $this->belongsTo('App\Models\OrganizationRoleType', 'role_id');
    }
}
