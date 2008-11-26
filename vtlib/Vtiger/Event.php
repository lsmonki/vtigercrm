<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/
include_once('vtlib/Vtiger/Utils.php');
@include_once('include/events/include.inc');

/**
 * Provides API to work with vtiger CRM Eventing (available from vtiger 5.1)
 * @package vtlib
 */
class Vtiger_Event {
	/** Event name like: vtiger.entity.aftersave, vtiger.entity.beforesave */
	var $eventname;
	/** Event handler class to use */
	var $classname;
	/** Filename where class is defined */
	var $filename;

	/**
	 * Helper function to log messages
	 * @param String Message to log
	 * @param Boolean true appends linebreak, false to avoid it
	 * @access private
	 */
	static function log($message, $delim=true) {
		Vtiger_Utils::Log($message, $delim);
	}

	/**
	 * Check if vtiger CRM support Events
	 */
	static function hasSupport() {
		return Vtiger_Utils::checkTable('vtiger_eventhandlers');
	}

	/**
	 * Handle event registration for module
	 * @param Vtiger_Module Instance of the module to use
	 * @param String Name of the Event like vtiger.entity.aftersave, vtiger.entity.beforesave
	 * @param String Name of the Handler class (should extend VTEventHandler)
	 * @param String File path which has Handler class definition
	 */
	static function register($moduleInstance, $eventname, $classname, $filename) {
		// Security check on fileaccess, don't die if it fails
		if(Vtiger_Utils::checkFileAccess($filename, false)) {
			global $adb;
			$eventsManager = new VTEventsManager($adb);
			// TODO Update the call when API is fixed
			// $eventsManager->registerHandler($eventname, $classname, $filename,$moduleInstance->name);
			$eventsManager->registerHandler($eventname, $filename, $classname);

			self::log("Registering Event $eventname with [$filename] $classname ... DONE");
		}
	}

	/**
	 * Get all the registered module events
	 * @param Vtiger_Module Instance of the module to use
	 */
	static function getAll($moduleInstance) {
		global $adb;
		$events = false;
		if(self::hasSupport()) {
			// TODO VTEventManager should provide API to get list of registered events on module
			$records = $adb->pquery("SELECT * FROM vtiger_eventhandlers"); 
			$reccount = $adb->num_rows($records);
			if($reccount) {
				for($index = 0; $index < $reccount; ++$index) {
					$event = new Vtiger_Event();
					$event->eventname = $adb->query_result($records, $index, 'event_name');
					$event->classname = $adb->query_result($records, $index, 'handler_class');
					$event->filename = $adb->query_result($records, $index, 'handler_path');
					$events[] = $event;
				}
			}
		}
		return $events;
	}		
}
?>
