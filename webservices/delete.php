<?php
	
	require_once("webservices/VtigerCRMObject.php");
	require_once("webservices/VtigerCRMObjectMeta.php");
	require_once("webservices/DataTransform.php");
	require_once 'webservices/ModuleTypes.php';

//	function delete(&$state, $tParser,$user){
//		
//		global $focus;
//		
//		includeModule($tParser->tablename);
//		$focus = new $tParser->tablename();
//		
//		$meta = new ObjectMetaData($tParser->tablename,$user);
//		$meta->retrieveMeta();
//		
//		if(!$meta->hasAccess() || !$meta->hasDeleteAccess()){
//			
//			$state->success = false;
//			$state->msg = "Access Denied";
//			return;
//		}else if(!$meta->hasPermission(ObjectMetaData::$DELETE,$tParser->elementId)){
//			$state->success = false;
//			$state->msg = "Access to Object Denied";
//			return;
//		}
//		//TODO deleteentity error checking not there so need to do it manually.
//		DeleteEntity($tParser->tablename, $tParser->tablename, $focus, $tParser->elementId,$returnid);
//		
//		$state->success = true;
//		$state->msg = "delete complete";
//		$state->result = Array("status"=>"successful");
//		
//	}
	
	function delete($id,$user){

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
		}else if(!$meta->hasWriteAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to write is denied");
		}else if(!$meta->hasPermission(VtigerCRMObjectMeta::$DELETE,$elemid)){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to delete given object is denied");
		}
		
		if(!$crmObject->exists($elemid)){
			return new WebServiceError(WebServiceErrorCode::$RECORDNOTFOUND,"Record you are trying to access is not found");
		}
		
		$error = $crmObject->delete($elemid);
		if(!$error){
			return new WebServiceError(WebServiceErrorCode::$DATABASEQUERYERROR,"Database error while performing required operation");
		}
		return array("status"=>"successful");
	}
	
?>