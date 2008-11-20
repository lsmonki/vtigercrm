<?php
	require_once "include/Zend/Json.php";
	require_once("include/events/VTWSEntityType.inc");
	
	function vtModuleTypeInfoJson($adb, $request){
		$moduleName = $request['module_name'];
		$et = VTWSEntityType::usingGlobalCurrentUser($moduleName);
		echo Zend_Json::encode($et->getFieldLabels());
	}
	vtModuleTypeInfoJson($adb, $_REQUEST);
?>