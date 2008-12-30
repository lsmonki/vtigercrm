<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
********************************************************************************/

require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');
global $current_user,$currentModule;
global $adb;

$tabid=getTabid($currentModule);
$selected_col_string = rtrim($_REQUEST['selectedColumnsString'],",");
$merge_criteria_cols = explode(',',$selected_col_string);
$sql_user="SELECT distinct(userid) from vtiger_user2mergefields where tabid=?";
$result_user=$adb->pquery($sql_user,array($tabid));
$num_users=$adb->num_rows($result_user);
$user = "false";
for($i=0; $i<$num_users;$i++) {
	if($adb->query_result($result_user,$i,"userid") == $current_user->id) {
		$user = "true";
	}
}
if($selected_col_string != "") {
	if($user == "true") {
		$sql="UPDATE vtiger_user2mergefields SET visible=2 WHERE userid=? and tabid=?";
		$adb->pquery($sql,array($current_user->id,$tabid));
		
		$sql1="UPDATE vtiger_user2mergefields SET visible=1 WHERE fieldid IN(". generateQuestionMarks($merge_criteria_cols) .") AND userid=?";
		$adb->pquery($sql1,array($merge_criteria_cols,$current_user->id));
	}
	else {
		$fld_result = getFieldsResultForMerge($tabid);
		if ($fld_result != null) {
	    	$num_rows = $adb->num_rows($fld_result);
	    	for($i=0; $i<$num_rows; $i++) {
	    		$field_id = $adb->query_result($fld_result,$i,'fieldid');
				$params = array($current_user->id, $tabid, $field_id, 2);
	    		$adb->pquery("insert into vtiger_user2mergefields values (?,?,?,?)", $params);
			}
			$sql1="UPDATE vtiger_user2mergefields SET visible=1 WHERE fieldid IN(". generateQuestionMarks($merge_criteria_cols) .") AND userid=?";
			$adb->pquery($sql1,array($merge_criteria_cols,$current_user->id));
		}
	}
}

?>
