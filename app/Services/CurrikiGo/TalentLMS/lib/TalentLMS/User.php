<?php

class TalentLMS_User extends TalentLMS_ApiResource{
	
	public static function retrieve($params){
		$class = get_class();
		
		if(!is_array($params)){	// retrieve by id
			return self::_scopedRetrieve($class, $params);
		}
		else{	// e.g. retrieve by email address
			return self::_scopedExtendedUserRetrieve($class, $params);
		}
	}
	
	public static function all(){
		$class = get_class();
		return self::_scopedAll($class);
	}
	
	public static function delete($params){
		$class = get_class();
		return self::_scopedDeleteUser($class, $params);
	}
	
	public static function login($params){
		$class = get_class();
		return self::_scopedLogin($class, $params);
	}
	
	public static function logout($params){
		$class = get_class();
		return self::_scopedLogout($class, $params);
	}
	
	public static function signup($params){
		$class = get_class();
		return self::_scopedSignup($class, $params);
	}
	
	public static function getCustomRegistrationFields(){
		$class = get_class();
		return self::_scopedGetCustomRegistrationFields($class);
	}
	
	public static function setStatus($params){
		$class = get_class();
		return self::_scopedSetUserStatus($class, $params);
	}
	
	public static function forgotUsername($params){
		$class = get_class();
		return self::_scopedForgotUsername($class, $params);
	}
	
	public static function forgotPassword($params){
		$class = get_class();
		return self::_scopedForgotPassword($class, $params);
	}
	
	public static function edit($params){
		$class = get_class();
		return self::_scopedEditUser($class, $params);
	}
	
	public static function getByCustomField($params){
		$class = get_class();
		return self::_scopedGetUsersByCustomField($class, $params);
	}
}