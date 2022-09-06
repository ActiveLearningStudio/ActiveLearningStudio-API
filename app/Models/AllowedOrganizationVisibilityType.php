<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AllowedOrganizationVisibilityType extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'organization_visibility_type_id',
        'organization_id'
    ];


     /**
     * Get the visibility type of the organization
     */
    public function visibilityType()
    {
        return $this->belongsTo('App\Models\OrganizationVisibilityType', 'organization_visibility_type_id', 'id');
    }
}
