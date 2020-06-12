<?php
namespace CurrikiTsugi;
use Tsugi\Core\LTIX;
use CurrikiTsugi\Interfaces\ControllerInterface;

class App
{
    public $controller;

    public function __construct(ControllerInterface $controller = null) {        
        $this->controller = $controller;
    }   
    
    public function bootstrap()
    {        
        if(!is_null($this->controller)){
            //execute controller instead LTI Launch
            if (isset($_GET['act']) && method_exists($this->controller, $_GET['act'])) {
                call_user_func(array($this->controller, $_GET['act']));
            }else {
                call_user_func(array($this->controller, 'index'));
            }            
        }else {
            //exectute LTI Launch
            $lti_launch = LTIX::requireData();
            $lti_launch->var_dump();
        }        
    }
}
