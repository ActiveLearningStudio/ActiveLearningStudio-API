<?php

namespace Djoudi\LaravelH5p\Eloquents;

use Illuminate\Database\Eloquent\Model;

class H5pTmpfile extends Model
{
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'created_at',
    ];
}
