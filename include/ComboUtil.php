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
/** Function to  returns the combo field values in array format
  * @param $combofieldNames -- combofieldNames:: Type string array
  * @returns $comboFieldArray -- comboFieldArray:: Type string array
 */
function getComboArray($combofieldNames)
{
	global $log;
        $log->debug("Entering getComboArray(".$combofieldNames.") method ...");
	global $adb,$current_user;
        $roleid=$current_user->roleid;
	$comboFieldArray = Array();
	foreach ($combofieldNames as $tableName => $arrayName)
	{
		$fldArrName= $arrayName;
		$arrayName = Array();
		$result = $adb->query("select $tableName from vtiger_$tableName  inner join vtiger_role2picklist on vtiger_role2picklist.picklistvalueid = vtiger_$tableName.picklist_valueid where roleid='$roleid' and picklistid in (select picklistid from vtiger_$tableName) and presence=1 order by sortid");
		while($row = $adb->fetch_array($result))
		{
			$val = $row[$tableName];
			$arrayName[$val] = $val;	
		}
		$comboFieldArray[$fldArrName] = $arrayName;
	}
	$log->debug("Exiting getComboArray method ...");
	return $comboFieldArray;	
}
function getUniquePicklistID()
{
	global $adb;
	$sql="select id from vtiger_picklistvalues_seq";
	$picklistvalue_id = $adb->query_result($adb->query($sql),0,'id');

	$qry = "update vtiger_picklistvalues_seq set id =".++$picklistvalue_id;
	$adb->query($qry);

	return $picklistvalue_id;
}

?>
