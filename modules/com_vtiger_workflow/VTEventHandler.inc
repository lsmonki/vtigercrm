<?php
	require_once('include/events/SqlResultIterator.inc');
	require_once('VTWorkflowManager.inc');
	require_once('VTTaskManager.inc');
	require_once('VTTaskQueue.inc');
	require_once('VTEntityCache.inc');
	/*
	* VTEventHandler 
	*/
	class VTWorkflowEventHandler extends VTEventHandler{
		
		
		
		/**
		 * Push tasks to the task queue if the conditions are true
		 * @param $entityData A VTEntityData object representing the entity.
		 */
		function handleEvent($eventName, $entityData){
			global $adb;
			$isNew = $entityData->isNew();
			$entityCache = new VTEntityCache($adb);
			$entityData = $entityCache->forId($entityData->getId());
			$data = $entityData->getData();
			$wfs = new VTWorkflowManager($adb);
			$workflows = $wfs->getWorkflowsForModule($entityData->getModuleName());
			$tm = new VTTaskManager($adb);
			$taskQueue = new VTTaskQueue($adb);
			
			foreach($workflows as $workflow){
				switch($workflow->executionCondition){
					case VTWorkflowManager::$ON_FIRST_SAVE:{
						if($isNew){
							$doEvaluate = true;
						}else{
							$doEvaluate = false;
						}
						break;
					}
					case VTWorkflowManager::$ONCE:{
						$result = $adb->pquery("SELECT * FROM com_vtiger_workflow_activatedonce 
							WHERE entity_id=? and workflow_id=?", array($entityData->getId(), $workflow->id));
						if($adb->num_rows($result)===0){
							$adb->pquery("INSERT INTO com_vtiger_workflow_activatedonce (entity_id, workflow_id)
								VALUES (?,?)", array($entityData->getId(), $workflow->id));
							$doEvaluate = true;
						}else{
							$doEvaluate = false;
						}
						break;
					}
					case VTWorkflowManager::$ON_EVERY_SAVE:{
						$doEvaluate = true;
						break;
					}
					default:{
						throw new Exception("Should never come here! Execution Condition:".$workflow->executionCondition);
					}
					
				}
				if($doEvaluate && $workflow->evaluate($entityCache, $entityData->getId())){
					$tasks = $tm->getTasksForWorkflow($workflow->id);
					foreach($tasks as $task){
						$trigger = $task->trigger;
						if($trigger != null){
							$delay = strtotime($data[$trigger['field']])+$trigger['days']*86400;
						}else{
							$delay = 0;
						}
						if($task->executeImmediately==true){
							$task->doTask($entityData);
						}else{
							$taskQueue->queueTask($task->id, $entityData->getId(), $delay);
						}
						
					}
				}
			}
		}
	}
?>
