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
        $lti_client_id = $_SESSION['lti']['issuer_client'];
        $lti13_deeplink = $_SESSION['lti']['lti13_deeplink'];
        $port = parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_PORT) ? ':'.parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_PORT):'';
        $lms_url = parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_SCHEME)
                    .'://'.parse_url($lti13_deeplink->deep_link_return_url, PHP_URL_HOST).$port;
        
        $studio_url = CURRIKI_STUDIO_HOST.'/lti/content/'.urlencode($lms_url).'/'.$lti_client_id.'/'.urlencode($redirect_url);
        $response = new RedirectResponse($studio_url);
        $response->send();
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
        $icon = false;
        if ( $fa_icon !== false ) {
            $icon = $CFG->fontawesome.'/png/'.str_replace('fa-','',$fa_icon).'.png';
        }                

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
        $parentid = U::get($_GET, "parentid");
        
        $custom = $id ? [$entity => $id] : null;
        $path = $this->tool['url'] . '?'.$entity.'='.$id;
        
        if($entity == 'activity' && $parentid){
            $custom['playlist'] = $parentid;
            $path .= '&playlist='.$parentid;
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

