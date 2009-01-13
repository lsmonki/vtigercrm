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
require_once 'include/database/PearDatabase.php';
require_once 'include/utils/utils.php';
require_once 'modules/PickList/PickListUtils.php';
require_once "include/Zend/Json.php";

global $adb, $current_user;

$moduleName = $_REQUEST['moduleName'];
$tableName = $_REQUEST['fieldname'];
$roleid = $_REQUEST['roleid'];
$values = $_REQUEST['values'];
$otherRoles = $_REQUEST['otherRoles'];

if(empty($tableName)){
	echo "Table name is empty";
	exit;
}

$values = Zend_Json::decode($values);

$sql = "select * from vtiger_picklist where name = '$tableName'";
$result = $adb->query($sql);
if($adb->num_rows($result) > 0){
	$picklistid = $adb->query_result($result, 0, "picklistid");
}

if(!empty($roleid)){
	assignValues($picklistid, $roleid, $values, $tableName);
}

$otherRoles = Zend_Json::decode($otherRoles);
if(!empty($otherRoles)){
	foreach($otherRoles as $role){
		assignValues($picklistid, $role, $values, $tableName);
	}
}

echo "SUCCESS";


function assignValues($picklistid, $roleid, $values, $tableName){
	global $adb;
	$count = count($values);
	//delete older values
	$sql = "delete from vtiger_role2picklist where roleid='$roleid' and picklistid=$picklistid";
	$adb->query($sql);
	
	//insert the new values
	for($i=0;$i<$count;$i++){
		$pickVal = $values[$i];
		$sql = "select * from vtiger_$tableName where $tableName='$pickVal'";
		$result = $adb->query($sql);
		if($adb->num_rows($result) > 0){
			$picklistvalueid = $adb->query_result($result, 0, "picklist_valueid");
			$sortid = $i+1;
			$sql = "insert into vtiger_role2picklist values ('$roleid', $picklistvalueid, $picklistid, $sortid)";
			$adb->query($sql);
		}
	}
}

?>