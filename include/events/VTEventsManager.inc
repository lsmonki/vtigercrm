<?php
	
	/**
	 * Create and trigger events in vtiger
	 */
	class VTEventsManager{
		function __construct($adb){
			$this->adb = $adb;
		}
		
		/**
		 * Register an event handler
		 *
		 * @param $forEvent The name of the event to handle
		 * @param $path The path to the php file containing the handler
		 * @param $className The name of the VTEventHandler class.
		 */
		function registerHandler($forEvent, $path, $className){
			$adb = $this->adb;
			$handlerId = $adb->getUniqueId('vtiger_eventhandlers');
			$adb->pquery("insert into vtiger_eventhandlers
				(eventhandler_id, event_name, handler_path, handler_class)
				values (?,?,?,?)", array($handlerId, $forEvent, $path, $className));
		}
		
		
		/**
		 * Unregister a registered handler
		 *
		 * @param $className The name of teh VTEventHandler class to unregister
		 */
		function unregisterHandler($className){
			$adb = $this->adb;
			$adb->pquery("delete from vtiger_eventhandlers where handler_class=?",
				array($className));
		}
		
		/**
		 * Get an event triger instance
		 * 
		 * @param $triggerName The name of the event.
		 * @return The trigger object for the event.
		 */
		function getTrigger($triggerName){
			$adb=$this->adb;
			return new VTEventTrigger($adb, $triggerName);
		}
		
		/**
		 * Trigger an event
		 *
		 * @param $triggerName The name of the event.
		 * @return The trigger object for the event.
		 */
		function triggerEvent($triggerName, $data){
			$this->getTrigger($triggerName)->trigger($data);
		}
	}
?>