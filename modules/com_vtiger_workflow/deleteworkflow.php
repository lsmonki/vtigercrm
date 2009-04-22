<?php
require_once("include/utils/CommonUtils.php");
require_once("include/events/SqlResultIterator.inc");
require_once("include/Zend/Json.php");
require_once("VTWorkflowApplication.inc");
require_once("VTWorkflowManager.inc");
require_once("VTWorkflowUtils.php");
	function vtDeleteWorkflow($adb, $request){
		$util = new VTWorkflowUtils();
		$module = new VTWorkflowApplication("deleteworkflow");
		$mod = return_module_language($current_language, $module->name);

		if(!$util->checkAdminAccess()){
			$errorUrl = $module->errorPageUrl($mod['LBL_ERROR_NOT_ADMIN']);
			$util->redirectTo($errorUrl, $mod['LBL_ERROR_NOT_ADMIN']);
			return;
		}

		$wm = new VTWorkflowManager($adb);
		$wm->delete($request['workflow_id']);
		
		if(isset($request["return_url"])){
			$returnUrl=$request["return_url"];
		}else{
			$returnUrl=$module->listViewUrl($wf->id);
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