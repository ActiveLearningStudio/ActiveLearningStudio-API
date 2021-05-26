<?php

abstract class TalentLMS_ApiResource{

	protected static function _scopedRetrieve($class, $id){
		$url = self::_instanceUrl($class, $id);
		$response = TalentLMS_ApiRequestor::request('get', $url);
		
		return $response;
	}
	
	protected static function _scopedAll($class){
		$url = self::_classUrl($class);
		$response = TalentLMS_ApiRequestor::request('get', $url);
		
		return $response;
	}
	
	protected static function _scopedLogin($class, $params){
		self::_validateCall('login', $class, $params);
		$url = self::_postUrl('login');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
		
		return $response;
	}
	
	protected static function _scopedLogout($class, $params){
		self::_validateCall('logout', $class, $params);
		$url = self::_postUrl('logout');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedSignup($class, $params){
		self::_validateCall('signup', $class, $params);
		$url = self::_postUrl('signup');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedAddUserToCourse($class, $params){
		self::_validateCall('addUser', $class, $params);
		$url = self::_postUrl('addUser');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedRemoveUserFromCourse($class, $params){
		$url = self::_instanceUrlByParams('removeUserFromCourse', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedResetUserProgress($class, $params){
		$url = self::_instanceUrlByParams('resetUserProgress', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGotoCourse($class, $params){
		$url = self::_instanceUrlByParams('gotoCourse', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
		
		return $response;
	}
	
	protected static function _scopedBuyCourse($class, $params){
		self::_validateCall('buyCourse', $class, $params);
		$url = self::_postUrl('buyCourse');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedBuyCategoryCourses($class, $params){
		self::_validateCall('buyCategoryCourses', $class, $params);
		$url = self::_postUrl('buyCategoryCourses');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedRetrieveLeafsAndCourses($class, $id){
		$url = self::_instanceUrlByMethodName('leafsAndCourses', $id);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetCustomRegistrationFields($class){
		$url = self::_classUrlByMethodName('customRegistrationFields');
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedSetUserStatus($class, $params){
		$url = self::_instanceUrlByParams('userSetStatus', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
		
		return $response;
	}
	
	protected static function _scopedSetBranchStatus($class, $params){
		$url = self::_instanceUrlByParams('branchSetStatus', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedAddUserToGroup($class, $params){
		$url = self::_instanceUrlByParams('addUserToGroup', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedRemoveUserFromGroup($class, $params){
		$url = self::_instanceUrlByParams('removeUserFromGroup', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedAddCourseToGroup($class, $params){
		$url = self::_instanceUrlByParams('addCourseToGroup', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedAddUserToBranch($class, $params){
		$url = self::_instanceUrlByParams('addUserToBranch', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedRemoveUserFromBranch($class, $params){
		$url = self::_instanceUrlByParams('removeUserFromBranch', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedAddCourseToBranch($class, $params){
		$url = self::_instanceUrlByParams('addCourseToBranch', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedForgotUsername($class, $params){
		$url = self::_instanceUrlByParams('forgotUsername', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedForgotPassword($class, $params){
		$url = self::_instanceUrlByParams('forgotPassword', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedExtendedUserRetrieve($class, $params){
		$url = self::_instanceUrlByParams('users', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
		
		return $response;
	}
	
	protected static function _scopedGetRateLimit($class){
		$url = self::_instanceUrlByParams('getRateLimit', array());
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetUsersProgressInUnits($class, $params){
		$url = self::_instanceUrlByParams('getUsersProgressInUnits', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetTestAnswers($class, $params){
		$url = self::_instanceUrlByParams('getTestAnswers', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetSurveyAnswers($class, $params){
		$url = self::_instanceUrlByParams('getSurveyAnswers', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetIltSessions($class, $params){
		$url = self::_instanceUrlByParams('getIltSessions', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedCreateCourse($class, $params){
		self::_validateCall('create', $class, $params);
		$url = self::_postUrl('createCourse');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedCreateGroup($class, $params){
		self::_validateCall('create', $class, $params);
		$url = self::_postUrl('createGroup');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedCreateBranch($class, $params){
		self::_validateCall('create', $class, $params);
		$url = self::_postUrl('createBranch');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedEditUser($class, $params){
		self::_validateCall('editUser', $class, $params);
		$url = self::_postUrl('editUser');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedGetUsersByCustomField($class, $params){
		$url = self::_instanceUrlByParams('getUsersByCustomField', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetUserStatusInCourse($class, $params){
		$url = self::_instanceUrlByParams('getUserStatusInCourse', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetCustomCourseFields($class){
		$url = self::_classUrlByMethodName('customCourseFields');
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetCoursesByCustomField($class, $params){
		$url = self::_instanceUrlByParams('getCoursesByCustomField', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedGetTimeline($class, $params){
		$url = self::_instanceUrlByParams('getTimeline', $params);
		$response = TalentLMS_ApiRequestor::request('get', $url);
	
		return $response;
	}
	
	protected static function _scopedDeleteGroup($class, $params){
		self::_validateCall('delete', $class, $params);
		$url = self::_postUrl('deleteGroup');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedDeleteBranch($class, $params){
		self::_validateCall('delete', $class, $params);
		$url = self::_postUrl('deleteBranch');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedDeleteCourse($class, $params){
		self::_validateCall('delete', $class, $params);
		$url = self::_postUrl('deleteCourse');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _scopedDeleteUser($class, $params){
		self::_validateCall('delete', $class, $params);
		$url = self::_postUrl('deleteUser');
		$response = TalentLMS_ApiRequestor::request('post', $url, $params);
	
		return $response;
	}
	
	protected static function _instanceUrl($class, $id){
		$base = self::_classUrl($class);
		$url = $base."/id:".$id;
		
		return $url;
	}
	
	protected static function _classUrl($class){
		$class = str_replace('TalentLMS_', '', $class);
		$class = strtolower($class);
		
		if($class == 'user'){
			return "/users";
		}
		else if($class == 'course'){
			return "/courses";
		}
		else if($class == 'category'){
			return "/categories";
		}
		else if($class == 'branch'){
			return "/branches";
		}
		else if($class == 'group'){
			return "/groups";
		}
		else if($class == 'siteinfo'){
			return "/siteinfo";
		}
	}
	
	protected static function _instanceUrlByMethodName($method, $id){
		$base = self::_classUrlByMethodName($method);
		$url = $base."/id:".$id;
	
		return $url;
	}
	
	protected static function _instanceUrlByParams($method, $params){
		$base = self::_classUrlByMethodName($method);
		$url = $base."/";
		
		foreach($params as $key => $value){
			if($key == 'logout_redirect' || $key == 'course_completed_redirect' || $key == 'redirect_url' || $key == 'domain_url'){
				$url .= $key.':'.base64_encode($value).',';
			}
			else{
				$url .= $key.':'.$value.',';
			}
		}
		
		$url = trim($url, ',');
	
		return $url;
	}
	
	protected static function _classUrlByMethodName($method){
		if($method == 'leafsAndCourses'){
			return "/categoryleafsandcourses";
		}
		else if($method == 'customRegistrationFields'){
			return "/getcustomregistrationfields";
		}
		else if($method == 'userSetStatus'){
			return "/usersetstatus";
		}
		else if($method == 'branchSetStatus'){
			return "/branchsetstatus";
		}
		else if($method == 'gotoCourse'){
			return "/gotocourse";
		}
		else if($method == 'addUserToGroup'){
			return "/addusertogroup";
		}
		else if($method == 'removeUserFromGroup'){
			return "/removeuserfromgroup";
		}
		else if($method == 'addCourseToGroup'){
			return "/addcoursetogroup";
		}
		else if($method == 'addUserToBranch'){
			return "/addusertobranch";
		}
		else if($method == 'removeUserFromBranch'){
			return "/removeuserfrombranch";
		}
		else if($method == 'addCourseToBranch'){
			return "/addcoursetobranch";
		}
		else if($method == 'forgotUsername'){
			return "/forgotusername";
		}
		else if($method == 'forgotPassword'){
			return "/forgotpassword";
		}
		else if($method == 'users'){
			return "/users";
		}
		else if($method == 'getRateLimit'){
			return "/ratelimit";
		}
		else if($method == 'getUsersProgressInUnits'){
			return "/getusersprogressinunits";
		}
		else if($method == 'getTestAnswers'){
			return "/gettestanswers";
		}
		else if($method == 'getSurveyAnswers'){
			return "/getsurveyanswers";
		}
		else if($method == 'getIltSessions'){
			return "/getiltsessions";
		}
		else if($method == 'getUserStatusInCourse'){
			return "/getuserstatusincourse";
		}
		else if($method == 'customCourseFields'){
			return "/getcustomcoursefields";
		}
		else if($method == 'getCoursesByCustomField'){
			return "/getcoursesbycustomfield";
		}
		else if($method == 'getTimeline'){
			return "/gettimeline";
		}
		else if($method == 'removeUserFromCourse'){
			return "/removeuserfromcourse";
		}
		else if($method == 'resetUserProgress'){
			return "/resetuserprogress";
		}
		else if($method == 'getUsersByCustomField'){
			return "/getusersbycustomfield";
		}
	}
	
	protected static function _postUrl($method){
		if($method == 'login'){
			return "/userlogin";
		}
		else if($method == 'logout'){
			return "/userlogout";
		}
		else if($method == 'addUser'){
			return "/addusertocourse";
		}
		else if($method == 'signup'){
			return "/usersignup";
		}
		else if($method == 'buyCourse'){
			return "/buycourse";
		}
		else if($method == 'buyCategoryCourses'){
			return "/buycategorycourses";
		}
		else if($method == 'createCourse'){
			return "/createcourse";
		}
		else if($method == 'createGroup'){
			return "/creategroup";
		}
		else if($method == 'createBranch'){
			return "/createbranch";
		}
		else if($method == 'editUser'){
			return "/edituser";
		}
		else if($method == 'deleteGroup'){
			return "/deletegroup";
		}
		else if($method == 'deleteBranch'){
			return "/deletebranch";
		}
		else if($method == 'deleteCourse'){
			return "/deletecourse";
		}
		else if($method == 'deleteUser'){
			return "/deleteuser";
		}
	}
	
	private static function _validateCall($method, $class, $params=null){
		if($params && !is_array($params)){
			throw new TalentLMS_ApiError("You must pass an array as the first argument to ".$class.'::'.$method."() method calls.");
		}
	}
}