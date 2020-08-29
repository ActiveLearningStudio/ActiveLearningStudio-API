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
        'ajax/library-install',
        'ajax/files',
        'ajax/library-upload',
        'ajax/filter',
        'ajax/content-user-data',
        'ajax/finish'
    ];
}
