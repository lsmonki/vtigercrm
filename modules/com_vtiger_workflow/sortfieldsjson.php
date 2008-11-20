<?php
	function vtSortFieldsJson($request){
		$moduleName = $request['module_name'];
		require_once("modules/$moduleName/$moduleName.php");
		$focus = new $moduleName();
		echo Zend_Json::encode($focus->sortby_fields);
	}
	vtSortFieldsJson($_REQUEST);
?>