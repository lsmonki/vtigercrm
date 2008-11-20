<?php
require_once("Smarty_setup.php");
require_once("include/utils/CommonUtils.php");
require_once("include/events/SqlResultIterator.inc");

require_once("VTTaskManager.inc");
	function vtSaveTask($adb, $request){
		global $log;
		$log->fatal(print_r($request, true));
		
		$tm = new VTTaskManager($adb);
		if(isset($request["task_id"])){
			$task = $tm->retrieveTask($request["task_id"]);
		}else{
			$taskType = $request["task_type"];
			$workflowId = $request["workflow_id"];
			$task = $tm->createTask($taskType, $workflowId);
		}
		$task->summary = $request["summary"];
		
		if($request["active"]=="true"){
			$task->active=true;
		}else if($request["active"]=="false"){
			$task->active=false;
		}
		global $log;
		$log->fatal("Hello");
		if(isset($request['check_select_date'])){
			$trigger = array(
				'days'=>($request['select_date_direction']=='after'?1:-1)*(int)$request['select_date_days'],
				'field'=>$request['select_date_field']
				); 
			$task->trigger=$trigger;
			global $log;
			$log->fatal($trigger);
		}
		
		$fieldNames = $task->getFieldNames();
		foreach($fieldNames as $fieldName){
			$task->$fieldName = $request[$fieldName];
		}
		$tm->saveTask($task);
		
		if(isset($request["return_url"])){
			$returnUrl=$request["return_url"];
		}else{
			$returnUrl=$module->editTaskUrl($task->id);
		}
		
		?>
		<script type="text/javascript" charset="utf-8">
			window.location="<?=$returnUrl?>";
		</script>
		<a href="<?=$returnUrl?>">Return</a>
		<?php
	}
vtSaveTask($adb, $_REQUEST);
?>