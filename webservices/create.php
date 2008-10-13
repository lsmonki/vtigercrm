<?php
	require_once("webservices/DataTransform.php");
	require_once("webservices/VtigerCRMObject.php");
	require_once("webservices/VtigerCRMObjectMeta.php");
	require_once("webservices/WebServiceError.php");
	require_once 'webservices/ModuleTypes.php';

//	function create(&$state, $element, $moduleName,$user){
//		
//		global $recordString, $focus, $zjson;
//		
//		if(strlen($element)=== 0 || sizeof($element)=== 0 || $element === null || strcasecmp($element, "")===0){
//			$state->success = false;
//			$state->msg = "No Data Provided";
//			return;
//		}
//		
//		$meta = new ObjectMetaData($moduleName,$user);
//		$meta->retrieveMeta();
//		
//		if(!$meta->hasAccess() || !$meta->hasWriteAccess()){
//			
//			$state->success = false;
//			$state->msg = "Access Denied";
//			return;
//		}
//		
//		$focus = getInstance($moduleName);
//		
//		$element = sanitizeForInsert($element,$meta);
//		
//		if(!hasMandatoryColumns($element,$meta)){
//			$state->success = false;
//			$state->msg = "Mandatory Fields Missing";
//			return;
//		}
//		
//		foreach($element as $k=>$v){
//			$focus->column_fields[$k] = $v;
//		}
//		
//		$focus->save($moduleName);
//		
//		$focus->retrieve_entity_info($focus->id, $moduleName);
//		
//		$state->result = filterAndSanitize($focus->column_fields,$meta);
//		$state->success = true;
//		$state->msg = "create complete";
//		
//	}
	
	function create($elementType, $element, $user){
		
		$crmObject = new VtigerCRMObject($elementType, false);
		$meta = new VtigerCRMObjectMeta($crmObject,$user);
		$meta->retrieveMeta();
		
		if(!$meta->hasAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to access object type is denied");
		}
		if(!$meta->hasWriteAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to write is denied");
		}
		
		$types = listtypes($user);
		if(!in_array($elementType,$types['types'])){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
		}
		
		$element = DataTransform::sanitizeForInsert($element,$meta);
		
		if(!$meta->hasMandatoryFields($element)){
			return new WebServiceError(WebServiceErrorCode::$MANDFIELDSMISSING,"Mandatory fields not specified");
		}
		if($element["assigned_user_id"]!=null && !$meta->hasAssignPrivilege($element["assigned_user_id"])){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED, "Cannot assign record to the given user");
		}
		
		
		$error = $crmObject->create($element);
		if(!$error){
			return new WebServiceError(WebServiceErrorCode::$DATABASEQUERYERROR,"Database error while performing required operation");
		}
		
		$id = $crmObject->getObjectId();
		
		$error = $crmObject->read($id);
		if(!$error){
			return new WebServiceError(WebServiceErrorCode::$DATABASEQUERYERROR,"Database error while performing required operation");
		}
		
		return DataTransform::filterAndSanitize($crmObject->getFields(),$meta);
		
	}
	
?>