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
 * Settings List View Model Class
 */
class Settings_Vtiger_ListView_Model extends Vtiger_Base_Model {

	/**
	 * Function to get the Module Model
	 * @return Vtiger_Module_Model instance
	 */
	public function getModule() {
		return $this->module;
	}

	public function setModule($name) {
		$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'Module', $name);
		$this->module = new $modelClassName();
		return $this;
	}

	public function setModuleFromInstance($module) {
		$this->module = $module;
		return $this;
	}

	/**
	 * Function to get the list view header
	 * @return <Array> - List of Vtiger_Field_Model instances
	 */
	public function getListViewHeaders() {
		$module = $this->getModule();
		return $module->getListFields();
	}

	/**
	 * Function to get the list view entries
	 * @param Vtiger_Paging_Model $pagingModel
	 * @return <Array> - Associative array of record id mapped to Vtiger_Record_Model instance.
	 */
	public function getListViewEntries($pagingModel) {
		$db = PearDatabase::getInstance();

		$module = $this->getModule();
		$moduleName = $module->getName();
		$parentModuleName = $module->getParentName();
		$qualifiedModuleName = $moduleName;
		if(!empty($parentModuleName)) {
			$qualifiedModuleName = $parentModuleName.':'.$qualifiedModuleName;
		}
		$recordModelClass = Vtiger_Loader::getComponentClassName('Model', 'Record', $qualifiedModuleName);
		$listQuery = 'SELECT * FROM '. $module->baseTable;

		$startIndex = $pagingModel->getStartIndex();
		$pageLimit = $pagingModel->getPageLimit();

		$orderBy = $this->getForSql('orderby');
		if(!empty($orderBy)) {
			$listQuery .= ' ORDER BY '. $orderBy . ' ' .$this->getForSql('sortorder');
		}
		$listQuery .= " LIMIT $startIndex, $pageLimit";

		$listResult = $db->pquery($listQuery, array());
		$noOfRecords = $db->num_rows($listResult);

		$listViewRecordModels = array();
		for($i=0; $i<$noOfRecords; ++$i) {
			$row = $db->query_result_rowdata($listResult, $i);
			$record = new $recordModelClass();
			$record->setData($row);
			$listViewRecordModels[$record->getId()] = $record;
		}
		$pagingModel->calculatePageRange($listViewRecordModels);
		return $listViewRecordModels;
	}

	/**
	 * Function to get the instance of Settings module model
	 * @return Settings_Vtiger_Module_Model instance
	 */
	public static function getInstance($name='Settings:Vtiger') {
		$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'ListView', $name);
		$instance = new $modelClassName();
		return $instance->setModule($name);
	}
}