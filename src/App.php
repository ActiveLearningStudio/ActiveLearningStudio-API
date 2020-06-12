<?php
namespace CurrikiTsugi;
use Tsugi\Core\Launch;
use CurrikiTsugi\Interfaces\ControllerInterface;

class App
{
    public $lti_launch;

    public function __construct(Launch $lti_launch, ControllerInterface $controller = null) {
        $this->lti_launch = $lti_launch;
        $this->controller = $controller;
    }   
    
    public function bootstrap()
    {
        if(!is_null($this->controller)){
            
            if (isset($_GET['act']) && method_exists($this->controller, $_GET['act'])) {
                call_user_func(array($this->controller, $_GET['act']));
            }else {
                call_user_func(array($this->controller, 'index'));
            }            
        }else {
            $this->lti_launch->var_dump();
        }        
    }
}
