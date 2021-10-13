<?php
namespace CurrikiTsugi;
use Tsugi\Core\LTIX;
use \Tsugi\Core\Result;
use \Tsugi\Util\U;
use \Tsugi\Util\LTIConstants;
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
        global $LTI, $CFG;
        
        if (!is_null($this->controller)) {
            global $path_info_parts;            
            //execute controller instead LTI Launch - also set controller action
            if (isset($path_info_parts[1]) && method_exists($this->controller, $path_info_parts[1])) {
                call_user_func(array($this->controller, $path_info_parts[1]));
            } else {
                call_user_func(array($this->controller, 'index'));
            }            
        } else {
            $LTI = LTIX::requireData();
           
            $course_id = ParamValidate::getKeyInCustomFields($_SESSION, 'course_id');
            $custom_email_id = ParamValidate::getKeyInCustomFields($_SESSION, 'person_email_primary');
            $custom_course_name = ParamValidate::getKeyInCustomFields($_SESSION, 'course_name');
            $custom_api_domain_url = ParamValidate::getKeyInCustomFields($_SESSION, 'api_domain_url');
            $custom_course_code = ParamValidate::getKeyInCustomFields($_SESSION, 'course_code');

            // $LTI->var_dump();
            // Obtain User ID
            $user_id = $LTI->user->id; //TSUGI member ID
            // Obtain User Email
            $user_email = $LTI->user->email ?: false; //Canvas User email
            if (!$user_email && !empty($custom_email_id)) {
                // Try to obtain it from the custom fields.
                $user_email = $custom_email_id;
            }
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
            } elseif ($activity_id) { 
                // Check if the grade is being passedback
                $is_gradepassback = (int) U::get($_GET, "gpb");
                if ($is_gradepassback) {
                    $gradetosend = U::get($_GET, 'final_grade') * 1.0;
                    $scorestr = "Your score of " . sprintf("%.2f%%", $gradetosend * 100) . " has been saved.";
                   
                    $lti_data = $LTI->ltiParameterArray();
                    if (isset($_SESSION['lti_post']['lti_version']) && $_SESSION['lti_post']['lti_version'] == "LTI-1p0") {
                        $grade_params = $lti_data;
                        $grade_params['note'] = "You've been graded.";
                        $grade_params['result_id'] = $lti_data['result_id'];
                    }
                    else {
                        $grade_params['issuer_client'] = $lti_data['issuer_client'];
                        $grade_params['lti13_privkey'] = $lti_data['lti13_privkey'];
                        $grade_params['lti13_lineitem'] = $lti_data['lti13_lineitem'];
                        $grade_params['lti13_token_url'] = $lti_data['lti13_token_url'];
                        $grade_params['lti13_token_audience'] = $lti_data['lti13_token_audience'];
                        $grade_params['lti13_pubkey'] = $lti_data['lti13_pubkey'];
                        $grade_params['lti13_subject_key'] = $lti_data['subject_key'];
                        $grade_params['note'] = "You've been graded.";
                        $grade_params['result_id'] = $lti_data['result_id'];
                    }

                    // LTI Submission Review - Canvas' Score API implementation
                    //Submission review link
                    $review_data = [];
                    $review_data['result_id'] = $lti_data['result_id'];
                    $review_data['activity_id'] = $activity_id;
                    $review_data['user_id'] = $user_id;
                    
                    $build_review_data = http_build_query($review_data);

                    // encode user information.
                    $lti_submission_info = base64_encode($build_review_data);

                    if (isset($_SESSION['lti']['issuer_client'])) {
                        $grade_params['lti13_extra'] = [
                            'https://canvas.instructure.com/lti/submission' => [
                                "new_submission" => true,
                                "submission_type" => "basic_lti_launch",
                                "submission_data" => $CFG->wwwroot . '/mod/curriki/?submission=' . $lti_submission_info,
                                "submitted_at" => date(DATE_RFC3339_EXTENDED),
                            ]
                        ];
                    }
                    
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
                }
                // get the result id
                $submission_id = $LTI->result->id;
                $lti_token_params = http_build_query($_SESSION['lti_post']);
                $activity_studio_link = CURRIKI_STUDIO_HOST . "/lti-tools/activity/$activity_id";
                $redirect_to_studio_url = $activity_studio_link . "?" . $lti_token_params;
                $custom_variable_array = array(
                    'user_id',
                    'tool_platform',
                    'is_learner',
                    'submission_id',
                    'course_id',
                    'custom_course_name',
                    'custom_api_domain_url',
                    'custom_course_code'
                );
                foreach($custom_variable_array as $extra_param) {
                    $redirect_to_studio_url .= '&' . $extra_param . '=' . urlencode($$extra_param);
                }
                $redirect_to_studio_url .= '&homepage=' . urlencode($CFG->wwwroot);
                $redirect_to_studio_url = addSession($redirect_to_studio_url);
                
                header("Location: $redirect_to_studio_url");
            } else {
                // LTI Submission Review - Canvas' Score API implementation
                $is_submission_review = U::get($_GET, "submission");
                if (!empty($is_submission_review)) {
                    
                    parse_str(base64_decode($is_submission_review), $submission_data);
                    $submission_data['referrer'] = $CFG->wwwroot;
                    $build_submission_request_data = http_build_query($submission_data);

                    // encode user information.
                    $lti_summary_info = base64_encode($build_submission_request_data);
                    $studio_lti_summary_link = CURRIKI_STUDIO_HOST . "/lti/summary?submission=$lti_summary_info";
                    
                    // Redirect User to the login page.
                    header("Location: $studio_lti_summary_link");
                    exit(0);
                }
                // Single Sign On LTI request
                // we should move this to a new 
                $lti_data = $LTI->ltiParameterArray();    
                $request_data = [];
                foreach (['user_key', 'user_email', 'user_displayname'] as $param) {
                    $request_data[$param] = $lti_data[$param];
                }  
                
                $first_name = $LTI->ltiRawParameter(LTIConstants::LIS_PERSON_NAME_FAMILY, false);
                $last_name = $LTI->ltiRawParameter(LTIConstants::LIS_PERSON_NAME_GIVEN, false);
                $person_sourcedid = $LTI->ltiRawParameter(LTIConstants::LIS_PERSON_SOURCEDID, false);
                $tool_platform = $LTI->ltiRawParameter(LTIConstants::TOOL_CONSUMER_INFO_PRODUCT_FAMILY_CODE, false);
                $tool_consumer_instance_name = $LTI->ltiRawParameter(LTIConstants::TOOL_CONSUMER_INSTANCE_NAME, false);
                $tool_consumer_instance_guid = $LTI->ltiRawParameter(LTIConstants::TOOL_CONSUMER_INSTANCE_GUID, false);
                $custom_school = $LTI->ltiRawParameter('custom_' . $tool_platform . '_schoolname', false);
                $request_data['first_name'] = $first_name;
                $request_data['last_name'] = $last_name;
                $request_data['tool_platform'] = $tool_platform;
                $request_data['tool_consumer_instance_name'] = $tool_consumer_instance_name;
                $request_data['tool_consumer_instance_guid'] = $tool_consumer_instance_guid;
                $request_data['custom_' . $tool_platform . '_school'] = $custom_school;

                $build_request_data = http_build_query($request_data);

                // encode user information.
                $lti_user_info = base64_encode($build_request_data);
                $studio_login_link = CURRIKI_STUDIO_HOST . "/lti-sso?sso_info=$lti_user_info";
                
                // Redirect User to the login page.
                header("Location: $studio_login_link");
                exit(0);
            }

        }
    }
}
