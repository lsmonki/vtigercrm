<?
require_once("include/utils/CommonUtils.php");
require_once("include/events/SqlResultIterator.inc");
require_once("include/Zend/Json.php");
require_once("VTWorkflowApplication.inc");
require_once("VTWorkflowManager.inc");

	function vtDeleteWorkflow($adb, $request){
		$module = new VTWorkflowApplication("saveworkflow");
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