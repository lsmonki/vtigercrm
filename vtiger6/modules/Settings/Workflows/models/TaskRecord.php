<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/*
 * Workflow Task Record Model Class
 */
require_once 'modules/com_vtiger_workflow/include.inc';
require_once 'modules/com_vtiger_workflow/VTTaskManager.inc';

class Settings_Workflows_TaskRecord_Model extends Settings_Vtiger_Record_Model {

	const TASK_STATUS_ACTIVE = 1;

	public function getId() {
		return $this->get('task_id');
	}

	public function getName() {
		return $this->get('summary');
	}

	public function isActive() {
		return $this->get('status') == self::TASK_STATUS_ACTIVE;
	}

	public function getTaskObject() {
		return $this->task_object;
	}

	public function setTaskObject($task) {
		$this->task_object = $task;
		return $this;
	}

	public function getEditViewUrl() {
		return '?module=Workflows&parent=Settings&view=EditTask&record='.$this->getId().'&for_workflow='.$this->getWorkflow()->getId();
	}

	public function getDeleteActionUrl() {
		return '?module=Workflows&parent=Settings&action=DeleteTask&record='.$this->getId();
	}

	public function getWorkflow() {
		return $this->workflow;
	}

	public function setWorkflowFromInstance($workflowModel) {
		$this->workflow = $workflowModel;
		return $this;
	}

	public function getTaskType() {
		if(!$this->task_type) {
			$taskObject = $this->getTaskObject();
			$taskClass = get_class($taskObject);
			$this->task_type = Settings_Workflows_TaskType_Model::getInstanceFromClassName($taskClass);
		}
		return $this->task_type;
	}
//
//	public function save() {
//		$db = PearDatabase::getInstance();
//		$wm = new VTWorkflowManager($db);
//
//		$wf = $this->getWorkflowObject();
//		$wf->description = $this->get('summary');
//		$wf->test = Zend_Json::encode($this->get('conditions'));
//		$wf->moduleName = $this->get('module_name');
//		$wf->executionConditionAsLabel($this->get('execution_condition'));
//		$wm->save($wf);
//
//		$this->set('workflow_id', $wf->id);
//	}
//
//	public function delete() {
//		$db = PearDatabase::getInstance();
//		$wm = new VTWorkflowManager($db);
//		$wm->delete($this->getId());
//	}

	public static function getAllForWorkflow($workflowModel, $active=false) {
		$db = PearDatabase::getInstance();

		$tm = new VTTaskManager($db);
		$tasks = $tm->getTasksForWorkflow($workflowModel->getId());
		$taskModels = array();
		foreach($tasks as $task) {
			if(!$active || $task->active == self::TASK_STATUS_ACTIVE) {
				$taskModels[$task->id] = self::getInstanceFromTaskObject($task, $workflowModel);
			}
		}
		return $taskModels;
	}

	public static function getInstance($taskId) {
		$db = PearDatabase::getInstance();
		$tm = new VTTaskManager($db);
		$task = $tm->retrieveTask($taskId);
		$workflowModel = Settings_Workflows_Record_Model::getInstance($task->workflowId);
		return self::getInstanceFromTaskObject($task, $workflowModel);
	}

	public static function getCleanInstance($workflowModel, $taskName) {
		$db = PearDatabase::getInstance();
		$tm = new VTTaskManager($db);
		$task = $tm->createTask($taskName, $workflowModel->getId());
		return self::getInstanceFromTaskObject($task, $workflowModel);
	}

	public static function getInstanceFromTaskObject($task, $workflowModel) {
		$taskId = $task->id;
		$summary = $task->summary;
		$status = $task->active;

		$taskModel = new self();
		return $taskModel->set('task_id', $taskId)->set('summary', $summary)->set('status', $status)
							->setTaskObject($task)->setWorkflowFromInstance($workflowModel);
	}

}
