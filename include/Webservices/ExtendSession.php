<?php
	function vtws_extendSession(){
		global $adb,$API_VERSION;
		if(isset($_SESSION["authenticated_user_id"])){
			$userId = $_SESSION["authenticated_user_id"];
			$sessionManager = new SessionManager();
			$sessionManager->set("authenticatedUserId", $userId);
			$crmObject = new VtigerCRMObject("Users");
			$userId = getId($crmObject->getModuleId(),$userId);
			$vtigerVersion = vtws_getVtigerVersion();
			$resp = array("sessionId"=>$sessionManager->getSessionId(),"userId"=>$userId,"version"=>$API_VERSION,"vtigerVersion"=>$vtigerVersion);
			return $resp;
		}else{
			return new WebServiceError(WebServiceErrorCode::$AUTHFAILURE,"Authencation Failed");
		}
	}
?>