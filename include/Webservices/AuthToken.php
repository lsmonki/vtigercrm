<?php
	
	function vtws_getchallenge($username){
		
		global $adb;
		
		$user = new Users();
		$userid = $user->retrieve_user_id($username);
		$authToken = uniqid();
		
		$servertime = time();
		$expireTime = time()+(60*5);
		
		$sql = "delete from vtiger_ws_userauthtoken where userid=?";
		$adb->pquery($sql,array($userid));
		
		$sql = "insert into vtiger_ws_userauthtoken(userid,token,expireTime) values (?,?,?)";
		$adb->pquery($sql,array($userid,$authToken,$expireTime));
		
		return array("token"=>$authToken,"serverTime"=>$servertime,"expireTime"=>$expireTime);
	}

?>