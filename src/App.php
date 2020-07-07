<?php
namespace CurrikiTsugi;
use Tsugi\Core\LTIX;
use \Tsugi\Core\Result;
use \Tsugi\Grades\GradeUtil;
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
            global $path_info_parts;            
            //execute controller instead LTI Launch
            if (isset($path_info_parts[1]) && method_exists($this->controller, $path_info_parts[1])) {
                call_user_func(array($this->controller, $path_info_parts[1]));
            }else {
                call_user_func(array($this->controller, 'index'));
            }            
        }else {

            //exectute LTI Launch                        
            $LTI = LTIX::requireData();
            $playlist_id = ParamValidate::playlistInCustom($_SESSION) ?: ParamValidate::playlistInQueryString($_SESSION);
            if ($playlist_id) {
                $lti_token_params = http_build_query($_SESSION['lti_post']);
                $playlist_studio_link = CURRIKI_STUDIO_HOST."/playlist/lti/preview/$playlist_id";
                $redirect_to_studio_url = $playlist_studio_link . "?" . $lti_token_params;
                header("Location: $redirect_to_studio_url");
            }else{
                echo "<h1>Curriki LTI Tool</h1>";
                echo "<pre>"; 
                $LTI->var_dump();
                $lti_data = $_SESSION['lti'];                
                $grade_params['issuer_client'] = $lti_data['issuer_client'];
                $grade_params['lti13_privkey'] = $lti_data['lti13_privkey'];
                $grade_params['lti13_lineitem'] = $lti_data['lti13_lineitem'];
                $grade_params['lti13_token_url'] = $lti_data['lti13_token_url'];
                $grade_params['lti13_token_audience'] = $lti_data['lti13_token_audience'];
                $grade_params['lti13_pubkey'] = $lti_data['lti13_pubkey'];                
                $grade_params['subject_key'] = $lti_data['subject_key'];                
                $grade_params['note'] = "Hey You Graded";                
                
                //var_dump($LTI->result->gradeSend(0.25, $grade_params));
                
            }

        }
    }
}
