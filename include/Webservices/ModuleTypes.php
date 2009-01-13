<?php
	
	function vtws_listtypes($user){
		try{
			//get All the modules the current user is permitted to Access.
			$allModuleNames = getPermittedModuleNames();
			//get All the CRM entity names.
			$crmEntityNames = vtws_getModuleNameList();
			unset($crmEntityNames[array_search('Reports',$crmEntityNames)]);
			if(strcasecmp('on',$user->is_admin)===0){
				array_push($crmEntityNames,"Users");
			}
		}catch(Exception $exception){
			return new WebServiceError(WebServiceErrorCode::$DATABASEQUERYERROR,
											"An Database error occured while performing the operation");
		}
		return array("types"=>array_values(array_intersect($crmEntityNames,$allModuleNames)));
	}

?>