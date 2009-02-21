<?php

require_once("Smarty_setup.php");
require_once("include/utils/CommonUtils.php");
require_once("include/events/SqlResultIterator.inc");
require_once("include/events/VTWSEntityType.inc");

require_once("VTWorkflowApplication.inc");
require_once("VTTaskManager.inc");
require_once("VTWorkflowManager.inc");

	function vtTaskEdit($adb, $request, $current_language, $app_strings){
		global $theme;
		$image_path = "themes/$theme/images/";
		
		$module = new VTWorkflowApplication('edittask');
		$smarty = new vtigerCRM_Smarty();
		$tm = new VTTaskManager($adb);
		$smarty->assign('edit',isset($request["task_id"]));
		if(isset($request["task_id"])){
			$task = $tm->retrieveTask($request["task_id"]);
			$workflowId=$task->workflowId;
		}else{
			$workflowId = $request["workflow_id"];
			$taskClass = $request["task_type"];
			$task = $tm->createTask($taskClass, $workflowId);
		}
		
		$wm = new VTWorkflowManager($adb);
		$workflow = $wm->retrieve($workflowId);
		
		$smarty->assign("workflow", $workflow);
		$smarty->assign("returnUrl", $request["return_url"]);
		$smarty->assign("task", $task);
		$smarty->assign("taskType", $taskClass);
		$smarty->assign("saveType", $request['save_type']);
		$taskClass = get_class($task);
		$smarty->assign("taskTemplate", "{$module->name}/taskforms/$taskClass.tpl");
		$et = VTWSEntityType::usingGlobalCurrentUser($workflow->moduleName);
		$smarty->assign("entityType", $et);
		$smarty->assign('entityName', $workflow->moduleName);
		$smarty->assign("fieldNames", $et->getFieldNames());
		
		$dateFields = array();
		$fieldTypes = $et->getFieldTypes();
		$fieldLabels = $et->getFieldLabels();
		foreach($fieldTypes as $name => $type){
			if($type->type=='Date' || $type->type=='DateTime'){
				$dateFields[$name] = $fieldLabels[$name];
			}
		}
		
		$smarty->assign('dateFields', $dateFields);
		
		
		if($task->trigger!=null){
			$trigger = $task->trigger;
			$days = $trigger['days'];
			if ($days < 0){
				$days*=-1;
				$direction = 'before';
			}else{
				$direction = 'after';
			}
			$smarty->assign('trigger', array('days'=>$days, 'direction'=>$direction, 'field'=>$trigger['field']));
		}
		
		
		$smarty->assign("MOD", array_merge(
			return_module_language($current_language,'Settings'), 
			return_module_language($current_language, $module->name)));
		$smarty->assign("APP", $app_strings);
		$smarty->assign("IMAGE_PATH",$image_path);
		$smarty->assign("THEME", $theme);
		$smarty->assign("MODULE_NAME", $module->label);
		$smarty->assign("PAGE_NAME", 'Edit Task');
		$smarty->assign("PAGE_TITLE", 'Edit an existing task or create a one');
		
		$smarty->assign("module", $module);
		
		$smarty->display("{$module->name}/EditTask.tpl");
	}
	vtTaskEdit($adb, $_REQUEST, $current_language, $app_strings);
?>