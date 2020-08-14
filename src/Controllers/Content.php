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
        
        //$redirect_url
        //$lti_client_id
        //$lms_url
        //
        //die($lms_url)
        //studio.currki.org/playlist/lti/preview/activity-id/
        //studio.currki.org/lti/content/$lms_url/$lti_client_id/$redirect_url
        
        echo "<h2>CurrikiStudio Projects<h2>";
        echo "<ul>";
            echo '<li><a href="'.$redirect_url.'&title=The Basics Of Investing&projectid=5ed7034deed58f676319990b'.'">The Basics Of Investing</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=Exploring Our National Parks&projectid=5ed87c682ca48c1b4666ceb4'.'">Exploring Our National Parks</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=Globalization Robots And You&projectid=5ed88320e3e1042dc64d7a5e'.'">Globalization Robots And You</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=Electronics Tech. AAS Prep (Year 1)&projectid=5ed8fa36d6a2ad0bd543455f'.'">Electronics Tech. AAS Prep (Year 1)</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=How to Use CurrikiStudio&projectid=5eeae4ab75ce27706d2d18f4'.'">How to Use CurrikiStudio</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=UBS Keys To Your Future Demo&projectid=5eee38a0b51a5323cb2c2592'.'">UBS Keys To Your Future Demo</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=Financial Literacy for Kids&projectid=5efb8f8e7e7d7f10ca210dc3'.'">Financial Literacy for Kids</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=Chemistry - Part I&projectid=5f0df6586e5331109376618f'.'">Chemistry - Part I</a></li>';
            echo '<li><a href="'.$redirect_url.'&title=Pre Algebra&projectid=5f11d0d6863db22c051b3ddc'.'">Pre Algebra</a></li>';
        echo "</ul>";
        echo "<p>" . $lms_url . " | " . $lti_client_id . "</p>";
    }

    public function processtolms()
    {        
        global $CFG;
        $title = U::get($_GET, "title");
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
        $projectid = U::get($_GET, "projectid");
        $custom = $projectid ? ['project' => $projectid] : null;
        $path = $this->tool['url'] . '?project='.$projectid;
        
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

