<?php

class TalentLMS_Siteinfo extends TalentLMS_ApiResource{
	
	public static function get(){
		$class = get_class();
		return self::_scopedAll($class);
	}
	
	public static function getRateLimit(){
		$class = get_class();
		return self::_scopedGetRateLimit($class);
	}
	
	public static function getTimeline($params){
		$class = get_class();
		return self::_scopedGetTimeline($class, $params);
	}
}