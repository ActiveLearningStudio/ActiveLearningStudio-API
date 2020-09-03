<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;

class H5pLibrariesCachedAsset extends Model
{
    protected $primaryKey = ['library_id', 'hash'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'library_id',
        'hash',
    ];
}
