<?
require_once("include/utils/CommonUtils.php");
require_once("include/events/SqlResultIterator.inc");
require_once("include/Zend/Json.php");
require_once("VTWorkflowApplication.inc");
require_once("VTTaskManager.inc");
require_once('VTWorkflowUtils.php');

	function vtDeleteWorkflow($adb, $request){
		$util = new VTWorkflowUtils();
		$module = new VTWorkflowApplication("deltetask");
		$mod = return_module_language($current_language, $module->name);

		if(!$util->checkAdminAccess()){
			$errorUrl = $module->errorPageUrl($mod['LBL_ERROR_NOT_ADMIN']);
			$util->redirectTo($errorUrl, $mod['LBL_ERROR_NOT_ADMIN']);
			return;
		}

		$wm = new VTTaskManager($adb);
		$wm->deleteTask($request['task_id']);
		
		if(isset($request["return_url"])){
			$returnUrl=$request["return_url"];
		}else{
			$returnUrl=$module->editWorkflowUrl($wf->id);
		}
		
		?>
		<script type="text/javascript" charset="utf-8">
			window.location="<?=$returnUrl?>";
		</script>
		<a href="<?=$returnUrl?>">Return</a>
		<?php
	}
	vtDeleteWorkflow($adb, $_REQUEST);
?>