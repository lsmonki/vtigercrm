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

// Adding email field type to vtiger_ws_fieldtype
function vt530_addEmailFieldTypeInWs(){
	$db = PearDatabase::getInstance();
	$checkQuery = "SELECT * FROM vtiger_ws_fieldtype WHERE fieldtype=?";
	$params = array ("email");
	$checkResult = $db->pquery($checkQuery,$params);
	if($db->num_rows($checkResult) <= 0) {		
		$fieldTypeId = $db->getUniqueID('vtiger_ws_fieldtype');
		$sql = 'insert into vtiger_ws_fieldtype(uitype,fieldtype) values (?,?)';
		$params = array( '13', 'email');
		$db->pquery($sql, $params);
		echo "<br> Added Email in webservices types ";
	}
}

function vt530_addFilterToListTypes() {
	$db = PearDatabase::getInstance();
	$query = "SELECT operationid FROM vtiger_ws_operation WHERE name=?";
	$parameters = array("listtypes");
	$result = $db->pquery($query,$parameters);
	if($db->num_rows($result) > 0){
		$operationId = $db->query_result($result,0,'operationid');
		$status = vtws_addWebserviceOperationParam($operationId,'fieldTypeList',
						'Encoded',0);
		if($status === false){
				echo 'FAILED TO SETUP listypes WEBSERVICE HALFWAY THOURGH';
				die;
		}
	}
}

function vt530_registerVTEntityDeltaApi() {
	$db = PearDatabase::getInstance();

	$em = new VTEventsManager($db);
	$em->registerHandler('vtiger.entity.beforesave', 'data/VTEntityDelta.php', 'VTEntityDelta');
	$em->registerHandler('vtiger.entity.aftersave', 'data/VTEntityDelta.php', 'VTEntityDelta');
}

function vt530_addDependencyColumnToEventHandler() {
	$db = PearDatabase::getInstance();
	$db->pquery("ALTER TABLE vtiger_eventhandlers ADD COLUMN dependent_on VARCHAR(255) NOT NULL DEFAULT '[]'", array());
}

function vt530_addDepedencyToVTWorkflowEventHandler(){
	$db = PearDatabase::getInstance();

	$dependentEventHandlers = array('VTEntityDelta');
	$dependentEventHandlersJson = Zend_Json::encode($dependentEventHandlers);
	$db->pquery('UPDATE vtiger_eventhandlers SET dependent_on=? WHERE event_name=? AND handler_class=?',
									array($dependentEventHandlersJson, 'vtiger.entity.aftersave', 'VTWorkflowEventHandler'));
}

vt530_addEmailFieldTypeInWs();
vt530_addFilterToListTypes();

vt530_registerVTEntityDeltaApi();
vt530_addDependencyColumnToEventHandler();
vt530_addDepedencyToVTWorkflowEventHandler();

installVtlibModule('WSAPP', "packages/vtiger/mandatory/WSAPP.zip");


updateVtlibModule('Mobile', "packages/vtiger/mandatory/Mobile.zip");
updateVtlibModule('RecycleBin', 'packages/vtiger/optional/RecycleBin.zip');

$migrationlog->debug("\n\nDB Changes from 5.2.1 to 5.3.0  -------- Ends \n\n");

?>