<?php
	require_once("include/events/SqlResultIterator.inc");
	class VTEventTrigger{
		function __construct($adb, $name){
			$this->name=$name;
			$this->adb = $adb;
		}
		
		function trigger($data){
			$adb = $this->adb;
			$result = $adb->pquery("select handler_path, handler_class, cond
				from vtiger_eventhandlers where event_name=? and is_active=true", array($this->name));
			$it = new SqlResultIterator($adb, $result);
			foreach($it as $row){
				$condition = new VTEventCondition($row->cond);
				if($condition->test($data)){
					require_once($row->handler_path);
					$handler_class = $row->handler_class;
					$handler = new $handler_class();
					$handler->handleEvent($this->name, $data);
				}
			}
		}
	}
?>
