<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class LowercaseEmail extends TransformsRequest
{
    /**
     * Transform the given value.
     *
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    protected function transform($key, $value)
    {
        return $key === 'email' ? strtolower($value) : $value;
    }
}
