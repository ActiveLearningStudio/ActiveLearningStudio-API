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
}
