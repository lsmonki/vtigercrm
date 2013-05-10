<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class Settings_Workflows_EditTask_View extends Settings_Vtiger_Index_View {

	public function process(Vtiger_Request $request) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$viewer = $this->getViewer($request);
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);

		$recordId = $request->get('task_id');
		$workflowId = $request->get('for_workflow');

		$workflowModel = Settings_Workflows_Record_Model::getInstance($workflowId);
		$taskTypes = $workflowModel->getTaskTypes();
		if($recordId) {
			$taskModel = Settings_Workflows_TaskRecord_Model::getInstance($recordId);
		} else {
			$taskType = $request->get('type');
			if(empty($taskType)) {
				$taskType = !empty($taskTypes[0]) ? $taskTypes[0]->getName() : 'VTEmailTask';
			}
			$taskModel = Settings_Workflows_TaskRecord_Model::getCleanInstance($workflowModel, $taskType);
		}

		$taskTypeModel = $taskModel->getTaskType();
		$viewer->assign('TASK_TYPE_MODEL', $taskTypeModel);

		$viewer->assign('TASK_TEMPLATE_PATH', $taskTypeModel->getTemplatePath());
		$recordStructureInstance = Settings_Workflows_RecordStructure_Model::getInstanceForWorkFlowModule($workflowModel);
		$viewer->assign('RECORD_STRUCTURE_MODEL', $recordStructureInstance);
		$viewer->assign('RECORD_STRUCTURE', $recordStructureInstance->getStructure());

		$moduleModel = $workflowModel->getModule();
		$dateTimeFields = $moduleModel->getFieldsByType(array('date', 'datetime'));
		
		$viewer->assign('SOURCE_MODULE',$moduleModel->getName());
		$viewer->assign('MODULE_MODEL', $moduleModel);
		$viewer->assign('TASK_ID',$recordId);
		$viewer->assign('WORKFLOW_ID',$workflowId);
		$viewer->assign('DATETIME_FIELDS', $dateTimeFields);
		$viewer->assign('WORKFLOW_MODEL', $workflowModel);
		$viewer->assign('TASK_TYPES', $taskTypes);
		$viewer->assign('TASK_MODEL', $taskModel);
		$viewer->assign('CURRENTDATE', date('Y-n-j'));
		$viewer->assign('META_VARIABLES', Settings_Workflows_Module_Model::getMetaVariables());
		$viewer->assign('TASK_OBJECT',$taskModel->getTaskObject());
		$viewer->assign('FIELD_EXPRESSIONS', Settings_Workflows_Module_Model::getExpressions());
		$repeat_date = $taskModel->getTaskObject()->calendar_repeat_limit_date;
		if(!empty ($repeat_date)){
		    $repeat_date = Vtiger_Date_UIType::getDisplayDateValue($repeat_date);
		}
		$viewer->assign('REPEAT_DATE',$repeat_date);
		
		$userModel = Users_Record_Model::getCurrentUserModel();
		$viewer->assign('dateFormat',$userModel->get('date_format'));

		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);

		
		
		$emailFields = $recordStructureInstance->getAllEmailFields();
		foreach($emailFields as $metaKey => $emailField) {
			$emailFieldoptions .= '<option value=",$'.$metaKey.'">'.
						'('.vtranslate($emailField->getModule()->get("name"), $emailField->getModule()->get("name")).')'.'  '.
						vtranslate($emailField->get("label"), $emailField->getModule()->get("name")).'</option>';
		}
		
		$structure = $recordStructureInstance->getStructure();
		foreach($structure as $fields) {
			foreach($fields as $field) {
				$allFieldoptions .= '<option value="$'.$field->get('workflow_columnname').'">'.
										$field->get('workflow_columnlabel').'</option>';
			}
		}

		$userList = $currentUser->getAccessibleUsers();
		$groupList = $currentUser->getAccessibleGroups();
		$assignedToValues = array();
		$assignedToValues[vtranslate('LBL_USERS', 'Vtiger')] = $userList;
		$assignedToValues[vtranslate('LBL_GROUPS', 'Vtiger')] = $groupList;

		$viewer->assign('ASSIGNED_TO', $assignedToValues);
		$viewer->assign('EMAIL_FIELD_OPTION', $emailFieldoptions);
		$viewer->assign('ALL_FIELD_OPTIONS',$allFieldoptions);
		$viewer->view('EditTask.tpl', $qualifiedModuleName);
	}
}