<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// During the registration flow, users are required to specify
// an organization type to which they belong.
// The options for this drop down are defined here.
// Attributes: id, name, label, order

class OrganizationType extends Model
{
    protected $fillable = [
        'name',
        'label',
        'order'
    ];

    public $timestamps = false;
}