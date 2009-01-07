<?php
	function vtws_extendSession(){
		global $adb,$API_VERSION,$application_unique_key;
		if(isset($_SESSION["authenticated_user_id"]) && $_SESSION["app_unique_key"] == $application_unique_key){
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