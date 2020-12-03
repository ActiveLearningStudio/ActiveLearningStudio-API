<?php
namespace CurrikiTsugi;
use Tsugi\Core\LTIX;
use \Tsugi\Core\Result;
use \Tsugi\Util\U;
use \Tsugi\Grades\GradeUtil;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class App
{
    public $controller;

    public function __construct(ControllerInterface $controller = null) {        
        $this->controller = $controller;
    }   
    
    public function bootstrap()
    {        
        global $LTI;
        
        if (!is_null($this->controller)) {
            global $path_info_parts;            
            //execute controller instead LTI Launch - also set controller action
            if (isset($path_info_parts[1]) && method_exists($this->controller, $path_info_parts[1])) {
                call_user_func(array($this->controller, $path_info_parts[1]));
            } else {
                call_user_func(array($this->controller, 'index'));
            }            
        } else {
            // Obtain User ID
            $user_id = $LTI->user->id; //TSUGI member ID
            // Obtain User Email
            $user_email = $LTI->user->email ?: false; //Canvas User email
            // Obtain User role
            $is_learner = !$LTI->user->instructor;
            $tool_platform = ParamValidate::toolPlatformInfo($_SESSION);
            $playlist_id = ParamValidate::playlistInCustom($_SESSION) ?: ParamValidate::playlistInQueryString($_SESSION);
            $project_id = ParamValidate::projectInCustom($_SESSION) ?: ParamValidate::projectInQueryString($_SESSION);
            $activity_id = ParamValidate::activityInCustom($_SESSION) ?: ParamValidate::activityInQueryString($_SESSION);
            if ($project_id) {
                $lti_token_params = http_build_query($_SESSION['lti_post']);
                $project_studio_link = CURRIKI_STUDIO_HOST . "/project/preview2/$project_id";
                $redirect_to_studio_url = $project_studio_link . "?" . $lti_token_params;
                header("Location: $redirect_to_studio_url");
            } elseif ($playlist_id && !$activity_id) {
                $lti_token_params = http_build_query($_SESSION['lti_post']);
                $playlist_studio_link = CURRIKI_STUDIO_HOST . "/playlist/$playlist_id/preview/lti";
                $redirect_to_studio_url = $playlist_studio_link . "?" . $lti_token_params;
                header("Location: $redirect_to_studio_url");
            } elseif ($activity_id && $playlist_id) {
                // Check if the grade is being passedback
                $is_gradepassback = (int) U::get($_GET, "gpb");
                if ($is_gradepassback) {
                    $gradetosend = U::get($_GET, 'final_grade') * 1.0;
                    $scorestr = "Your score of " . sprintf("%.2f%%", $gradetosend * 100) . " has been saved.";
                   
                    $lti_data = $LTI->ltiParameterArray();       
                    $grade_params['issuer_client'] = $lti_data['issuer_client'];
                    $grade_params['lti13_privkey'] = $lti_data['lti13_privkey'];
                    $grade_params['lti13_lineitem'] = $lti_data['lti13_lineitem'];
                    $grade_params['lti13_token_url'] = $lti_data['lti13_token_url'];
                    $grade_params['lti13_token_audience'] = $lti_data['lti13_token_audience'];
                    $grade_params['lti13_pubkey'] = $lti_data['lti13_pubkey'];                
                    $grade_params['lti13_subject_key'] = $lti_data['subject_key'];                
                    $grade_params['note'] = "You've been graded.";
                    $grade_params['result_id'] = $lti_data['result_id'];
                    
                    // Use LTIX to send the grade back to the LMS.
                    // if you don't know the data to send when creating the response
                    $response = new JsonResponse();
                    $debug_log = [];
                    $retval = $LTI->result->gradeSend($gradetosend, $grade_params, $debug_log);
                    $_SESSION['debug_log'] = $debug_log;
                    $output = '';
                    if ($retval === true) {
                        $response->setStatusCode(Response::HTTP_OK);

                        $response->setData(['success' => true, "message" => $scorestr]);
                        $_SESSION['success'] = $scorestr;
                        //$output = $scorestr;
                    } elseif (is_string($retval)) {
                        $_SESSION['error'] = "Grade not sent: " . $retval;
                        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

                        $response->setData(['errors' => ["Grade not sent: " . $retval]]);
                        $output = $_SESSION['error'];
                    } else {
                        // $output = "<pre>\n" . print_r($retval, true) . "</pre>\n";
                        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                        $response->setData(['errors' => ["Grade not sent."]]);
                    }

                    $response->send();
                    exit(0);
/*
                    echo <<<EOT
                    <!DOCTYPE html>
                    <html>
                    <head>
                    <title>TSUGI</title>
                    </head>
                    <body>
                    <h2><center>$output</center></h2>
                    </body>
                    </html>
EOT;
                    die();*/
                }
                
                $lti_token_params = http_build_query($_SESSION['lti_post']);
                $activity_studio_link = CURRIKI_STUDIO_HOST . "/lti-tools/activity/$activity_id";
                $redirect_to_studio_url = $activity_studio_link . "?" . $lti_token_params;
                foreach(['user_id', 'tool_platform', 'is_learner'] as $extra_param) {
                    $redirect_to_studio_url .= '&' . $extra_param . '=' . urlencode($$extra_param);
                }
                $redirect_to_studio_url = addSession($redirect_to_studio_url);
                
                header("Location: $redirect_to_studio_url");
            } else {
                /* echo "<h1>Curriki LTI Tool</h1>";
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
                
                //var_dump($LTI->result->gradeSend(0.61, $grade_params));
                var_dump($LTI->result->gradeSend(0.61));*/
                // Nothing to show here.
                
            }

        }
    }
}
