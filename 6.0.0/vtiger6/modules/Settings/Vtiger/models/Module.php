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
 * Settings Module Model Class
 */
class Settings_Vtiger_Module_Model extends Vtiger_Base_Model {

	var $baseTable = 'vtiger_settings_field';
	var $baseIndex = 'fieldid';
	var $listFields = array('name' => 'Name', 'description' => 'Description');
	var $nameFields = array('name');

	public function getName() {
		return 'Vtiger';
	}

	public function getParentName() {
		return 'Settings';
	}

	public function getBaseTable() {
		return $this->baseTable;
	}

	public function getBaseIndex() {
		return $this->baseIndex;
	}

	public function setListFields($fieldNames) {
		$this->listFields = $fieldNames;
		return $this;
	}

	public function getListFields() {
		if(!$this->listFieldModels) {
			$fields = $this->listFields;
			$fieldObjects = array();
			foreach($fields as $fieldName => $fieldLabel) {
				$fieldObjects[$fieldName] = new Vtiger_Base_Model(array('name' => $fieldName, 'label' => $fieldLabel));
			}
			$this->listFieldModels = $fieldObjects;
		}
		return $this->listFieldModels;
	}

	public function getNameFields() {
		if(!$this->nameFieldModels) {
			$fields = $this->nameFields;
			$fieldObjects = $this->getListFields();
			$nameFieldObjects = array();
			foreach($fields as $fieldName) {
				$nameFieldObjects[$fieldName] = $fieldObjects[$fieldName];
			}
			$this->nameFieldModels = $nameFieldObjects;
		}
		return $this->nameFieldModels;
	}

	/**
	 * Function to get all the Settings menus
	 * @return <Array> - List of Settings_Vtiger_Menu_Model instances
	 */
	public function getMenus() {
		return Settings_Vtiger_Menu_Model::getAll();
	}

	/**
	 * Function to get all the Settings menu items for the given menu
	 * @return <Array> - List of Settings_Vtiger_MenuItem_Model instances
	 */
	public function getMenuItems($menu=false) {
		$menuModel = false;
		if($menu) {
			$menuModel = Settings_Vtiger_Menu_Model::getInstance($menu);
		}
		return Settings_Vtiger_MenuItem_Model::getAll($menuModel);
	}

	/**
	 * Function to get the instance of Settings module model
	 * @return Settings_Vtiger_Module_Model instance
	 */
	public static function getInstance($name='Settings:Vtiger') {
		$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'Module', $name);
		return new $modelClassName();
	}
}
