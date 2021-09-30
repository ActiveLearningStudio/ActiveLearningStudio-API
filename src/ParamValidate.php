<?php
namespace CurrikiTsugi;

use \Tsugi\Util\LTIConstants;

class ParamValidate
{
    public static function playlistInCustom($session)
    {
        if (isset($session['tsugi_jwt'])) {
            $lti_jwt = $session['tsugi_jwt'];
            $lti_claim_custom_url = "https://purl.imsglobal.org/spec/lti/claim/custom";
            $lti_claim_custom = $lti_jwt->body->{$lti_claim_custom_url};
            return property_exists($lti_claim_custom, 'playlist') ? $lti_claim_custom->playlist : null;
        }
        return null;
    }

    public static function playlistInQueryString($session)
    {
        return isset($_SESSION['lti_post']) && isset($_SESSION['lti_post']['playlist']) ? $_SESSION['lti_post']['playlist'] : null;
    }

    public static function projectInCustom($session)
    {
        if (isset($session['tsugi_jwt'])) {
            $lti_jwt = $session['tsugi_jwt'];
            $lti_claim_custom_url = "https://purl.imsglobal.org/spec/lti/claim/custom";
            $lti_claim_custom = $lti_jwt->body->{$lti_claim_custom_url};
            return property_exists($lti_claim_custom, 'project') ? $lti_claim_custom->project : null;
        }
        return null;
    }

    public static function projectInQueryString($session)
    {
        return isset($_SESSION['lti_post']) && isset($_SESSION['lti_post']['project']) ? $_SESSION['lti_post']['project'] : null;
    }

    public static function activityInCustom($session)
    {
        if (isset($session['tsugi_jwt'])) {
            $lti_jwt = $session['tsugi_jwt'];
            $lti_claim_custom_url = "https://purl.imsglobal.org/spec/lti/claim/custom";
            $lti_claim_custom = $lti_jwt->body->{$lti_claim_custom_url};
            return property_exists($lti_claim_custom, 'activity') ? $lti_claim_custom->activity : null;
        }
        return null;
    }

    public static function activityInQueryString($session)
    {
        return isset($_SESSION['lti_post']) && isset($_SESSION['lti_post']['activity']) ? $_SESSION['lti_post']['activity'] : null;
    }

    public static function toolPlatformInfo($session)
    {
        if (isset($session['tsugi_jwt'])) {
            $lti_jwt = $session['tsugi_jwt'];
            $lti_claim_tool_platform = "https://purl.imsglobal.org/spec/lti/claim/tool_platform";
            $tool_family_code = $lti_jwt->body->{$lti_claim_tool_platform};
            return property_exists($tool_family_code, 'product_family_code') ? $tool_family_code->product_family_code : null;
        } else {
            // get from LTI post
            if (isset($session['lti_post'])) {
                return $session['lti_post'][LTIConstants::TOOL_CONSUMER_INFO_PRODUCT_FAMILY_CODE];
            }
        }
        return null;
    }

    public static function isCanvas($session) {
        $tool_family_code = self::toolPlatformInfo($session);
        return $tool_family_code === 'canvas'; 
    }

    public static function isMoodle($session) {
        $tool_family_code = self::toolPlatformInfo($session);
        return $tool_family_code === 'moodle'; 
    }

    public static function getCustomFieldsInfo($session)
    {
        if (isset($session['tsugi_jwt'])) {
            $lti_jwt = $session['tsugi_jwt'];
            $lti_claim_custom = "https://purl.imsglobal.org/spec/lti/claim/custom";
            return property_exists($lti_jwt->body, $lti_claim_custom) ? $lti_jwt->body->{$lti_claim_custom} : null;
        }
        return null;
    }

    public static function getKeyInCustomFields($session, $key) {
        if (isset($session['lti_post']['lti_version']) && $session['lti_post']['lti_version'] == "LTI-1p0") {
            $key = !empty($key) ? 'custom_' . $key : null;
            if (array_key_exists($key, $session['lti_post'])) {
                return $session['lti_post'][$key];
            }
            return null;
        }
        $custom_fields = self::getCustomFieldsInfo($session);
        return ($custom_fields && property_exists($custom_fields, $key)) ? $custom_fields->{$key} : null;
    }

    public static function toolPlatformElement($session, $element='')
    {
        if (isset($session['tsugi_jwt'])) {
            $lti_jwt = $session['tsugi_jwt'];
            $lti_claim_tool_platform = "https://purl.imsglobal.org/spec/lti/claim/tool_platform";
            $tool_family_code = $lti_jwt->body->{$lti_claim_tool_platform};
            return property_exists($tool_family_code, $element) && !empty($element) ? $tool_family_code->$element : $tool_family_code->product_family_code;
        } else {
            // get from LTI post
            if (isset($session['lti_post'])) {
                return $session['lti_post'][LTIConstants::TOOL_CONSUMER_INFO_PRODUCT_FAMILY_CODE];
            }
        }
        return null;
    }

}
