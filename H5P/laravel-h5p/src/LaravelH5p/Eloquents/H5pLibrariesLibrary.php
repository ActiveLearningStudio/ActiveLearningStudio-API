<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;

class H5pLibrariesLibrary extends Model
{
    protected $primaryKey = ['library_id', 'required_library_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'library_id',
        'required_library_id',
        'dependency_type',
    ];
}
