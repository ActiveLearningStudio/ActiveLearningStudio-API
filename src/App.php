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
            $LTI = LTIX::requireData();
            $lti_jwt = $_SESSION['tsugi_jwt'];
            $lti_claim_custom_url = "https://purl.imsglobal.org/spec/lti/claim/custom";
            $lti_claim_custom = $lti_jwt->body->{$lti_claim_custom_url};

            //check if lti launch has 'playlist' custom pramameter with Id
            if ( property_exists($lti_claim_custom, 'playlist') ) {
                $playlist_id = $lti_claim_custom->playlist;
                $lti_token_params = http_build_query($_SESSION['lti_post']);
                $playlist_studio_link = CURRIKI_STUDIO_HOST."/playlist/preview/$playlist_id/resource/5eebbfbcc9bda1111d7be7e3";
                $redirect_to_studio_url = $playlist_studio_link . "?" . $lti_token_params;            
                header("Location: $redirect_to_studio_url");
            }else{
                echo "<h1>Curriki LTI Tool</h1>";
            }

        }
    }
}
