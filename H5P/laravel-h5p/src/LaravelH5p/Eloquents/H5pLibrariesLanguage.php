<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;

class H5pLibrariesLanguage extends Model
{
    protected $primaryKey = ['library_id', 'language_code'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'library_id',
        'language_code',
        'translation',
    ];
}
