<?php
	require_once("include/Webservices/QueryParser.php");
	
	function vtws_query($q,$user){
		
		global $adb;
		
		$parser = new Parser($user, $q);
		$error = $parser->parse();
		
		if($error){
			return $parser->getError();
		}
		
		$mysql_query = $parser->getSql();
		$meta = $parser->getObjectMetaData();
		if(!$meta->hasAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to access object type is denied");
		}
		if(!$meta->hasReadAccess()){
			return new WebServiceError(WebServiceErrorCode::$ACCESSDENIED,"Permission to read is denied");
		}
		
		$adb->startTransaction();
		$result = $adb->pquery($mysql_query, array());
		$error = $adb->hasFailedTransaction();
		$adb->completeTransaction();
		
		if($error){
			return new WebServiceError(WebServiceErrorCode::$DATABASEQUERYERROR,"Database error while performing required operation");
		}
		
		$noofrows = $adb->num_rows($result);
		$output = array();
		for($i=0; $i<$noofrows; $i++){
			$row = $adb->fetchByAssoc($result,$i);
			if(!$meta->hasPermission(VtigerCRMObjectMeta::$RETRIEVE,$row["crmid"])){
				continue;
			}
			$output[] = DataTransform::sanitizeDataWithColumn($row,$meta);
		}
		
		return $output;
		
		
	}
	
?>