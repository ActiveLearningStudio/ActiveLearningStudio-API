<?php

class TalentLMS_Branch extends TalentLMS_ApiResource{
	
	public static function create($params){
		$class = get_class();
		return self::_scopedCreateBranch($class, $params);
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
		return self::_scopedDeleteBranch($class, $params);
	}
	
	public static function addUser($params){
		$class = get_class();
		return self::_scopedAddUserToBranch($class, $params);
	}
	
	public static function removeUser($params){
		$class = get_class();
		return self::_scopedRemoveUserFromBranch($class, $params);
	}
	
	public static function addCourse($params){
		$class = get_class();
		return self::_scopedAddCourseToBranch($class, $params);
	}
	
	public static function setStatus($params){
		$class = get_class();
		return self::_scopedSetBranchStatus($class, $params);
	}
}