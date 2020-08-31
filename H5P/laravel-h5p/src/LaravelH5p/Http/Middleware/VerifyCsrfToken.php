<?php

namespace Djoudi\LaravelH5p\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'h5p/ajax/library-install',
        'h5p/ajax/files',
        'h5p/ajax/library-upload',
        'h5p/ajax/filter',
        'h5p/ajax/content-user-data',
        'h5p/ajax/finish'
    ];
}
