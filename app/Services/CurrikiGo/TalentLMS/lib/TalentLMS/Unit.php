<?php

class TalentLMS_Unit extends TalentLMS_ApiResource{
	
	public static function getUsersProgress($params){
		$class = get_class();
		return self::_scopedGetUsersProgressInUnits($class, $params);
	}
	
	public static function getTestAnswers($params){
		$class = get_class();
		return self::_scopedGetTestAnswers($class, $params);
	}
	
	public static function getSurveyAnswers($params){
		$class = get_class();
		return self::_scopedGetSurveyAnswers($class, $params);
	}
	
	public static function getIltSessions($params){
		$class = get_class();
		return self::_scopedGetIltSessions($class, $params);
	}
}