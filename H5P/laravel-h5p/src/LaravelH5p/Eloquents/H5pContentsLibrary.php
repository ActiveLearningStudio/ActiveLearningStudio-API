<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;

class H5pContentsLibrary extends Model
{
    protected $primaryKey = ['content_id', 'library_id', 'dependency_type'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content_id',
        'library_id',
        'dependency_type',
        'weight',
        'drop_css',
    ];
}
