<?php
require_once 'include/Zend/Json.php';
require_once 'VTWorkflowTemplateManager.inc';
function vtTemplatesForModuleJson($adb, $request){
	$moduleName = $request['module_name'];
	$tm = new VTWorkflowTemplateManager($adb); 
	$templates = $tm->getTemplatesForModule($moduleName);
	$arr = array();
	foreach($templates as $template){
		$arr[] = array("title"=>$template->title, 'id'=>$template->id);
	}
	echo Zend_Json::encode($arr);
}
vtTemplatesForModuleJson($adb, $_REQUEST);
?>