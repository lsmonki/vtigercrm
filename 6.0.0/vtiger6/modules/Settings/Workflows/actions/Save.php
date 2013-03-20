<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Workflows_Save_Action extends Vtiger_Action_Controller {

	public function process(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);

		$selectedModuleName = $request->get('module_name');
		$selectedModule = Vtiger_Module_Model::getInstance($selectedModuleName);

		$viewer->assign('module_name', $selectedModuleName);
		$viewer->assign('summary', $request->get('summary'));
		$viewer->assign('execution_condition', $request->get('execution_condition'));

		$viewer->assign('MODULE_MODEL', $selectedModule);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('DATE_FILTERS', Vtiger_Field_Model::getDateFilterTypes());
		$viewer->assign('ADVANCED_FILTER_OPTIONS', Vtiger_Field_Model::getAdvancedFilterOptions());
		$viewer->assign('ADVANCED_FILTER_OPTIONS_BY_TYPE', Vtiger_Field_Model::getAdvancedFilterOpsByFieldType());
		$viewer->assign('FIELD_EXPRESSIONS', Settings_Workflows_Module_Model::getExpressions());
		$viewer->assign('META_VARIABLES', Settings_Workflows_Module_Model::getMetaVariables());

		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		$viewer->view('CreateViewStep2.tpl', $qualifiedModuleName);

		$recordId = $request->get('record');
		$summary = $request->get('summary');
		$moduleName = $request->get('module_name');
		$conditions = $request->get('conditions');
		$executionCondition = $request->get('execution_condition');

		if($recordId) {
			$workflowModel = Settings_Workflows_Record_Model::getInstance($recordId);
		} else {
			$workflowModel = Settings_Workflows_Record_Model::getCleanInstance();
		}
		$workflowModel->set('summary', $summary);
		$workflowModel->set('module_name', $moduleName);
		$workflowModel->set('conditions', $conditions);
		$workflowModel->set('execution_condition', $executionCondition);
		$workflowModel->save();

		$redirectUrl = Settings_Workflows_Module_Model::getDefaultUrl();
		if(!$recordId) {
			$redirectUrl = $workflowModel->getEditViewUrl();
		}
		header("Location: $redirectUrl");
	}
}