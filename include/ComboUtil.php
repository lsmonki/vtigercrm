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

require_once('database/DatabaseConnection.php');
function getComboArray($combofieldNames)
{
	$comboFieldArray = Array();
	foreach ($combofieldNames as $tableName => $arrayName)
	{
		$fldArrName= $arrayName;
		$arrayName = Array();
		$result = mysql_query("select * from ".$tableName);
		while($row = mysql_fetch_array($result))
		{
			$val = $row[$tableName];
			$arrayName[$val] = $val;	
		}
		$comboFieldArray[$fldArrName] = $arrayName;
	}
	return $comboFieldArray;	
}
?>
