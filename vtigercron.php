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

/** 
 * To make sure we can work with command line and direct browser invocation.
 */
if($argv) {
	if(!isset($_REQUEST)) $_REQUEST = Array();

	for($index = 0; $index < count($argv); ++$index) {
		$value = $argv[$index];
		if(strpos($value, '=') === false) continue;

		$keyval = split('=', $value);
		if(!isset($_REQUEST[$keyval[0]])) {
			$_REQUEST[$keyval[0]] = $keyval[1];
		}
	}
}

/** All service invocation needs have valid app_key parameter sent */
require_once('config.inc.php');

/** Verify the script call is from trusted place. */
global $application_unique_key;
if($_REQUEST['app_key'] != $application_unique_key) {
	echo "Access denied!";
	exit;
}

/** Include the service file */
$service = $_REQUEST['service'];
if($service == 'MailScanner') {
	include_once('cron/MailScanner.service');
}
if($service == 'RecurringInvoice') {
	include_once('cron/modules/SalesOrder/RecurringInvoice.service');
}

if($service == 'com_vtiger_workflow'){
	include_once('cron/modules/com_vtiger_workflow/com_vtiger_workflow.service');
}

?>
