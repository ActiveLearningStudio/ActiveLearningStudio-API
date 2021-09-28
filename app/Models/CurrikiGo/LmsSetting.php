<?php

namespace App\Models\CurrikiGo;

use App\Models\Organization;
use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class LmsSetting extends Model
{
    use SoftDeletes, GlobalScope;

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
        'lms_login_id',
        'user_id',
        'published',
        'organization_id',
    ];

    /**
     * Get the user that owns the LMS setting
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the organization for LMS setting
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

}
