<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;

class H5pEvent extends Model
{
    protected $primaryKey = ['type', 'library_name', 'library_version'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'library_name',
        'library_version',
        'num',
    ];
}
