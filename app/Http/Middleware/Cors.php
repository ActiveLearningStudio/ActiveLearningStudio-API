<?php

namespace App\Http\Middleware;

use Closure;

class Cors
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
	    $allowed_cors = explode(",", config('allowed_urls.urls'));

        $input = isset($_SERVER["HTTP_REFERER"]) ? $_SERVER['HTTP_REFERER'] :  "";
        $domain_arr = array_filter($allowed_cors, function ($item) use ($input) {
            if (stripos($item, $input) !== false) {
                return true;
            }
            return false;
        });
        //$domain = isset($domain_arr[0])? $domain_arr[0] : null;
        //$domain = array_pop($domain_arr);
        header('Access-Control-Allow-Origin: https://dev.currikistudio.org');

        header('Access-Control-Allow-Methods: GET, POST');

        header("Access-Control-Allow-Headers: X-Requested-With");
        // return response()->json(["test"=>json_encode($_SERVER["HTTP_REFERER"])]);
        return $next($request);
    }
}
