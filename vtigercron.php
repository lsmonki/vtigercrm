<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

/** Load the configuration file common to cron tasks. */
require_once('cron/config.cron.php');
global $VTIGER_CRON_CONFIGURATION;

/** 
 * To make sure we can work with command line and direct browser invocation.
 */
if($argv) {
	if(!isset($_REQUEST)) $_REQUEST = Array();

	for($index = 0; $index < count($argv); ++$index) {
		$value = $argv[$index];
		if(strpos($value, '=') === false) continue;

		$keyval = explode('=', $value);
		if(!isset($_REQUEST[$keyval[0]])) {
			$_REQUEST[$keyval[0]] = $keyval[1];
		}
	}
	
	/* If app_key is not set, pick the value from cron configuration */
	if(empty($_REQUEST['app_key'])) $_REQUEST['app_key'] = $VTIGER_CRON_CONFIGURATION['app_key'];
}

/** All service invocation needs have valid app_key parameter sent */
require_once('config.inc.php');

/** Verify the script call is from trusted place. */
global $application_unique_key;
if($_REQUEST['app_key'] != $application_unique_key) {
	echo "Access denied!";
	exit;
}

/**
 * Start the cron services configured.
 */
include_once 'vtlib/Vtiger/Cron.php';

$cronTasks = false;
if (isset($_REQUEST['service'])) {
	// Run specific service
	$cronTasks = array(Vtiger_Cron::getInstance($_REQUEST['service']));
}
else {
	// Run all service
	$cronTasks = Vtiger_Cron::listAllActiveInstances();
}

foreach ($cronTasks as $cronTask) {
	try {
		$cronTask->setBulkMode(true);

		// Not ready to run yet?
		if (!$cronTask->isRunnable()) {
			echo sprintf("[INFO]: %s - not ready to run\n", $cronTask->getName());
			continue;
}

		// Timeout could happen if intermediate cron-tasks fails
		// and affect the next task. Which need to be handled in this cycle.				
		if ($cronTask->hadTimedout()) {
			echo sprintf("[INFO]: %s - cron task had timedout - restarting\n", $cronTask->getName());
}

		// Mark the status - running		
		$cronTask->markRunning();
		
		checkFileAccess($cronTask->getHandlerFile());		
		include_once $cronTask->getHandlerFile();
		
		// Mark the status - finished
		$cronTask->markFinished();
		
	} catch (Exception $e) {
		echo sprintf("[ERROR]: %s - cron task execution throwed exception.\n", $cronTask->getName());
		echo $e->getMessage();
		echo "\n";
	}		
}

?>