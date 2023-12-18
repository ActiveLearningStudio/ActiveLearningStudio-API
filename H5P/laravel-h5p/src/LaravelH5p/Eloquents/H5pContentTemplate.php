<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;

class H5pContentTemplate extends Model
{
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'library',
        'template'
    ];
}
