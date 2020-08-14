<?php
namespace CurrikiTsugi;

class ParamValidate
{
    public static function playlistInCustom($session)
    {
        $lti_jwt = $session['tsugi_jwt'];
        $lti_claim_custom_url = "https://purl.imsglobal.org/spec/lti/claim/custom";
        $lti_claim_custom = $lti_jwt->body->{$lti_claim_custom_url};
        return property_exists($lti_claim_custom, 'playlist') ? $lti_claim_custom->playlist : null;
    }

    public static function playlistInQueryString($session)
    {
        return isset($_SESSION['lti_post']) && isset($_SESSION['lti_post']['playlist']) ? $_SESSION['lti_post']['playlist'] : null;
    }

    public static function projectInCustom($session)
    {
        $lti_jwt = $session['tsugi_jwt'];
        $lti_claim_custom_url = "https://purl.imsglobal.org/spec/lti/claim/custom";
        $lti_claim_custom = $lti_jwt->body->{$lti_claim_custom_url};
        return property_exists($lti_claim_custom, 'project') ? $lti_claim_custom->project : null;
    }

    public static function projectInQueryString($session)
    {
        return isset($_SESSION['lti_post']) && isset($_SESSION['lti_post']['project']) ? $_SESSION['lti_post']['project'] : null;
    }

    public static function activityInCustom($session)
    {
        $lti_jwt = $session['tsugi_jwt'];
        $lti_claim_custom_url = "https://purl.imsglobal.org/spec/lti/claim/custom";
        $lti_claim_custom = $lti_jwt->body->{$lti_claim_custom_url};
        return property_exists($lti_claim_custom, 'activity') ? $lti_claim_custom->activity : null;
    }

    public static function activityInQueryString($session)
    {
        return isset($_SESSION['lti_post']) && isset($_SESSION['lti_post']['activity']) ? $_SESSION['lti_post']['activity'] : null;
    }


}
