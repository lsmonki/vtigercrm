<?php

/* +***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 * *********************************************************************************** */

class Users_Module_Model extends Vtiger_Module_Model {

	/**
	 * Function to get list view query for popup window
	 * @param <String> $sourceModule Parent module
	 * @param <String> $field parent fieldname
	 * @param <Integer> $record parent id
	 * @param <String> $listQuery
	 * @return <String> Listview Query
	 */
	public function getQueryByModuleField($sourceModule, $field, $record, $listQuery) {
		if ($sourceModule == 'Users' && $field == 'reports_to_id') {
			$overRideQuery = $listQuery . " AND vtiger_users.id != " . $record;
			return $overRideQuery;
		}
	}

	/**
	 * Function searches the records in the module, if parentId & parentModule
	 * is given then searches only those records related to them.
	 * @param <String> $searchValue - Search value
	 * @param <Integer> $parentId - parent recordId
	 * @param <String> $parentModule - parent module name
	 * @return <Array of Users_Record_Model>
	 */
	public function searchRecord($searchValue, $parentId = false, $parentModule = false) {
		if (!empty($searchValue)) {
			$db = PearDatabase::getInstance();

			$query = 'SELECT * FROM vtiger_users WHERE (first_name LIKE ? OR last_name LIKE ?) AND status = ?';
			$params = array("%$searchValue%", "%$searchValue%", 'Active');

			$result = $db->pquery($query, $params);
			$noOfRows = $db->num_rows($result);

			$matchingRecords = array();
			for ($i = 0; $i < $noOfRows; ++$i) {
				$row = $db->query_result_rowdata($result, $i);
				$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'Record', 'Users');
				$recordInstance = new $modelClassName();
				$matchingRecords['Users'][$row['id']] = $recordInstance->setData($row)->setModuleFromInstance($this);
			}
			return $matchingRecords;
		}
	}

	/**
	 * Function returns the default column for Alphabetic search 
	 * @return <String> columnname
	 */
	public function getAlphabetSearchField() {
		return 'last_name';
	}

	/**
	 * Function to get the url for the Create Record view of the module
	 * @return <String> - url
	 */
	public function getCreateRecordUrl() {
		return 'index.php?module=' . $this->get('name') . '&parent=Settings&view=' . $this->getEditViewName();
	}

	public function checkDuplicateUser($userName){
		$db = PearDatabase::getInstance();
		
		$query = 'SELECT user_name FROM vtiger_users WHERE user_name = ?';
		$result = $db->pquery($query, array($userName));
		if($db->num_rows($result) > 0){
			return true;
		}
		return false;
	}
	
	/**
	 * Function to delete a given record model of the current module
	 * @param Vtiger_Record_Model $recordModel
	 */
	public function deleteRecord(Vtiger_Record_Model $recordModel) {
		$db = PearDatabase::getInstance();
		$moduleName = $this->get('name');
		$date_var = date('Y-m-d H:i:s');
        $query = "UPDATE vtiger_users SET status=?, date_modified=?, modified_user_id=? WHERE id=?";
        $db->pquery($query, array('Inactive', $adb->formatDate($date_var, true), $recordModel->getId(), $recordModel->getId()), true,"Error marking record deleted: ");
	}
	
	/**
	 * Function to get the url for list view of the module
	 * @return <string> - url
	 */
	public function getListViewUrl() {
		return 'index.php?module='.$this->get('name').'&parent=Settings&view='.$this->getListViewName();
	}

}