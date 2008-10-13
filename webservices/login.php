<?php
	require_once("webservices/SessionManager.php");
	require_once("webservices/WebServiceError.php");
	require_once("modules/Users/Users.php");
//	function login(&$state, $uname, $pwd){
//		
//			// Allow for the session information to be passed via the URL for printing.
//			if(isset($_REQUEST['session_name']) && isset($_SESSION["authenticated_user_id"]))
//			{
//				session_id($_REQUEST['session_name']);
//				//Setting the same session id to Forums as in CRM
//			    $sessid=$_REQUEST['session_name'];
//			    
//			}else{
//				
//				$user = new Users();
//				$user->column_fields["user_name"] =  $uname;
//				$user = $user->load_user($pwd);
//			}
//			
//			if(!isset($user)){
//				$state->success = false;
//				$state->msg = "Invalid username or password.";
//				return;
//			}
//			
//			if($user->is_authenticated()){
//				
//				session_start();
//				$sessid = session_id();
//				if(strlen($sessid) == 0){
//					$sessid = session_regenerate_id(false);
//				}
//				
//				$_SESSION["authenticated_user_id"] = $user->id;
//				
//				$state->success = true;
//				$state->msg = "Login Successful";
//				$state->result = Array("sessionid" => $sessid,"userid"=>$user->id);
//				
//			}else{
//				$state->success = false;
//				$state->result = Array('Error'=>"Authorization failure");
//			}
//		}

	function login($username,$pwd){
		
		$user = new Users();
		$userId = $user->retrieve_user_id($username);
		
		$token = getActiveToken($userId);
		if($token == null){
			return new WebServiceError(WebServiceErrorCode::$INVALIDTOKEN,"Specified token is invalid or expired");
		}
		
		$accessKey = getUserAccessKey($userId);
		if($accessKey == null){
			return new WebServiceError(WebServiceErrorCode::$ACCESSKEYUNDEFINED,"Access key for the user is undefined");
		}
		
		$accessCrypt = md5($token.$accessKey);
		if(strcmp($accessCrypt,$pwd)!==0){
			return new WebServiceError(WebServiceErrorCode::$INVALIDUSERPWD,"Invalid username or password");
		}
		$user = $user->retrieveCurrentUserInfoFromFile($userId);
		return $user;
		
	}
	
	function getActiveToken($userId){
		global $adb;
		
		$sql = "select * from vtiger_ws_userauthtoken where userid=? and expiretime >= ?";
		$result = $adb->pquery($sql,array($userId,time()));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				return $adb->query_result($result,0,"token");
			}
		}
		return null;
	}
	
	function getUserAccessKey($userId){
		global $adb;
		
		$sql = "select * from vtiger_users where id=?";
		$result = $adb->pquery($sql,array($userId));
		if($result != null && isset($result)){
			if($adb->num_rows($result)>0){
				return $adb->query_result($result,0,"accesskey");
			}
		}
		return null;
	}
	
?>
