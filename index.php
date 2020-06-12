<?php
require_once "../config.php";
require_once "vendor/autoload.php";

use Tsugi\Core\LTIX;
use Tsugi\Core\Launch;
use Pimple\Container;
use CurrikiTsugi\App;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$container = new Container();

$container[Request::class] = function($c){
    $request = Request::createFromGlobals();
    return $request;
};

$container[Response::class] = function($c){
    $response = new Response(
        'Content',
        Response::HTTP_OK,
        ['content-type' => 'text/html']
    );
    return $response;  
};

$container[Launch::class] = function($c){
    $lti_launch = LTIX::requireData();    
    return $lti_launch;
};

$container[ControllerInterface::class] = function($c){
    $controller = null;
    if(isset($_REQUEST['ctrl'])){
        $controller_name = "CurrikiTsugi\Controllers\\";
        $controller_name_parts = explode('-',$_REQUEST['ctrl']);
        
        $controller_name_parts = array_map(function($item){return ucfirst($item);}, $controller_name_parts);
        $controller = $controller_name.implode('',$controller_name_parts);
        if (class_exists($controller)) {
            $controller = new $controller($c[Request::class], $c[Response::class]);
        }        
    }
    return $controller;
};

$container[App::class] = function($c){    
    return new App($c[Launch::class], $c[ControllerInterface::class]);
};

$app = $container[App::class];
$app->bootstrap();
