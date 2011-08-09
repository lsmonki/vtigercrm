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

// Workflow changes
if(!in_array('type', $adb->getColumnNames('com_vtiger_workflows'))) {
	$adb->pquery("ALTER TABLE com_vtiger_workflows ADD COLUMN type VARCHAR(255) DEFAULT 'basic'", array());
}

// Read-Only configuration for fields at Profile level
$adb->query("UPDATE vtiger_def_org_field SET readonly=0");
$adb->query("UPDATE vtiger_profile2field SET readonly=0");

// Modify selected column to enable support for setting default values for fields
$adb->query("ALTER TABLE vtiger_field CHANGE COLUMN selected defaultvalue TEXT default ''");
$adb->query("UPDATE vtiger_field SET defaultvalue='' WHERE defaultvalue='0'");

// Scheduled Reports (Email)
$adb->pquery("CREATE TABLE IF NOT EXISTS vtiger_scheduled_reports(reportid INT, recipients TEXT, schedule TEXT,
									format VARCHAR(10), next_trigger_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(reportid))
				ENGINE=InnoDB DEFAULT CHARSET=utf8;", array());

$updatedCVIds = array();
$updatedReportIds = array();
$usersQuery = "SELECT * FROM vtiger_users";
$usersInfo = $adb->query($usersQuery);
$usersCount = $adb->num_rows($usersInfo);
for($i=0;$i<$usersCount;$i++){
	$username = $adb->query_result($usersInfo,$i,'user_name');
	$firstname = $adb->query_result($usersInfo,$i,'first_name');
	$lastname = $adb->query_result($usersInfo,$i,'last_name');
	$usernames[$i] = $username;
	$fullname = getDisplayName(array('f'=>$firstname,'l'=>$lastname));
	$fullnames[$i] = $fullname;
}

for($i=0;$i<$usersCount;$i++){
	$cvQuery = "SELECT * FROM vtiger_cvadvfilter WHERE columnname LIKE '%:assigned_user_id%' AND value LIKE '%$usernames[$i]%'";
	$cvResult = $adb->query($cvQuery);
	$cvCount = $adb->num_rows($cvResult);
	for($k=0;$k<$cvCount;$k++){
			$id = $adb->query_result($cvResult,$k,'cvid');
			if(!in_array($id, $updatedCVIds)){
				$value = $adb->query_result($cvResult,$k,'value');
				$value = explode(',',$value);
				$fullname='';
				if(count($value)>1){
					for($m=0;$m<count($value);$m++){
						$index = array_keys($usernames,$value[$m]);
						if($m == count($value)-1){
							$fullname .= trim($fullnames[$index[0]]);
						}
						else {
							$fullname .= trim($fullnames[$index[0]]).',';
						}
					}
				}else{
					$fullname = $fullnames[$i];
				}
				$updatedCVIds[$k] = $id;
				$adb->query("UPDATE vtiger_cvadvfilter SET value='$fullname' WHERE cvid=$id AND columnname LIKE '%:assigned_user_id%'");
			}
	}
	$reportQuery = "SELECT * FROM vtiger_relcriteria WHERE columnname LIKE 'vtiger_users%:user_name%' AND value LIKE '%$usernames[$i]%'";
	$reportResult = $adb->query($reportQuery);
	$reportsCount = $adb->num_rows($reportResult);

	$fullname='';
	for($j=0;$j<$reportsCount;$j++){

		$id = $adb->query_result($reportResult,$j,'queryid');
		if(!in_array($id,$updatedReportIds)){

			$value = $adb->query_result($reportResult,$j,'value');
			$value = explode(',',$value);
			$fullname='';
			if(count($value)>1){
				for($m=0;$m<count($value);$m++){
					$index = array_keys($usernames,$value[$m]);
					if($m == count($value)-1){
						$fullname .= trim($fullnames[$index[0]]);
					}
					else {
						$fullname .= trim($fullnames[$index[0]]).',';
					}
				}
			}else{
				$fullname = $fullnames[$i];
			}

			$updatedReportIds[$j] =$id;
			$adb->query("UPDATE vtiger_relcriteria SET value='$fullname' WHERE queryid=$id AND columnname LIKE 'vtiger_users%:user_name%'");

		}
	}
}

installVtlibModule('WSAPP', "packages/vtiger/mandatory/WSAPP.zip");

updateVtlibModule('Mobile', "packages/vtiger/mandatory/Mobile.zip");
updateVtlibModule('RecycleBin', 'packages/vtiger/optional/RecycleBin.zip');
updateVtlibModule('Services', 'packages/vtiger/mandatory/Services.zip');
updateVtlibModule('ServiceContracts', 'packages/vtiger/mandatory/ServiceContracts.zip');
updateVtlibModule('PBXManager','packages/vtiger/mandatory/PBXManager.zip');
updateVtlibModule('ModComments', 'packages/vtiger/optional/ModComments.zip');
updateVtlibModule('SMSNotifier', 'packages/vtiger/optional/SMSNotifier.zip');

$migrationlog->debug("\n\nDB Changes from 5.2.1 to 5.3.0  -------- Ends \n\n");

?>