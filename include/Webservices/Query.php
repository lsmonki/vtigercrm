<?php
	require_once("include/Webservices/QueryParser.php");
	
	function vtws_query($q,$user){
		
		global $log,$adb;
		$webserviceObject = VtigerWebserviceObject::fromQuery($adb,$q);
		$handlerPath = $webserviceObject->getHandlerPath();
		$handlerClass = $webserviceObject->getHandlerClass();
		
		require_once $handlerPath;
		
		$handler = new $handlerClass($webserviceObject,$user,$adb,$log);
		$meta = $handler->getMeta();
		
		$types = vtws_listtypes($user);
		if(!in_array($webserviceObject->getEntityName(),$types['types'])){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
		}
		
		if(!$meta->hasReadAccess()){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to read is denied");
		}
		
		$result = $handler->query($q);
		VTWS_PreserveGlobal::flush();
		return $result;
	}
	
?>