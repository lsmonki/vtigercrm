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
function getComboArray($combofieldNames)
{
	global $adb;
	$comboFieldArray = Array();
	foreach ($combofieldNames as $tableName => $arrayName)
	{
		$fldArrName= $arrayName;
		$arrayName = Array();
		$result = $adb->query("select * from ".$tableName." where presence=1 order by sortorderid");
		while($row = $adb->fetch_array($result))
		{
			$val = $row[$tableName];
			$arrayName[$val] = $val;	
		}
		$comboFieldArray[$fldArrName] = $arrayName;
	}
	return $comboFieldArray;	
}
?>
