<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Users_Record_Model extends Vtiger_Record_Model {

	/**
	 * Gets the value of the key . First it will check whether specified key is a property if not it
	 *  will get from normal data attribure from base class
	 * @param <string> $key - property or key name
	 * @return <object>
	 */
	public function get($key) {
		if(property_exists($this, $key)) {
			return $this->$key;
		}
		return parent::get($key);
	}

	/**
	 * Function to get the Detail View url for the record
	 * @return <String> - Record Detail View Url
	 */
	public function getDetailViewUrl() {
		$module = $this->getModule();
		return 'index.php?module='.$this->getModuleName().'&parent=Settings&view='.$module->getDetailViewName().'&record='.$this->getId();
	}

	/**
	 * Function to get the Edit View url for the record
	 * @return <String> - Record Edit View Url
	 */
	public function getEditViewUrl() {
		$module = $this->getModule();
		return 'index.php?module='.$this->getModuleName().'&parent=Settings&view='.$module->getEditViewName().'&record='.$this->getId();
	}

	/**
	 * Function to get the Delete Action url for the record
	 * @return <String> - Record Delete Action Url
	 */
	public function getDeleteUrl() {
		$module = $this->getModule();
		return 'index.php?module='.$this->getModuleName().'&parent=Settings&action='.$module->getDeleteActionName().'&record='.$this->getId();
	}

	/**
	 * Function to check whether the user is an Admin user
	 * @return <Boolean> true/false
	 */
	public function isAdminUser() {
		$adminStatus = $this->get('is_admin');
		if ($adminStatus == 'on') {
			return true;
		}
		return false;
	}

	/**
	 * Function to get the module name
	 * @return <String> Module Name
	 */
	public function getModuleName() {
		$module = $this->getModule();
		if($module) {
			return parent::getModuleName();
		}
		//get from the class propety module_name
		return $this->get('module_name');
	}

	/**
	 * Function to save the current Record Model
	 */
	public function save() {
		parent::save();

		$this->saveTagCloud();
	}


	/**
	 * Function to get all the Home Page components list
	 * @return <Array> List of the Home Page components
	 */
	public function getHomePageComponents() {
		$entity = $this->getEntity();
		$homePageComponents = $entity->getHomeStuffOrder($this->getId());
		return $homePageComponents;
	}

	/**
	 * Static Function to get the instance of the User Record model for the current user
	 * @return Users_Record_Model instance
	 */
	public static function getCurrentUserModel() {
		//TODO : Remove the global dependency
		$currentUser = vglobal('current_user');
		if(!empty($currentUser)) {
			return self::getInstanceFromUserObject($currentUser);
		}
		return new self();
	}

	/**
	 * Static Function to get the instance of the User Record model from the given Users object
	 * @return Users_Record_Model instance
	 */
	public static function getInstanceFromUserObject($userObject) {
		$objectProperties = get_object_vars($userObject);
		$userModel = new self();
		foreach($objectProperties as $properName=>$propertyValue){
			$userModel->$properName = $propertyValue;
		}
		return $userModel->setData($userObject->column_fields)->setModule('Users')->setEntity($userObject);
	}

	/**
	 * Static Function to get the instance of all the User Record models
	 * @return <Array> - List of Users_Record_Model instances
	 */
	public static function getAll($onlyActive=true) {
		$db = PearDatabase::getInstance();

		$sql = 'SELECT id FROM vtiger_users';
		$params = array();
		if($onlyActive) {
			$sql .= ' WHERE status = ?';
			$params[] = 'Active';
		}
		$result = $db->pquery($sql, $params);

		$noOfUsers = $db->num_rows($result);
		$users = array();
		if($noOfUsers > 0) {
			$focus = new Users();
			for($i=0; $i<$noOfUsers; ++$i) {
				$userId = $db->query_result($result, $i, 'id');
				$focus->id = $userId;
				$focus->retrieve_entity_info($userId, 'Users');

				$userModel = self::getInstanceFromUserObject($focus);
				$users[$userModel->getId()] = $userModel;
			}
		}
		return $users;
	}

	/**
	 * Function returns the Subordinate users
	 * @return <Array>
	 */
	function getSubordinateUsers() {
		$privilegesModel = $this->get('privileges');

		if(empty($privilegesModel)) {
			$privilegesModel = Users_Privileges_Model::getInstanceById($this->getId());
			$this->set('privileges', $privilegesModel);
		}

		$subordinateUsers = array();
		$subordinateRoleUsers = $privilegesModel->get('subordinate_roles_users');
		if($subordinateRoleUsers) {
			foreach($subordinateRoleUsers as $role=>$users) {
				foreach($users as $user) {
					$subordinateUsers[$user] = $privilegesModel->get('first_name').' '.$privilegesModel->get('last_name');
				}
			}
		}
		return $subordinateUsers;
	}

	/**
	 * Function returns the Users Parent Role
	 * @return <String>
	 */
	function getParentRoleSequence() {
		$privilegesModel = $this->get('privileges');

		if(empty($privilegesModel)) {
			$privilegesModel = Users_Privileges_Model::getInstanceById($this->getId());
			$this->set('privileges', $privilegesModel);
		}

		return $privilegesModel->get('parent_role_seq');
	}

	/**
	 * Function returns the Users Current Role
	 * @return <String>
	 */
	function getRole() {
		$privilegesModel = $this->get('privileges');

		if(empty($privilegesModel)) {
			$privilegesModel = Users_Privileges_Model::getInstanceById($this->getId());
			$this->set('privileges', $privilegesModel);
		}

		return $privilegesModel->get('roleid');
	}

	/**
	 * Function returns List of Accessible Users for a Module
	 * @param <String> $module
	 * @return <Array of Users_Record_Model>
	 */
	public function getAccessibleUsersForModule($module) {

		$currentUser = Users_Record_Model::getCurrentUserModel();
		$curentUserPrivileges = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		if($currentUser->isAdminUser() || $curentUserPrivileges->hasGlobalWritePermission()) {
			$users = $this->getAccessibleUsers();
		} else {
			$sharingAccessModel = Settings_SharingAccess_Module_Model::getInstance($module);
			if($sharingAccessModel->isPrivate()) {
				$users = $this->getAccessibleUsers('private');
			} else {
				$users = $this->getAccessibleUsers();
			}
		}
		return $users;
	}

	/**
	 * Function returns List of Accessible Users for a Module
	 * @param <String> $module
	 * @return <Array of Users_Record_Model>
	 */
	public function getAccessibleGroupForModule($module) {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$curentUserPrivileges = Users_Privileges_Model::getCurrentUserPrivilegesModel();

		if($currentUser->isAdminUser() || $curentUserPrivileges->hasGlobalWritePermission()) {
			$groups = $this->getAccessibleGroups();
		} else {
			$sharingAccessModel = Settings_SharingAccess_Module_Model::getInstance($module);
			if($sharingAccessModel->isPrivate()) {
				$groups = $this->getAccessibleGroups('private');
			} else {
				$groups = $this->getAccessibleGroups();
			}
		}
		return $groups;
	}

	/**
	 * Function to get Images Data
	 * @return <Array> list of Image names and paths
	 */
	public function getImageDetails() {
		$currentUserModel = Users_Record_Model::getCurrentUserModel();

		$db = PearDatabase::getInstance();
		$imageDetails = array();
		$recordId = $this->getId();

		if ($recordId) {
			$query = "SELECT vtiger_attachments.* FROM vtiger_attachments
            LEFT JOIN vtiger_salesmanattachmentsrel ON vtiger_salesmanattachmentsrel.attachmentsid = vtiger_attachments.attachmentsid
            WHERE vtiger_salesmanattachmentsrel.smid=?";

			$result = $db->pquery($query, array($recordId));

			$imageId = $db->query_result($result, 0, 'attachmentsid');
			$imagePath = $db->query_result($result, 0, 'path');
			$imageName = $db->query_result($result, 0, 'name');

			//decode_html - added to handle UTF-8 characters in file names
			$imageOriginalName = decode_html($imageName);

			$imageDetails[] = array(
					'id' => $imageId,
					'orgname' => $imageOriginalName,
					'path' => $imagePath.$imageId,
					'name' => $imageName
			);
		}
		return $imageDetails;
	}


    /**
	 * Function to get all the accessible users
	 * @return <Array>
	 */
	public function getAccessibleUsers($private="") {
		//TODO:Remove dependence on $_REQUEST for the module name in the below API
		return get_user_array(false, "ACTIVE", "", $private);
	}

	/**
	 * Function to get all the accessible groups
	 * @return <Array>
	 */
	public function getAccessibleGroups($private="") {
		//TODO:Remove dependence on $_REQUEST for the module name in the below API
		return get_group_array(false, "ACTIVE", "", $private);
	}

	/**
	 * Function to get privillage model
	 * @return $privillage model
	 */
	public function getPrivileges() {
		$privilegesModel = $this->get('privileges');

		if (empty($privilegesModel)) {
			$privilegesModel = Users_Privileges_Model::getInstanceById($this->getId());
			$this->set('privileges', $privilegesModel);
		}

		return $privilegesModel;
	}

	/**
	 * Function to get user default activity view
	 * @return <String>
	 */
	public function getActivityView() {
		$activityView = $this->get('activity_view');
		return $activityView;
	}

	/**
	 * Function to delete corresponding image
	 * @param <type> $imageId
	 */
	public function deleteImage($imageId) {
		$db = PearDatabase::getInstance();

		$checkResult = $db->pquery('SELECT smid FROM vtiger_salesmanattachmentsrel WHERE attachmentsid = ?', array($imageId));
		$smId = $db->query_result($checkResult, 0, 'smid');

		if ($this->getId() === $smId) {
			$db->pquery('DELETE FROM vtiger_attachments WHERE attachmentsid = ?', array($imageId));
			$db->pquery('DELETE FROM vtiger_salesmanattachmentsrel WHERE attachmentsid = ?', array($imageId));
			return true;
		}
		return false;
	}


	/**
	 * Function to get the Day Starts picklist values
	 * @param type $name Description
	 */
	public static function getDayStartsPicklistValues($stucturedValues){

		$fieldModel = $stucturedValues['LBL_CALENDAR_SETTINGS'];
		$hour_format = $fieldModel['hour_format']->getPicklistValues();
		$start_hour = $fieldModel['start_hour']->getPicklistValues();

		$defaultValues = array('00:00'=>'12:00 AM','01:00'=>'01:00 AM','02:00'=>'02:00 AM','03:00'=>'03:00 AM','04:00'=>'04:00 AM','05:00'=>'05:00 AM',
					'06:00'=>'06:00 AM','07:00'=>'07:00 AM','08:00'=>'08:00 AM','09:00'=>'09:00 AM','10:00'=>'10:00 AM','11:00'=>'11:00 AM','12:00'=>'12:00 PM',
					'13:00'=>'01:00 PM','14:00'=>'02:00 PM','15:00'=>'03:00 PM','16:00'=>'04:00 PM','17:00'=>'05:00 PM','18:00'=>'06:00 PM','19:00'=>'07:00 PM',
					'20:00'=>'08:00 PM','21:00'=>'09:00 PM','22:00'=>'10:00 PM','23:00'=>'11:00 PM');

		$picklistDependencyData = array();
		foreach ($hour_format as $value) {
			if($value == 24){
				$picklistDependencyData['hour_format'][$value]['start_hour'] = $start_hour;
			}else{
				$picklistDependencyData['hour_format'][$value]['start_hour'] = $defaultValues;
			}
		}
		if(empty($picklistDependencyData['hour_format']['__DEFAULT__']['start_hour'])) {
			$picklistDependencyData['hour_format']['__DEFAULT__']['start_hour'] = $defaultValues;
		}
		return $picklistDependencyData;
	}

	/**
	 * Function returns if tag cloud is enabled or not
	 */
	function getTagCloudStatus() {
		$db = PearDatabase::getInstance();
		$query = "SELECT visible FROM vtiger_homestuff WHERE userid=? AND stufftype='Tag Cloud'";
		$visibility = $db->query_result($db->pquery($query, array($this->getId())), 0, 'visible');
		if($visibility == 0) {
			return true;
		} 
		return false; 
	}

	/**
	 * Function saves tag cloud
	 */
	function saveTagCloud() {
		$db = PearDatabase::getInstance();
		$db->pquery("UPDATE vtiger_homestuff SET visible = ? WHERE userid=? AND stufftype='Tag Cloud'",
				array($this->get('tagcloud'), $this->getId()));
	}

	/**
	 * Function to get user groups
	 * @param type $userId
	 * @return <array> - groupId's
	 */
	public static function getUserGroups($userId){
		$db = PearDatabase::getInstance();
		$groupIds = array();
		$query = "SELECT groupid FROM vtiger_users2group WHERE userid=?";
		$result = $db->pquery($query, array($userId));
		for($i=0; $i<$db->num_rows($result); $i++){
			$groupId = $db->query_result($result, $i, 'groupid');
			$groupIds[] = $groupId;
		}
		return $groupIds;
	}
}