<?php
	
	function vtws_listtypes($user){
		try{
			//get All the modules the current user is permitted to Access.
			$allModuleNames = getPermittedModuleNames();
			//get All the CRM entity names.
			$crmEntityNames = getModuleNameList();
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
	
	/** function to get the module List to which are crm entities. 
	 *  @return Array modules list as array
	 */
	function getModuleNameList(){
		global $adb;
	
		$sql = "select vtiger_moduleowners.*, vtiger_tab.name from vtiger_moduleowners inner join vtiger_tab on vtiger_moduleowners.tabid = vtiger_tab.tabid order by vtiger_tab.tabsequence";
		$res = $adb->pquery($sql, array());
		$mod_array = Array();
		while($row = $adb->fetchByAssoc($res)){
			array_push($mod_array,$row['name']);
		}
		return $mod_array;
	}
?>