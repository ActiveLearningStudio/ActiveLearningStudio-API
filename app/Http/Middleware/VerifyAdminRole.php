<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class VerifyAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! (auth()->user())->isAdmin()){
            Log::info('Unauthorized User: ');
            Log::info(auth()->user());
            if ($request->wantsJson() || $request->is('api/*')){
                return response(['errors' => ['Unauthorized!'],], 401);
            }
           abort(401);
        }
        return $next($request);
    }
}
