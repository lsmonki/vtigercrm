<?php
	require_once("include/Zend/Json.php");
	require 'include.inc';
	function vtDeleteExpressionJson($adb, $request){
		$moduleName = $request['modulename'];
		$fieldName = $request['fieldname'];
		$mem = new VTModuleExpressionsManager($adb);
		$me = $mem->retrieve($moduleName);
		$me->remove($fieldName);
		$mem->save($me);
		echo Zend_Json::encode(array('status'=>'success'));
	}
	
	vtDeleteExpressionJson($adb, $_GET);
?>