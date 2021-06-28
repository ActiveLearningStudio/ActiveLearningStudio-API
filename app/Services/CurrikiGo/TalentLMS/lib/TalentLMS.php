<?php

if(!function_exists('curl_init')){
	throw new Exception('TalentLMS API needs the CURL PHP extension.');
}

if(!function_exists('json_decode')){
	throw new Exception('TalentLMS API needs the JSON PHP extension.');
}

abstract class TalentLMS{
	public static $apiKey;
	public static $domain;
	public static $apiBase;
	public static $apiProtocol;

	const VERSION = '1.0';
	
	public static function getApiKey(){
		return self::$apiKey;
	}
	
	public static function setApiKey($apiKey){
		self::$apiKey = $apiKey;
	}
	
	public static function getDomain(){
		return self::$domain;
	}

	public static function setProtocol($protocol){
		if($protocol == 'http' || $protocol == 'https'){
			self::$apiProtocol = $protocol;
		}
	}
	
	public static function setDomain($domain){
		$domain = str_replace('http://', '', $domain);
		$domain = str_replace('https://', '', $domain);
		$domain = str_replace('/', '', $domain);
		
		self::$domain = $domain;

		if(!isset(self::$apiProtocol) || (self::$apiProtocol != 'http' && self::$apiProtocol != 'https')){
			self::$apiProtocol = 'https';
		}

		self::$apiBase = self::$apiProtocol.'://'.$domain.'/api/v1';
	}
	
	public static function getApiBase(){
		return self::$apiBase;
	}
	
	public static function setApiBase($apiBase){
		self::$apiBase = $apiBase;
	}
}

// Errors
require(dirname(__FILE__).'/TalentLMS/ApiError.php');

// Internal
require(dirname(__FILE__).'/TalentLMS/ApiRequestor.php');
require(dirname(__FILE__).'/TalentLMS/ApiResource.php');

// Resources
require(dirname(__FILE__).'/TalentLMS/User.php');
require(dirname(__FILE__).'/TalentLMS/Course.php');
require(dirname(__FILE__).'/TalentLMS/Category.php');
require(dirname(__FILE__).'/TalentLMS/Branch.php');
require(dirname(__FILE__).'/TalentLMS/Group.php');
require(dirname(__FILE__).'/TalentLMS/Unit.php');
require(dirname(__FILE__).'/TalentLMS/SiteInfo.php');