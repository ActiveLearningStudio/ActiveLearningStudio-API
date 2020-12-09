<?php
require_once "../config.php";
require_once "../../admin/admin_util.php";
require_once "vendor/autoload.php";
require_once "config.php";

use Pimple\Container;
use CurrikiTsugi\App;
// use Tsugi\Core\LTIX;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// $LTI = LTIX::requireData();
$container = new Container();

$container[Request::class] = function($c){
    $request = Request::createFromGlobals();
    return $request;
};

$container[Response::class] = function($c){
    $response = new Response(
        null,
        Response::HTTP_OK,
        ['content-type' => 'text/html']
    );
    return $response;  
};

$path_info_parts = [];

$container[ControllerInterface::class] = function($c){
    
    $controller = null;
    global $path_info_parts;
    if( isset($_SERVER['PATH_INFO']) ) {
        $path_info_parts = explode('/', $_SERVER['PATH_INFO'] );
        $path_info_parts = array_filter($path_info_parts, function ($el){return !empty(trim($el));});
        $path_info_parts = count($path_info_parts) > 0 ? array_values($path_info_parts) : [];
    }
    
    if( count($path_info_parts) > 0 ){
        $controller_name = "CurrikiTsugi\Controllers\\";        
        $controller_name_parts = explode('-',$path_info_parts[0]);
        
        $controller_name_parts = array_map(function($item){return ucfirst($item);}, $controller_name_parts);
        $controller = $controller_name.implode('',$controller_name_parts);        
        if (class_exists($controller)) {
            global $path_info_parts;
            $controller = new $controller($c[Request::class], $c[Response::class]);
        }        
    }

    return $controller;

};

$container[App::class] = function($c){
    return new App($c[ControllerInterface::class]);
};

$app = $container[App::class];
$app->bootstrap();
