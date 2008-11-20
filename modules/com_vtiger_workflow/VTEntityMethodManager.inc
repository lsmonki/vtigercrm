<?php
require_once("include/events/SqlResultIterator.inc");
class VTEntityMethodManager{
	
	function __construct($adb){
		$this->adb = $adb;
	}
	
	
	function addEntityMethod($moduleName, $methodName, $functionPath, $functionName){
		$adb = $this->adb;
		$id = $adb->getUniqueId("com_vtiger_workflowtasks_entitymethod");
		$adb->pquery("insert into com_vtiger_workflowtasks_entitymethod (workflowtasks_entitymethod_id, module_name, function_path, function_name, method_name) values (?,?,?,?,?)", array($id, $moduleName, $functionPath, $functionName, $methodName));
	}
	
	
	
	function executeMethod($entityData, $methodName){
		$adb = $this->adb;
		$moduleName = get_class($entityData->focus);
		$result = $adb->pquery("select function_path, function_name from com_vtiger_workflowtasks_entitymethod where module_name=? and method_name=?", array($moduleName, $methodName));
		if($adb->num_rows($result)!=0){
			$functionPath = $adb->query_result($result, 0, 'function_path');
			$functionName = $adb->query_result($result, 0, 'function_name');
			require_once($functionPath);
			$functionName($entityData);
		}
	}
	
	function methodsForModule($moduleName){
		$adb = $this->adb;
		$result = $adb->pquery("select method_name from com_vtiger_workflowtasks_entitymethod where module_name=?", array($moduleName));
		$it = new SqlResultIterator($adb, $result);
		$methodNames = array();
		foreach($it as $row){
			$methodNames[] = $row->method_name;
		}
		return $methodNames;
	}
	/*
	private function methodExists($object, $methodName){
		$className = get_class($object);
		$class = new ReflectionClass($className);
		$methods = $class->getMethods();
		foreach($methods as $method){
			if($method->getName()==$methodName){
				return true;
			}
		}
		return false;
	}*/
}
?>