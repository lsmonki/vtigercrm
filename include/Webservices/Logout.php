<?php
function vtws_logout($sessionId,$user){
	$sessionManager = new SessionManager();
	$sid = $sessionManager->startSession($sessionId);
	
	if(!isset($sessionId) || !$sessionManager->isValid()){
		return $sessionManager->getError();
	}

	$sessionManager->destroy();
//	$sessionManager->setExpire(1);
	return array("message"=>"successfull");

}
?>
