<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Roles_Save_Action extends Vtiger_Action_Controller {

	public function process(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$qualifiedModuleName = $request->getModule(false);
		$recordId = $request->get('record');

		$moduleModel = Settings_Vtiger_Module_Model::getInstance($qualifiedModuleName);
		if(!empty($recordId)) {
			$recordModel = Settings_Roles_Record_Model::getInstanceById($recordId);
		} else {
			$recordModel = new Settings_Roles_Record_Model();
		}
		$parentRoleId = $request->get('parent_roleid');
		if($recordModel && !empty($parentRoleId)) {
			$parentRole = Settings_Roles_Record_Model::getInstanceById($parentRoleId);
			$roleName = $request->get('rolename');
			$roleProfiles = $request->get('profiles');
			if($parentRole && !empty($roleName) && !empty($roleProfiles)) {
				$recordModel->set('rolename', $roleName);
				$recordModel->set('profileIds', $roleProfiles);
				$parentRole->addChildRole($recordModel);
			}
		}

		$redirectUrl = $moduleModel->getDefaultUrl();
		header("Location: $redirectUrl");
	}
}
