<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

require_once 'include/utils/utils.php';

//5.2.1 to 5.3.0  database changes

$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.2.1 to 5.3.0 -------- Starts \n\n");

// Take away the ability to disable entity name fields
$sql = "SELECT modulename, fieldname, tablename FROM vtiger_entityname;";
$params = array();
$result = $adb->pquery($sql, $params);
$it = new SqlResultIterator($adb, $result);
foreach ($it as $row) {
	$tabId = getTabid($row->modulename);
	$column = $row->fieldname;
	$columnArray = explode(',', $column);
	$tableName = $row->tablename;
	$sql = "UPDATE vtiger_field,vtiger_def_org_field
					SET presence=0,
						vtiger_def_org_field.visible=0
					WHERE vtiger_field.tabid=? and columnname in "."(".generateQuestionMarks($columnArray).")
						AND tablename=? AND vtiger_field.fieldid=vtiger_def_org_field.fieldid";
	$params = array($tabId, $columnArray, $tableName);
	$adb->pquery($sql, $params);
}



$migrationlog->debug("\n\nDB Changes from 5.2.1 to 5.3.0  -------- Ends \n\n");

?>