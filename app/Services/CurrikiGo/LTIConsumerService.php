<?php

namespace App\Services\CurrikiGo;

use App\Services\CurrikiGo\LTIConsumerServiceInterface;

/**
 * LTI 1.0 consumer
 */
class LTIConsumerService implements LTIConsumerServiceInterface
{

    protected $key;
    protected $secret;
    protected $launchUrl;
	
    const LTI_VERSION = 'LTI-1p0';
    const LTI_MESSAGE_TYPE = 'basic-lti-launch-request';

    /**
     * Initialize
     * 
     * @param string $key
     * @param string $secret
     * @param string $launchUrl
     * @return void
     */
    public function __construct($key, $secret, $launchUrl)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->launchUrl = $launchUrl;
    }

    /**
     * Launch LTI 1.0 request.
     * 
     * @param array $launchData
     * @return string
     */
    public function launch($launchData)
    {
    	// ------------------------------
        // START CONFIGURATION SECTION
        //

        $launchUrl = $this->launchUrl;
        $key = $this->key;
        $secret = $this->secret;

        // Put these in env.
        $params = [
            'tool_consumer_instance_contact_email' => config('constants.tool-consumer-email'),
            "tool_consumer_instance_guid" => config('constants.tool-consumer-guid'),
            "tool_consumer_instance_description" => config('constants.tool-consumer-description'),
            'tool_consumer_info_product_family_code' => config('constants.tool-consumer-family-code'),
            'tool_consumer_info_version' => config('constants.tool-consumer-version'),
            'ext_submit' => 'Loading...'
        ];
        $launchData = array_merge($launchData, $params);
        
        //
        // END OF CONFIGURATION SECTION
        // ------------------------------

        $now = new \DateTime();

        $launchData["lti_version"] = self::LTI_VERSION;
        $launchData["lti_message_type"] = self::LTI_MESSAGE_TYPE;

        // Basic LTI uses OAuth to sign requests
        // OAuth Core 1.0 spec: http://oauth.net/core/1.0/

        $launchData["oauth_callback"] = "about:blank";
        $launchData["oauth_consumer_key"] = $key;
        $launchData["oauth_version"] = "1.0";
        $launchData["oauth_nonce"] = uniqid('', true);
        $launchData["oauth_timestamp"] = $now->getTimestamp();
        $launchData["oauth_signature_method"] = "HMAC-SHA1";

        // In OAuth, request parameters must be sorted by name
        $launchDataKeys = array_keys($launchData);
        sort($launchDataKeys);

        $launchParams = array();
        foreach ($launchDataKeys as $key) {
            array_push($launchParams, $key . "=" . rawurlencode($launchData[$key]));
        }
        
        $baseString = "POST&" . urlencode($launchUrl) . "&" . rawurlencode(implode("&", $launchParams));
        $secret = urlencode($secret) . "&";
        $signature = base64_encode(hash_hmac("sha1", $baseString, $secret, true));

        $output = '
        <form id="ltiLaunchForm" name="ltiLaunchForm" method="POST" action="' . $launchUrl . '" encType="application/x-www-form-urlencoded">';
        foreach ($launchData as $k => $v ) { 
            $output .= '<input type="hidden" name="' . $k .'" value="' . ($k === 'custom_safarimontage_upload_metadata' ? html_escape($v) : $v) .'">';
        }
        $output .= <<<HTML
<input type="hidden" name="oauth_signature" value="{$signature}">
<input type="submit" name="ext_submit" value="Loading..." style="border:0; background-color: inherit;">
</form>
<script type="text/javascript"> 
//<![CDATA[ 
    document.getElementById("ltiLaunchForm").submit();
//]]> 
</script>
HTML;
        return $output;
    }
    
}
