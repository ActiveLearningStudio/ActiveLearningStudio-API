<?php

class TalentLMS_ApiRequestor{
	
	public static function request($method, $url, $params=null){
		list($rbody, $rcode) = self::_requestRaw($method, $url, $params);
		$response = self::_interpretResponse($rbody, $rcode);
		return $response;
	}
	
	private static function _interpretResponse($rbody, $rcode){
		try{
			$response = json_decode($rbody, true);
		}
		catch(Exception $e){
			throw new TalentLMS_ApiError("Invalid response body from API: ".$rbody." (HTTP response code was ".$rcode.")", $rcode, $rbody);
		}
	
		if($rcode < 200 || $rcode >= 300){
			self::_handleApiError($rbody, $rcode, $response);
		}
		
		return $response;
	}
	
	private static function _requestRaw($method, $url, $params){
		$myApiKey = TalentLMS::$apiKey;
		$myApiBase = TalentLMS::$apiBase;

		if(!$myApiKey){
			throw new TalentLMS_ApiError('No API key provided. (HINT: set your API key using "TalentLMS::setApiKey(\'API-KEY\')").');
		}
		
		if(!$myApiBase){
			throw new TalentLMS_ApiError('No domain provided. (HINT: set your domain using "TalentLMS::setDomain(\'DOMAIN\')").');
		}
	
		$absUrl = self::_apiUrl($url);

		$ua = array('bindings_version' => TalentLMS::VERSION,
					'lang' => 'php',
					'lang_version' => phpversion(),
					'publisher' => 'talentlms',
					'uname' => php_uname());
		
		$headers = array('X-Talentlms-Client-User-Agent: '.json_encode($ua),
						'User-Agent: Talentlms/v1 PhpBindings/'.TalentLMS::VERSION);
		
		list($rbody, $rcode) = self::_curlRequest($method, $absUrl, $headers, $params, $myApiKey);
		return array($rbody, $rcode);
	}
	
	protected static function _apiUrl($url=''){
		$apiBase = TalentLMS::$apiBase;
		return $apiBase.$url;
	}
	
	private static function _curlRequest($method, $absUrl, $headers, $params, $myApiKey){
		$curl = curl_init();
		$method = strtolower($method);
		$opts = array();
		
		if($method == 'get'){
			$opts[CURLOPT_HTTPGET] = 1;
		}
		else if($method == 'post'){
			$opts[CURLOPT_POST] = 1;
			
			if($params != null){
				$opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
			}
		}
		else{
			throw new TalentLMS_ApiError("Unsupported method ".$method);
		}
		
		$opts[CURLOPT_URL] = $absUrl;
		$opts[CURLOPT_RETURNTRANSFER] = true;
		$opts[CURLOPT_CONNECTTIMEOUT] = 30;
		$opts[CURLOPT_TIMEOUT] = 80;
		$opts[CURLOPT_RETURNTRANSFER] = true;
		$opts[CURLOPT_HTTPHEADER] = $headers;
		$opts[CURLOPT_USERPWD] = $myApiKey.':';
		$opts[CURLOPT_SSL_VERIFYPEER] = false;
		$opts[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
		
		curl_setopt_array($curl, $opts);
		$rbody = curl_exec($curl);
		
		if($rbody === false || $rbody == FALSE){
			$errno = curl_errno($curl);
			$message = curl_error($curl);
			curl_close($curl);
			self::_handleCurlError($errno, $message);
		}
		
		$rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		return array($rbody, $rcode);
	}
	
	protected static function _handleCurlError($errno, $message){
		$apiBase = TalentLMS::$apiBase;
		
		if($errno == CURLE_COULDNT_CONNECT || $errno == CURLE_COULDNT_RESOLVE_HOST || $errno == CURLE_OPERATION_TIMEOUTED){
			$msg = "Could not connect to TalentLMS (".$apiBase."). Please check your internet connection and try again.";
		}
		else{
			$msg = "Unexpected error while communicating with TalentLMS. Please try again.";
		}
		
		$msg .= "\n\n(Network error: ".$message.")";
		throw new TalentLMS_ApiError($msg);
	}
	
	protected static function _handleApiError($rbody, $rcode, $response){
		if(!is_array($response) || !isset($response['error'])){
			throw new TalentLMS_ApiError("Invalid response object from API: ".$rbody." (HTTP response code was: ".$rcode.")", $rcode, $rbody, $response);
		}

		throw new TalentLMS_ApiError(isset($response['error']['message']) ? $response['error']['message'] : null, $rcode, $rbody, $response);
	}
}