<?php
	require_once('include/workflow/sqlresultiterator.inc');
	require 'include/workflow/VTConditionalExpression.inc';
	require 'include/workflow/VTTaskManager.inc';
	class VTFireEVent{
		public function fireEvent(VTEntityData $entityData){
			global $adb;
			$data = $entityData->getData();
			$taskManager = new VTTaskManager($adb);
			$result = $this->adb->pquery("select test, task_id from workflow where module_name=?", array($this->moduleName));
			foreach(new SqlResultIterator($result) as $row){
				$expression = new VTConditionalExpression($row->test);
				if($expression->evaluate($data)){
					$task = $taskManager->retrieveTask($row->task_id);
					$task->doTask($data);
				}
			}
		}
	}
?>