<?php
	
	function vtws_create($elementType, $element, $user){
		
		$crmObject = new VtigerCRMObject($elementType, false);
		
		$types = vtws_listtypes($user);
		if(!in_array($elementType,$types['types'])){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
		}
		
		$meta = new VtigerCRMObjectMeta($crmObject,$user);
		$meta->retrieveMeta();
		
		if(!$meta->hasAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to access object type is denied");
		}
		if(!$meta->hasWriteAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to write is denied");
		}
		
		$referenceFields = $meta->getReferenceFieldDetails();
		foreach($referenceFields as $fieldName=>$details){
			if(isset($element[$fieldName]) && strlen($element[$fieldName]) > 0){
				$ids = getIdComponents($element[$fieldName]);
				$elemTypeId = $ids[0];
				$elemId = $ids[1];
				$referenceObject = new VtigerCRMObject($elemTypeId,true);
				if(!in_array($referenceObject->getModuleName(),$details)){
					return new WebServiceError(WebServiceErrorCode::$REFERENCEINVALID,
						"Invalid reference specified for $fieldName");
				}
				$referenceMeta = new VtigerCRMObjectMeta($referenceObject,$user);
				if(!$referenceMeta->hasAccess()){
					return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,
						"Permission to access reference type is denied");
				}
			}else if($element[$fieldName] !== NULL){
				unset($element[$fieldName]);
			}
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