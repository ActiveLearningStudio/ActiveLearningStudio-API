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
        'published',
    ];
    /**
     * Get the organization for LMS setting
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
