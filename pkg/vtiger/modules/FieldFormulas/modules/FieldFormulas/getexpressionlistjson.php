<?php
	require_once("include/Zend/Json.php");
	require 'include.inc';
	function vtGetExpressionListJson($adb, $request){
		$moduleName = $request['modulename'];
		$ee = new VTModuleExpressionsManager($adb);
		$arr = $ee->expressionsForModule($moduleName);
		echo Zend_Json::encode($arr);
	}
	vtGetExpressionListJson($adb, $_GET);
?>
