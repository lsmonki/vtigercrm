<?php
/*+*******************************************************************************
 *  The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 *********************************************************************************/

/**
 * @author MAK
 */

require_once 'include/utils/utils.php';

//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.2.0 RC to 5.2.0 -------- Starts \n\n");

ExecuteQuery("UPDATE vtiger_tab SET customized=1 WHERE name='ProjectTeam'");

function VT520GA_webserviceMigrate(){
	require_once 'include/Webservices/Utils.php';
	$customWebserviceDetails = array(
		"name"=>"revise",
		"include"=>"include/Webservices/Revise.php",
		"handler"=>"vtws_revise",
		"prelogin"=>0,
		"type"=>"POST"
	);

	$customWebserviceParams = array(
		array("name"=>'element',"type"=>'Encoded')
	);
	echo 'INITIALIZING WEBSERVICE...';
	$operationId = vtws_addWebserviceOperation($customWebserviceDetails['name'],$customWebserviceDetails['include'],
		$customWebserviceDetails['handler'],$customWebserviceDetails['type']);
	if($operationId === null && $operationId > 0){
		echo 'FAILED TO SETUP '.$customWebserviceDetails['name'].' WEBSERVICE';
		die;
	}
	$sequence = 1;
	foreach ($customWebserviceParams as $param) {
		$status = vtws_addWebserviceOperationParam($operationId,$param['name'],$param['type'],$sequence++);
		if($status === false){
			echo 'FAILED TO SETUP '.$customWebserviceDetails['name'].' WEBSERVICE HALFWAY THOURGH';
			die;
		}
	}
}

VT520GA_webserviceMigrate();

$migrationlog->debug("\n\nDB Changes from 5.2.0 RC to 5.2.0 -------- Ends \n\n");
?>