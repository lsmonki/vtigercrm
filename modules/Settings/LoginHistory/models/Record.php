<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_LoginHistory_Record_Model extends Settings_Vtiger_Record_Model {
	
	/**
	 * Function to get the Id
	 * @return <Number> Profile Id
	 */
	public function getId() {
		return $this->get('login_id');
	}

	/**
	 * Function to get the Profile Name
	 * @return <String>
	 */
	public function getName() {
		return $this->get('user_name');
	}
	
	public function getAccessibleUsers(){
		$adb = PearDatabase::getInstance();
		$userRecordModel = Users_Record_Model::getCurrentUserModel();
		$usersList = $userRecordModel->getAccessibleUsers();
		$query = 'SELECT user_name FROM vtiger_users WHERE id = ?';
		$usersListArray = array();
		foreach ($usersList as $id => $name) {
			$result = $adb->pquery($query, array($id));
			if($adb->num_rows($result) > 0) {
				$usersListArray[$adb->query_result($result, 0, 'user_name')] = $name;
			}
		}
		return $usersListArray;
	}
}
