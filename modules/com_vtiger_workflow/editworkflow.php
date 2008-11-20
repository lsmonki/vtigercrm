<?php
require_once("Smarty_setup.php");
require_once("include/utils/CommonUtils.php");
require_once("include/Zend/Json.php");

require_once("include/events/SqlResultIterator.inc");
require_once("include/events/VTWSEntityType.inc");

require_once("VTWorkflowManager.inc");
require_once("VTTaskManager.inc");
require_once("VTWorkflowApplication.inc");

	function vtWorkflowEdit($adb, $request, $requestUrl, $current_language){
		$module = new VTWorkflowApplication("editworkflow");
		$smarty = new vtigerCRM_Smarty();
		$wfs = new VTWorkflowManager($adb);
		if(isset($request["workflow_id"])){
			$workflow = $wfs->retrieve($request["workflow_id"]);
		}else{
			$moduleName=$request["module_name"];
			$workflow = $wfs->newWorkflow($moduleName);
			
		}

		$et = VTWSEntityType::usingGlobalCurrentUser($workflow->moduleName);
		$smarty->assign("fieldNames", Zend_Json::encode($et->getFieldNames()));
		$smarty->assign("fieldTypes", Zend_Json::encode($et->getFieldTypes()));
		
		
		$tm = new VTTaskManager($adb);
		$tasks = $tm->getTasksForWorkflow($workflow->id);
		$smarty->assign("tasks", $tasks);
		$smarty->assign("taskTypes", $tm->getTaskTypes());
		$smarty->assign("newTaskReturnUrl", $requestUrl);
		
		$smarty->assign("returnUrl", $request["return_url"]);
		
		$smarty->assign("MOD", return_module_language($current_language,'Settings'));
		$smarty->assign("IMAGE_PATH", $image_path);
		$smarty->assign("MODULE_NAME", $module->label);
		$smarty->assign("PAGE_NAME", 'Edit Workflow');
		$smarty->assign("PAGE_TITLE", 'Edit an existing workflow or create a one');
		
		$smarty->assign("workflow", $workflow);
		$smarty->assign("saveType", isset($request["workflow_id"])?"edit":"new");
		$smarty->assign("module", $module);
		
		$smarty->display("{$module->name}/EditWorkflow.tpl");
	}
vtWorkflowEdit($adb, $_REQUEST, $_SERVER["REQUEST_URI"], $current_language);
?>