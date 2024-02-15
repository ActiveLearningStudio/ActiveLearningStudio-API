<?php

namespace App\Models\C2E\Publisher;

use App\Models\Organization;
use App\Models\Traits\GlobalScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class Publisher extends Model
{
    use SoftDeletes, GlobalScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'url',
        'key',
        'organization_id',
        'user_id',
        'project_visibility',
        'playlist_visibility',
        'activity_visibility',
    ];

    /**`
     * Get the user that owns the publisher
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the organization for publisher
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
