<?php
	require_once("webservice.php");
	require_once("webservices/VtigerCRMObject.php");
	require_once("webservices/VtigerCRMObjectMeta.php");
	require_once("webservices/DataTransform.php");
	require_once("webservices/WebServiceError.php");
	require_once 'webservices/ModuleTypes.php';
	
	function retrieve($id, $user){
		
		$ids = getIdComponents($id);
		$elemTypeId = $ids[0];
		$elemid = $ids[1];
		
		if(!$elemTypeId || !$elemid){
			return new WebServiceError(WebServiceErrorCode::$INVALIDID,"Id specified is incorrect");
		}
		
		$crmObject = new VtigerCRMObject($elemTypeId, true);
		
		$seType = $crmObject->getSEType($elemid);
		$types = listtypes($user);
		if(!in_array($seType,$types['types'])){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
		}
		if($crmObject->getModuleName() != $seType){
			return new WebServiceError(WebServiceErrorCode::$INVALIDID,"Id specified is incorrect");
		}
		
		$meta = new VtigerCRMObjectMeta($crmObject,$user);
		$meta->retrieveMeta();
		
		if(!$meta->hasAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to access object type is denied");
		}
		if(!$meta->hasReadAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to read is denied");
		}
		if(!$meta->hasPermission(VtigerCRMObjectMeta::$RETRIEVE,$elemid)){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to read given object is denied");
		}
		
		if(!$crmObject->exists($elemid)){
			return new WebServiceError(WebServiceErrorCode::$RECORDNOTFOUND,"Record you are trying to access is not found");
		}
		
		$error = $crmObject->read($elemid);
		if(!$error){
			return new WebServiceError(WebServiceErrorCode::$DATABASEQUERYERROR,"Database error while performing required operation");
		}
		
		return DataTransform::filterAndSanitize($crmObject->getFields(),$meta);
		
	}
	
?>
