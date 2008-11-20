<?php
	ini_set('include_path',ini_get('include_path').':../..');
	require 'include/database/PearDatabase.php';
	require_once('include/utils/CommonUtils.php');
	require_once('modules/Emails/mail.php');
	require_once('VTSimpleTemplate.inc');
	require_once 'include/events/VTEntityData.inc';
	require 'include.inc';
	
	function vtRunTaskJob($adb){
		$tq = new VTTaskQueue($adb);
		$readyTasks = $tq->getReadyTasks();
		$tm = new VTTaskManager($adb);
		foreach($readyTasks as $pair){
			list($taskId, $entityId) = $pair;
			$task = $tm->retrieveTask($taskId);
			$entity = VTEntityData::fromEntityId($adb, $entityId);
			$task->doTask($entity->getModuleName(), $entity->getData());
		}
	}
	vtRunTaskJob($adb);
?>