<?php

class TalentLMS_Group extends TalentLMS_ApiResource{
	
	public static function create($params){
		$class = get_class();
		return self::_scopedCreateGroup($class, $params);
	}
	
	public static function retrieve($id){
		$class = get_class();
		return self::_scopedRetrieve($class, $id);
	}
	
	public static function all(){
		$class = get_class();
		return self::_scopedAll($class);
	}
	
	public static function delete($params){
		$class = get_class();
		return self::_scopedDeleteGroup($class, $params);
	}
	
	public static function addUser($params){
		$class = get_class();
		return self::_scopedAddUserToGroup($class, $params);
	}
	
	public static function removeUser($params){
		$class = get_class();
		return self::_scopedRemoveUserFromGroup($class, $params);
	}
	
	public static function addCourse($params){
		$class = get_class();
		return self::_scopedAddCourseToGroup($class, $params);
	}
}