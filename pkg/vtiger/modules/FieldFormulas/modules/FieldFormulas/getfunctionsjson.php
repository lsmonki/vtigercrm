<?php
require_once("include/utils/CommonUtils.php");
require_once("include/Zend/Json.php");
require 'include.inc';
	function vtJsonFields($adb, $request){
		$mem = new VTModuleExpressionsManager($adb);
		$functions = $mem->expressionFunctions();
		echo Zend_Json::encode($functions);
	}
	vtJsonFields($adb, $_REQUEST);
?>