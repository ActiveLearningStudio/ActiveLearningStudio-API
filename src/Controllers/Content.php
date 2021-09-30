<?php
namespace CurrikiTsugi\Controllers;
use CurrikiTsugi\Interfaces\ControllerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CurrikiTsugi\Repositories\IssuerRepository;
use CurrikiTsugi\Repositories\TenantRepository;
use Tsugi\Core\LTIX;
use \Tsugi\Util\U;
use \Tsugi\Core\Settings;
use \Tsugi\Core\ContentItem;
use \Tsugi\Core\DeepLinkResponse;
use \Tsugi\Util\LTI;
use \Tsugi\Util\LTI13;
use \Tsugi\UI\Lessons;
use Symfony\Component\HttpFoundation\RedirectResponse;
use CurrikiTsugi\ParamValidate;

class Content implements ControllerInterface
{
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
        $this->return_url = null;
        $this->allow_lti = null;
        $this->allow_link = null;
        $this->allow_multiple = null;
        $this->allow_import = null;
        $this->LAUNCH = null;
        $this->tool = null;
        $this->debug = false;
        $this->deeplink = false;
        $this->deepLinkingLaunch();
    }

    public function deepLinkingLaunch()
    {
        global $CFG;
        // No parameter means we require CONTEXT, USER, and LINK
        $this->LAUNCH = LTIX::requireData(LTIX::USER);

        // Model
        $p = $CFG->dbprefix;
        $tool_registrations = findAllRegistrations(false, true);
        $this->tool = $tool_registrations['curriki'];
        
        
        if ( isset($this->LAUNCH->deeplink) ) $this->deeplink = $this->LAUNCH->deeplink;
        if ( $this->deeplink ) {
            $this->return_url = $this->deeplink->returnUrl();
            $this->allow_lti = $this->deeplink->allowLtiLinkItem();
            $this->allow_link = $this->deeplink->allowLink();
            $this->allow_multiple = $this->deeplink->allowMultiple();
            $this->allow_import = $this->deeplink->allowImportItem();
        } else {
            $this->return_url = ContentItem::returnUrl();
            $this->allow_lti = ContentItem::allowLtiLinkItem();
            $this->allow_link = ContentItem::allowLink();
            $this->allow_import = ContentItem::allowImportItem();
            $this->allow_multiple = ContentItem::allowMultiple();
        }
    }

    public function index()
    {
        global $CFG;        
        $redirect_url = $CFG->apphome.'/mod/curriki/content/processtolms';
        $redirect_url = U::add_url_parm($redirect_url, 'PHPSESSID', session_id());

        if ( isset($_SESSION['lti_post']['lti_version']) && $_SESSION['lti_post']['lti_version'] === 'LTI-1p0' ) {
            $custom_email_id = ParamValidate::getKeyInCustomFields($_SESSION, 'person_email_primary');
            // handle LTI 1.0
            $oauth_consumer_key = $_SESSION['lti_post']['oauth_consumer_key'];
            $content_item_return_url = isset($_SESSION['lti_post']['content_item_return_url']) ? $_SESSION['lti_post']['content_item_return_url'] : $_SESSION['lti_post']['tool_consumer_instance_url'];
            $port = parse_url($content_item_return_url, PHP_URL_PORT) ? ':'.parse_url($content_item_return_url, PHP_URL_PORT):'';
            $lms_url = parse_url($content_item_return_url, PHP_URL_SCHEME)
                        .'://'.parse_url($content_item_return_url, PHP_URL_HOST).$port;
            
            $studio_url = CURRIKI_STUDIO_HOST.'/lti/content/'.urlencode($lms_url).'/'.$oauth_consumer_key.'/'.urlencode($redirect_url);
            if (!empty($custom_email_id)) {
                $studio_url .= '?user_email=' . urlencode($custom_email_id);
            } else{
                die("You need to set 'person_email_primary' key in external tool settings!");
            }
            $response = new RedirectResponse($studio_url);
            $response->send();
        }elseif ( isset($_SESSION['lti']['issuer_client']) ) {
            $custom_email_id = ParamValidate::getKeyInCustomFields($_SESSION, 'person_email_primary');
            if (isset($_SESSION['lti_post']['placement']) && $_SESSION['lti_post']['placement'] === 'canvas_sso') {
                $user_data = [];
                $user_data['email'] = $custom_email_id;
                $user_data['first_name'] = ParamValidate::getKeyInCustomFields($_SESSION, 'person_name_given');
                $user_data['last_name'] = ParamValidate::getKeyInCustomFields($_SESSION, 'person_name_family');
                $user_data['lti_client_id'] = $_SESSION['lti']['issuer_client'];
                $user_data['tool_platform'] = ParamValidate::toolPlatformInfo($_SESSION);
                $user_data['guid'] = ParamValidate::toolPlatformElement($_SESSION, 'guid');

                $build_request_data = http_build_query($user_data);

                // encode user information.
                $lti_user_info = base64_encode($build_request_data);
                $studio_login_link = CURRIKI_STUDIO_HOST . "/canvas-lti-sso?sso_info=$lti_user_info";
                // Redirect User to the login page.
                header("Location: $studio_login_link");
                exit(0);
            }
            // handle LTI 1.3
            $lti_client_id = $_SESSION['lti']['issuer_client'];
            $lti13_deeplink = $_SESSION['lti']['lti13_deeplink'];
            $port = parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_PORT) ? ':'.parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_PORT):'';
            $lms_url = parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_SCHEME)
                        .'://'.parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_HOST).$port;
            
            $studio_url = CURRIKI_STUDIO_HOST.'/lti/content/'.urlencode($lms_url).'/'.$lti_client_id.'/'.urlencode($redirect_url);
            if (!empty($custom_email_id)) {
                $studio_url .= '?user_email=' . urlencode($custom_email_id);
            }
            $response = new RedirectResponse($studio_url);
            $response->send();
        }
    }

    public function processtolms()
    {        
        global $CFG;
        $title = urldecode(U::get($_GET, "title"));
        $text =  U::get($_GET, "text");
        $fa_icon = isset($this->tool['FontAwesome']) ? $this->tool['FontAwesome'] : false;
        $presentationDocumentTarget = U::get($_GET, "presentationDocumentTarget");
        $displayWidth = U::get($_GET, "displayWidth");
        $displayHeight = U::get($_GET, "displayHeight");
        $additionalParams = array();
        if ( $presentationDocumentTarget ) {
            $additionalParams['presentationDocumentTarget'] = $presentationDocumentTarget;
            if ( ($presentationDocumentTarget == 'embed' || $presentationDocumentTarget == 'iframe') &&
            $displayWidth && $displayHeight && is_numeric($displayWidth) && is_numeric($displayHeight) ) {
                $additionalParams['placementWidth'] = $displayWidth;
                $additionalParams['placementHeight'] = $displayHeight;
            }
        }
                        
        $icon = CURRIKI_STUDIO_HOST.'/favicon-apple.png';

        // Set up to send the response
        if ( $this->deeplink ) {
            $retval = new DeepLinkResponse($this->deeplink);
        } else {
            $retval = new ContentItem();
        }
        $points = false;
        $activity_id = false;
        if ( isset($this->tool['messages']) && is_array($this->tool['messages']) &&
            array_search('launch_grade', $this->tool['messages']) !== false ) {
            $points = 10;
            $activity_id = $install;
        }
        
        $custom = false;
        $id = U::get($_GET, "id");
        $entity = U::get($_GET, "entity");
        $custom = $id ? [$entity => $id] : null;
        $path = $this->tool['url'] . '?'.$entity.'='.$id;
        $playlist =  U::get($_GET, "playlist");
        if ($entity === 'activity' && $playlist) {
            $path .= "&playlist=$playlist";
        }
        $retval->addLtiLinkItem($path, $title, $text, $icon, $fa_icon, $custom, $points, $activity_id, $additionalParams);

        $iframeattr=false; $endform=false;
        $content = "<center>Redirecting.....</center>";
        $content .= "<style>p,pre {display:none !important;}</style>";
        $content .= $retval->prepareResponse($endform, $this->debug, $iframeattr);
        $content .= " <script type=\"text/javascript\"> \n" .
                    "  //<![CDATA[ \n" .
                    "    document.getElementsByTagName(\""."form"."\")[0].style.display = \"none\";\n" .
                    "    document.getElementsByTagName(\""."form"."\")[0].submit(); \n" .
                    "  //]]> \n" .
                    " </script> \n";
        echo($content);
    }

}

