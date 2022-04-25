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

        $http_referer = isset($_SERVER["HTTP_REFERER"]) ? parse_url($_SERVER['HTTP_REFERER']) :  "";
        $url= "";
        if($http_referer){
            $url = $http_referer["scheme"] . "://" . $http_referer["host"]. (isset($http_referer["port"]) ? ":". $http_referer["port"] : "");
            $domain_arr = array_filter($allowed_cors, function ($item) use ($url) {
                if (stripos($item, $url) !== false) {
                    return true;
                }
                return false;
            });
            $url = reset($domain_arr);
        } 
        
        
        header('Access-Control-Allow-Origin: '.$url);

        header('Access-Control-Allow-Methods: *');

        header("Access-Control-Allow-Headers: *");
        
        return $next($request);
    }
}
