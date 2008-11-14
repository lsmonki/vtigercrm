<?php
	
	function vtws_update($element,$user){
		
		$ids = getIdComponents($element["id"]);
		$elemTypeId = $ids[0];
		$elemid = $ids[1];
		
		if(!$elemTypeId || !$elemid){
			return new WebServiceError(WebServiceErrorCode::$INVALIDID,"Id specified is incorrect");
		}
		
		$crmObject = new VtigerCRMObject($elemTypeId, true);
		
		$seType = $crmObject->getSEType($elemid);
		$types = vtws_listtypes($user);
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
		if(!$meta->hasWriteAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to write is denied");
		}
		if(!$meta->hasPermission(VtigerCRMObjectMeta::$UPDATE,$elemid)){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to update given object is denied");
		}
		
		if(!$crmObject->exists($elemid)){
			return new WebServiceError(WebServiceErrorCode::$RECORDNOTFOUND,"Record you are trying to access is not found");
		}
		
		$element = DataTransform::sanitizeForInsert($element,$meta);
		if(!$meta->hasAssignPrivilege($element["assigned_user_id"])){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED, "Cannot assign record to the given user");
		}
		
		$crmObject->setObjectId($elemid);
		$error = $crmObject->update($element);
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