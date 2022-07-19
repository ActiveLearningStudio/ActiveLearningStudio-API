<?php

namespace App\Services\CurrikiGo\SchoologyLMS;
use Illuminate\Support\Facades\Config;
use stdClass;
/**
 * LTI 1.0 consumer
 */
class SchoologyOauthService implements SchoologyOauthServiceInterface
{
    protected $oauth_signature;
    protected $oauth_consumer_key;
    private $token_secret = '';
	private $api_supported_methods = array('POST','GET','PUT','DELETE','OPTIONS');
    private $lmsSetting;
    private $client;
    private $curl_resource;
    private $curl_opts = array(
        CURLOPT_USERAGENT => 'schoology-php-1.0',
        CURLOPT_CONNECTTIMEOUT => 20,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 60,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_HEADER => TRUE,
        CURLOPT_FOLLOWLOCATION => FALSE, 
        CURLOPT_COOKIESESSION => FALSE,
      );

    /**
     * Initialize
     */
    public function __construct()
    {
        $this->oauth_nonce = uniqid();
        $this->oauth_timestamp = time();
        $this->oauth_signature = Config::get('constants.schoology.oauth_signature');
        $this->oauth_consumer_key = Config::get('constants.schoology.oauth_consumer_key');
        $this->authorization = Config::get('constants.schoology.authorization');
        $this->auth_token = Config::get('constants.schoology.auth_token');
        $this->oauth_signature_method = Config::get('constants.schoology.oauth_signature_method');
        $this->oauth_version = Config::get('constants.schoology.oauth_version');
        $this->realm = Config::get('constants.schoology.realm');
    }

    /**
     * Make a schoology API Call
     */
    public function makePayload( $url , $method)
    {
        $body = array();

        if(!in_array($method,$this->api_supported_methods)){
            return $method.' is not supported. Must be '.implode(',',$this->api_supported_methods);
        }
        $body[] = 'Authorization: '.$this->_makeOauthHeaders( $url , $method , $body );
        // $response = $this->_curlRequest( $url , $method , $body , $extra_headers );
        
        $return = new stdClass();
        $return->url = $url;
        $return->method = $method;
        $return->body = $body;

        return $return;
    }

    public function _makeOauthHeaders( $url = '' , $method = '' , $body = '' )
    {
        $oauth_config = array(
            "realm" => $this->realm,
            'oauth_consumer_key' => $this->oauth_consumer_key,
            'oauth_nonce' => $this->oauth_nonce,
            'oauth_signature_method' => $this->oauth_signature_method,
            'oauth_timestamp' => $this->oauth_timestamp,
            'oauth_token' => $this->auth_token,
            'oauth_version' => $this->oauth_version,
        );
        $oauth_config['oauth_signature'] = $this->_makeOauthSig( $url , $method , $oauth_config );
        $oauth_headers = array();
        foreach($oauth_config as $k=>$v){
            $oauth_headers[] = "{$k}=\"{$v}\"";
        }
        return "OAuth realm=\"\", ".implode(", ",$oauth_headers);   
    }

    public function _makeOauthSig( $url = '' , $method = '' , &$oauth_config = '' )
    {
        $oauth_str = $this->_urlencode($this->oauth_signature).'&'.$this->_urlencode($this->token_secret);
        return $oauth_str;
    }

    public function _urlencode ( $s )
    {
        return str_replace('%7E', '~', rawurlencode($s));
    }

    public function sendCurlRequest($serviceResponse, $data, $sisId, $dataType)
    {        
        // dd($serviceResponse);
        $curl_resource = curl_init();    
        $curl_options = $this->curl_opts;
        $curl_options[ CURLOPT_URL ] = $serviceResponse->url;
    
        $body = array();
        if($serviceResponse->method === "POST")
        {
            if($dataType == "course"){
                $body = [
                    "title" => $data->name,
                    "course_code" => $sisId,
                    "department" => '',
                    "description" => $data->description
                ];
            }
            if($dataType == "section"){
                $body = [
                    "title" => "Curriki Playlist",
                    "section_school_code" => $data->id,
                    "description" => '',
                    "grading_periods" => 947779
                ];
            }
        }
        
        switch($serviceResponse->method){
        case 'POST': 
            $curl_options[ CURLOPT_POST ] = TRUE; 
            $curl_options[ CURLOPT_CUSTOMREQUEST ] = 'POST';
            break;
        case 'PUT': 
            $curl_options[ CURLOPT_CUSTOMREQUEST ] = 'PUT'; 
            break;
        case 'DELETE': 
            $curl_options[ CURLOPT_CUSTOMREQUEST ] = 'DELETE'; 
            break;
        case 'GET': 
            $curl_options[ CURLOPT_HTTPGET ] = TRUE; 
            $curl_options[ CURLOPT_CUSTOMREQUEST ] = 'GET';
            break;
        }
        $both = array_merge( $serviceResponse->body , $body );
    // dd($serviceResponse);
        // dd($both);
        if(is_array($both ))
            $json_body = json_encode( $both );
            // dd($json_body);
            // $json_body 

        $curl_options[ CURLOPT_POSTFIELDS ] = $json_body;
        $content_length = isset($json_body) ? strlen($json_body) : '0';
        $http_headers = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'Content-Length: ' . $content_length
        );
    
        
        // $curl_headers['body'] = json_encode($curl_headers['body']);
        $curl_headers = array_merge( $http_headers , $both );
        

        $curl_options[ CURLOPT_HTTPHEADER ] = $curl_headers;


        curl_setopt_array( $curl_resource , $curl_options );
        // dd( $curl_options );
        $result = curl_exec($curl_resource);
        // dd($result);

        if ($result === false ) {
            return false;
        }
    
        return $this->_getApiResponse($curl_resource, $result);
    }

    public function _getApiResponse($curl_resource, $result)
    {
        $response = (object)curl_getinfo( $curl_resource );
        $response->headers = $this->_parseHttpHeaders(mb_substr($result, 0, $response->header_size));
        $body = mb_substr($result, $response->header_size);
        $response->raw_result = $body;
        
        $response->result = is_string($result) ? json_decode(trim($body)) : '';
        // If no result decoded and the body length is > 0, the reponse was not in JSON. Return the raw body.
        if(is_null($response->result) && $response->size_download > 0){
        $response->result = $body;
        }
        return $response;
    }
    public function _parseHttpHeaders($header){
        $retVal = array();
        $fields = explode("\r\n", preg_replace('/\x0D\x0A[\x09\x20]+/', ' ', $header));
        foreach( $fields as $field ) {
          if( preg_match('/([^:]+): (.+)/m', $field, $match) ) {
            $callback = function($tmp){ 
                return strtoupper($tmp[0]); 
            };
            $match[1] = preg_replace_callback('/(?<=^|[\x09\x20\x2D])./', 
                    $callback,
                    strtolower(trim($match[1])));
            if( isset($retVal[$match[1]]) ) {
              $retVal[$match[1]] = array($retVal[$match[1]], $match[2]);
            } else {
              $retVal[$match[1]] = trim($match[2]);
            }
          }
        }
        return $retVal;
      }

      public function searchCourseFromSchoology($sisId, $project)
    {
        $project->name = "The Lion, the Witch and the Wardrobe";
        $sisId = "The Lion, the Witch and the Wardrobe";
        $url = "https://api.schoology.com/v1/search?keywords={$project->name}&type=course";

        $serviceNewObj = new SchoologyOauthService;
        $serviceResponse = $serviceNewObj->makePayload($url, 'GET');
        $response = $serviceNewObj->sendCurlRequest($serviceResponse, $project->id, $sisId, '');
        $decodedResponse = json_decode($response->raw_result);

        if(isset($decodedResponse->courses->search_result[0]->course_title) === $sisId){
            return $decodedResponse->courses->search_result[0];
        }
        return false;
    }
    
}
