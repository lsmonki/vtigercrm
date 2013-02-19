<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

/**
 * Vtiger Menu Model Class
 */
class Vtiger_Menu_Model extends Vtiger_Module_Model {

	/**
	 * Static Function to get all the accessible menu models with/without ordering them by sequence
	 * @param <Boolean> $sequenced - true/false
	 * @return <Array> - List of Vtiger_Menu_Model instances
	 */
	public static function getAll($sequenced=false) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$userPrivModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$db = PearDatabase::getInstance();
		$restrictedModulesList = array('Emails', 'ProjectMilestone', 'ProjectTask', 'ModComments','RSS','Portal','Integration','PBXManager','DashBoard','Home');

		// Ondemand Specific : Restrict Language Editor for now in menu for non-admin users
		if(!$currentUser->isAdminUser()) {
			array_push($restrictedModulesList, 'LanguageEditor');
		}

		$sql = "SELECT *
				FROM vtiger_tab
				WHERE parent IS NOT NULL AND parent != '' AND presence IN (0,2)
				AND name NOT IN (".generateQuestionMarks($restrictedModulesList).")";

		// We sequence it based on pre-defined automation process
		if($sequenced) {
			$sql .= ' ORDER BY tabsequence';
		}

		$result = $db->pquery($sql, $restrictedModulesList);
		$noOfMenus = $db->num_rows($result);

		$menuModels = array();
		for($i=0; $i<$noOfMenus; ++$i) {

			$row = $db->query_result_rowdata($result,$i);
			if($userPrivModel->isAdminUser() ||
					$userPrivModel->hasGlobalReadPermission() ||
					$userPrivModel->hasModulePermission($row['tabid'])) {
				$instance = Vtiger_Module_Model::getCleanInstance($row['name']);
				$instance->set('name',$row['name']);
				$instance->set('tabsequence',$row['tabsequence']);
				$instance->set('parent',$row['parent']);
				$instance->set('id',$row['tabid']);
				$instance->set('label',$row['tablabel']);
				$instance->set('isentitytype',$row['isentitytype']);
				$menuModels[$row['name']] = $instance;
			}
		}

		return $menuModels;
	}
}
