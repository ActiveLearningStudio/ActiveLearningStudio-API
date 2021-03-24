<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\GlobalScope;
use App\Models\DeepRelations\HasManyDeep;
use App\Models\DeepRelations\HasRelationships;

class OrganizationPermissionType extends Model
{
    use SoftDeletes, GlobalScope, HasRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'feature'
    ];
}
