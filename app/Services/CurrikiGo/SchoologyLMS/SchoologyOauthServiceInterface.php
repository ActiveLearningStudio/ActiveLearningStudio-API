<?php

namespace App\Services\CurrikiGo\SchoologyLMS;

/**
 * Interface for the Generic LMS Integration Service
 */
interface SchoologyOauthServiceInterface
{
    /**
     * Make a schoology API Call
     */
    public function makePayload($url , $method);

    /**
     * Make a schoology API Call
     */
    public function _makeOauthHeaders( $url = '' , $method = '' , $body = '' );

    /**
     * Make a schoology API Call
     */
    public function _makeOauthSig( $url = '' , $method = '' , &$oauth_config = '' );

    /**
     * Make a schoology API Call
     */
    public function _urlencode ( $s );

    /**
     * Make a schoology API Call
     */

    public function sendCurlRequest($serviceResponse, $data, $sisId, $dataType);
    /**
     * Make a schoology API Call
     */

    public function _getApiResponse($curl_resource, $result);
    /**
     * Make a schoology API Call
     */

    public function _parseHttpHeaders($header);
    public function searchCourseFromSchoology($sisId, $project);
    
    
}
