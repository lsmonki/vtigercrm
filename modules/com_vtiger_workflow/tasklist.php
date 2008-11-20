<?
	require_once("Smarty_setup.php");
	require_once("include/utils/CommonUtils.php");
	require_once("include/events/SqlResultIterator.inc");
	
	require_once("VTTaskManager.inc");
	require_once("VTWorkflowApplication.inc");

	
	function vtDisplayTaskList($adb, $requestUrl, $current_language){
		$module = new VTWorkflowApplication();
		$smarty = new vtigerCRM_Smarty();
		$tm = new VTTaskManager($adb);
		$smarty->assign("tasks", $tm->getTasks());
		$smarty->assign("moduleNames", array("Contacts", "Applications"));
		$smarty->assign("taskTypes", array("VTEmailTask", "VTDummyTask"));
		$smarty->assign("returnUrl", $requestUrl);
		
		$smarty->assign("MOD", return_module_language($current_language,'Settings'));
		$smarty->assign("APP", $app_strings);
		$smarty->assign("IMAGE_PATH",$image_path);
		$smarty->assign("MODULE_NAME", $module->label);
		$smarty->assign("PAGE_NAME", 'Task List');
		$smarty->assign("PAGE_TITLE", 'List available tasks');
		$smarty->assign("moduleName", $moduleName);
		$smarty->display("{$module->name}/ListTasks.tpl");
	}
	vtDisplayTaskList($adb, $_SERVER["REQUEST_URI"], $current_language);
?>