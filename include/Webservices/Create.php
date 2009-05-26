<?php
	
	function vtws_create($elementType, $element, $user){
		
		$types = vtws_listtypes($user);
		if(!in_array($elementType,$types['types'])){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to perform the operation is denied");
		}
		
		global $log,$adb;
		$webserviceObject = VtigerWebserviceObject::fromName($adb,$elementType);
		$handlerPath = $webserviceObject->getHandlerPath();
		$handlerClass = $webserviceObject->getHandlerClass();
		
		require_once $handlerPath;
		
		$handler = new $handlerClass($webserviceObject,$user,$adb,$log);
		$meta = $handler->getMeta();
		if($meta->hasWriteAccess()!==true){
			throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,"Permission to write is denied");
		}
		
		$referenceFields = $meta->getReferenceFieldDetails();
		foreach($referenceFields as $fieldName=>$details){
			if(isset($element[$fieldName]) && strlen($element[$fieldName]) > 0){
				$ids = vtws_getIdComponents($element[$fieldName]);
				$elemTypeId = $ids[0];
				$elemId = $ids[1];
				$referenceObject = VtigerWebserviceObject::fromId($adb,$elemTypeId);
				if(!in_array($referenceObject->getEntityName(),$details)){
					throw new WebServiceException(WebServiceErrorCode::$REFERENCEINVALID,
						"Invalid reference specified for $fieldName");
				}
				if(!in_array($referenceObject->getEntityName(),$types['types'])){
					throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED,
						"Permission to access reference type is denied".$referenceObject->getEntityName());
				}
			}else if($element[$fieldName] !== NULL){
				unset($element[$fieldName]);
			}
		}
		
		$meta->hasMandatoryFields($element);
		
		$ownerFields = $meta->getOwnerFields();
		if(is_array($ownerFields) && sizeof($ownerFields) >0){
			foreach($ownerFields as $ownerField){
				if(isset($element[$ownerField]) && $element[$ownerField]!==null && 
					!$meta->hasAssignPrivilege($element[$ownerField])){
					throw new WebServiceException(WebServiceErrorCode::$ACCESSDENIED, "Cannot assign record to the given user");
				}
			}
		}
		$entity = $handler->create($elementType,$element);
		VTWS_PreserveGlobal::flush();
		return $entity;
	}
?>