<?php
require_once('include/utils/utils.php');
require_once('include/Zend/Json.php');
require_once('include/events/SqlResultIterator.inc');
require_once('include/events/include.inc');
require_once('modules/com_vtiger_workflow/include.inc');

/**
 * This is a utility function to load a dumped templates files
 * into vtiger
 * @param $filename The name of the file to load.
 */
function loadTemplates($filename){
	global $adb;
	$str = file_get_contents('fetchtemplates.out');
	$tm = new VTWorkflowTemplateManager($adb);
	$tm->loadTemplates($str);
}
?>