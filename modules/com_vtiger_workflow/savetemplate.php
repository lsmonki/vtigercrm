<?php
require_once "include/utils/CommonUtils.php";
require_once "include/events/SqlResultIterator.inc";
require_once "include/Zend/Json.php";
require_once "VTWorkflowApplication.inc";
require_once "VTWorkflowManager.inc";
require_once "VTWorkflowTemplateManager.inc";
require_once "VTTaskManager.inc";
require_once "VTWorkflowUtils.php";

function vtSaveWorkflowTemplate($adb, $request){
	$util = new VTWorkflowUtils();
	$module = new VTWorkflowApplication("savetemplate");
	$mod = return_module_language($current_language, $module->name);
	
	if(!$util->checkAdminAccess()){
		$errorUrl = $module->errorPageUrl($mod['LBL_ERROR_NOT_ADMIN']);
		$util->redirectTo($errorUrl, $mod['LBL_ERROR_NOT_ADMIN']);
		return;
	}

	$title = $request['title'];
	$workflowId = $request['workflow_id'];
	$wfs = new VTworkflowManager($adb);
	$workflow = $wfs->retrieve($workflowId);
	$tm = new VTWorkflowTemplateManager($adb);
	$tpl = $tm->newTemplate($title, $workflow);
	$tm->saveTemplate($tpl);
	$returnUrl = $request['return_url'];
	?>
		<script type="text/javascript" charset="utf-8">
			 window.location="<?=$returnUrl?>";
		</script>
		<a href="<?=$returnUrl?>">Return</a>	
	<?php
}
vtSaveWorkflowTemplate($adb, $_REQUEST);
?>