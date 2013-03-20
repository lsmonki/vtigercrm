<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Workflows_ListTasksAjax_View extends Settings_Vtiger_IndexAjax_View {

	public function process(Vtiger_Request $request) {
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);

		$recordId = $request->get('record');
		$workflowModel = Settings_Workflows_Record_Model::getInstance($recordId);

		$viewer->assign('WORKFLOW_MODEL', $workflowModel);
		$viewer->assign('TASKS_LIST', $workflowModel->getTasks());
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		echo $viewer->view('ListTasks.tpl', $qualifiedModuleName, true);
	}
}