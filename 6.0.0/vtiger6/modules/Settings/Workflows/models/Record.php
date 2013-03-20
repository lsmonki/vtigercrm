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
 * Workflow Record Model Class
 */
require_once 'modules/com_vtiger_workflow/include.inc';
require_once 'modules/com_vtiger_workflow/expression_engine/VTExpressionsManager.inc';

class Settings_Workflows_Record_Model extends Settings_Vtiger_Record_Model {

	public function getId() {
		return $this->get('workflow_id');
	}

	public function getName() {
		return $this->get('summary');
	}

	public function get($key) {
		if($key == 'execution_condition_label') {
			$executionCondition = $this->get('execution_condition');
			$executionConditionAsLabel = Settings_Workflows_Module_Model::$triggerTypes[$executionCondition];
			return Vtiger_Language_Handler::getTranslatedString($executionConditionAsLabel, 'Workflows');
		}
		if($key == 'module_label') {
			$moduleName = $this->get('module_name');
			return Vtiger_Language_Handler::getTranslatedString($moduleName, $moduleName);
		}
		return parent::get($key);
	}

	public function getEditViewUrl() {
		return '?module=Workflows&parent=Settings&view=Edit&record='.$this->getId();
	}

	public function getTasksListUrl() {
		return '?module=Workflows&parent=Settings&view=ListTasksAjax&record='.$this->getId();
	}

	public function getAddTaskUrl() {
		return '?module=Workflows&parent=Settings&view=EditTask&for_workflow='.$this->getId();
	}

	protected function setWorkflowObject($wf) {
		$this->workflow_object = $wf;
		return $this;
	}

	protected function getWorkflowObject() {
		return $this->workflow_object;
	}

	public function getModule() {
		return $this->module;
	}

	public function setModule($moduleName) {
		$this->module = Vtiger_Module_Model::getInstance($moduleName);
		return $this;
	}

	public function getTasks($active=false) {
		return Settings_Workflows_TaskRecord_Model::getAllForWorkflow($this, $active);
	}

	public function getTaskTypes() {
		return Settings_Workflows_TaskType_Model::getAllForModule($this->getModule());
	}

	public function save() {
		$db = PearDatabase::getInstance();
		$wm = new VTWorkflowManager($db);

		$wf = $this->getWorkflowObject();
		$wf->description = $this->get('summary');
		$wf->test = Zend_Json::encode($this->get('conditions'));
		$wf->moduleName = $this->get('module_name');
		$wf->executionConditionAsLabel($this->get('execution_condition'));
		$wm->save($wf);

		$this->set('workflow_id', $wf->id);
	}

	public function delete() {
		$db = PearDatabase::getInstance();
		$wm = new VTWorkflowManager($db);
		$wm->delete($this->getId());
	}

	/**
	 * Function to get the list view actions for the record
	 * @return <Array> - Associate array of Vtiger_Link_Model instances
	 */
	public function getRecordLinks() {

		$links = array();

		$recordLinks = array(
			array(
				'linktype' => 'LISTVIEWRECORD',
				'linklabel' => 'LBL_EDIT_RECORD',
				'linkurl' => $this->getEditViewUrl(),
				'linkicon' => 'icon-pencil'
			),
			array(
				'linktype' => 'LISTVIEWRECORD',
				'linklabel' => 'LBL_RECORD_STATUS',
				'linkurl' => '',
				'linkicon' => 'icon-eye-open'
			),
			array(
				'linktype' => 'LISTVIEWRECORD',
				'linklabel' => 'LBL_DELETE_RECORD',
				'linkurl' => 'javascript:Vtiger_List_Js.deleteRecord('.$this->getId().');',
				'linkicon' => 'icon-trash'
			)
		);
		foreach($recordLinks as $recordLink) {
			$links[] = Vtiger_Link_Model::getInstanceFromValues($recordLink);
		}

		return $links;
	}

	public static function getInstance($workflowId) {
		$db = PearDatabase::getInstance();
		$wm = new VTWorkflowManager($db);
		$wf = $wm->retrieve($workflowId);
		return self::getInstanceFromWorkflowObject($wf);
	}

	public static function getCleanInstance($moduleName) {
		$db = PearDatabase::getInstance();
		$wm = new VTWorkflowManager($db);
		$wf = $wm->newWorkflow($moduleName);
		return self::getInstanceFromWorkflowObject($wf);
	}

	public static function getInstanceFromWorkflowObject($wf) {
		$workflowModel = new self();

		$workflowModel->set('summary', $wf->description);
		$workflowModel->set('conditions', Zend_Json::decode($wf->test));
		$workflowModel->set('execution_condition', $wf->executionCondition);
		$workflowModel->set('module_name', $wf->moduleName);
		$workflowModel->set('workflow_id', $wf->id);
		$workflowModel->setWorkflowObject($wf);
		$workflowModel->setModule($wf->moduleName);
		return $workflowModel;
	}

}
