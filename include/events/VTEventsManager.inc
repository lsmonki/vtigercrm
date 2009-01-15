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
		 * @param $condition A condition which must evaluate true for the event to be accepted.
		 */
		function registerHandler($forEvent, $path, $className, $condition=''){
			$adb = $this->adb;
			$result = $adb->pquery("SELECT * FROM vtiger_eventhandlers WHERE event_name=? AND handler_path=? AND handler_class=?",
				array($forEvent, $path, $className));
			if($adb->num_rows($result)===0){
				$handlerId = $adb->getUniqueId('vtiger_eventhandlers');
				$adb->pquery("insert into vtiger_eventhandlers
					(eventhandler_id, event_name, handler_path, handler_class, cond, is_active)
					values (?,?,?,?,?, true)", array($handlerId, $forEvent, $path, $className, $condition));
			}
		}
		
		/**
		 * Set an event handler as inactive
		 * @param The handler class to set as inactive
		 *
		 */
		function setHandlerInActive($handlerClass){
			$adb = $this->adb;
			$adb->pquery("update vtiger_eventhandlers set is_active=false where handler_class=?", array($handlerClass));
		}
		
		/**
		 * Set an event handler as active
		 *
		 * @param The handler class to set as active
		 */
		function setHandlerActive($handlerClass){
			$adb = $this->adb;
			$adb->pquery("update vtiger_eventhandlers set is_active=true where handler_class=?", array($handlerClass));
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
			$adb->pquery("delete from vtiger_eventhandler_module where handler_class=?",
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

		/**
		 * Set the module the handler belongs to
		 * 
		 * @param moduleName - The name of the module
		 * @param handlerClass - The name of the handler class
		 */
		function setModuleForHandler($moduleName, $handlerClass){
			$adb = $this->adb;
			$result = $adb->pquery("SELECT * FROM vtiger_eventhandler_module WHERE handler_class=?",
				array($handlerClass));
			if($adb->num_rows($result)===0){
				$handlerModuleId = $adb->getUniqueId('vtiger_eventhandler_module');
				$adb->pquery("insert into vtiger_eventhandler_module
					(eventhandler_module_id, module_name, handler_class)
					values (?,?,?)", array($handlerModuleId, $moduleName, $handlerClass));
			}
		}

		/**
		 * List handler classes for a module
		 * 
		 * @param moduleName - The name of the module
		 */
		function listHandlersForModule($moduleName){
		  $adb = $this->adb;
		  $result = $adb->pquery('SELECT handler_class FROM vtiger_eventhandler_module WHERE module_name=?', array($moduleName));
		  $it = new SqlResultIterator($adb, $result);
		  $arr = array();
		  foreach($it as $row){
			$arr[] = $row->handler_class;
		  }
		  return $arr;
		}
		
		/**
		 * List active events.
		 *
		 * @return A list of registered events.
		 */
		function listActiveEventHandlers(){
			$adb = $this->adb;
			$result = $adb->pquery("select * from vtiger_eventhandlers where is_active=true");
			return $this->listEventHandlers($result);
		}
		
		function listAllEventHandlers(){
			$adb = $this->adb;
			$result = $adb->pquery("select * from vtiger_eventhandlers");
			return $this->listEventHandlers($result);

		}
		
		private function listEventHandlers($result){
			$adb = $this->adb;
			$it = new SQLResultIterator($adb, $result);
			$out = array();
			foreach($it as $row){
				$el = array();
				$el['eventName'] = $row->event_name;
				$el['handlerPath'] = $row->handler_path;
				$el['handlerClass'] = $tow->handler_class;
				$el['condition'] = $row->cond;
				$el['isActive'] = $row->is_active;
				$out[] = $el;
			}
			return $out;
		}
	}
?>
