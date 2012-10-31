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
 * Field Access Vtiger Module Model Class
 */
class Settings_FieldAccess_Module_Model extends Vtiger_Module_Model {

	public function getFieldPermissionsUrl() {
		return '?module=FieldAccess&view=Index&parent=Settings&record='.$this->getId();
	}

	public function getSaveFieldPermissionsUrl() {
		return '?module=FieldAccess&action=SaveAjax&parent=Settings&record='.$this->getId();
	}

	public function getFields() {
		$db = PearDatabase::getInstance();

		$sql = 'SELECT fieldid, visible FROM vtiger_def_org_field WHERE tabid = ?';
		$params = array($this->getId());
		$result = $db->pquery($sql, $params);
		$noOfFields = $db->num_rows($result);
		$fieldVisibility = array();
		for($i=0; $i<$noOfFields; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			$fieldVisibility[$row['fieldid']] = $row['visible'];
		}

		$fieldsList = parent::getFields();
		$fieldModels = array();
		foreach($fieldsList as $fieldName => $fieldModel) {
			$fieldId = $fieldModel->getId();
			$fieldAccessModel = Settings_FieldAccess_Field_Model::getInstanceFromFieldModel($fieldModel);
			$fieldAccessModel->set('visible', $fieldVisibility[$fieldId]);
			$fieldModels[$fieldName] = $fieldAccessModel;
		}
		return $fieldModels;
	}

	public function save() {
		$db = PearDatabase::getInstance();

		$fieldPermissions = $this->get('field_permissions');

		$sql = 'UPDATE vtiger_def_org_field SET visible = ? WHERE fieldid = ? AND tabid = ?';
		foreach($fieldPermissions as $fieldId => $permission) {
			$params = array($this->tranformInputPermissionValue($permission), $fieldId, $this->getId());
			$db->pquery($sql, $params);
		}
	}

	protected function tranformInputPermissionValue($value) {
		if($value) {
			return Settings_FieldAccess_Field_Model::FIELD_ACCESS_ENABLED;
		} else {
			return Settings_FieldAccess_Field_Model::FIELD_ACCESS_DISABLED;
		}
	}

	public static function getInstanceFromModuleModel(Vtiger_Module_Model $fieldModel) {
		$objectProperties = get_object_vars($fieldModel);
		$fieldAccessModel = new Settings_FieldAccess_Module_Model();
		foreach($objectProperties as $properName=>$propertyValue) {
			$fieldAccessModel->$properName = $propertyValue;
		}
		return $fieldAccessModel;
	}

	public static function getInstance($value) {
		$moduleModel = parent::getInstance($value);
		return self::getInstanceFromModuleModel($moduleModel);
	}

	/**
	 * Static Function to get the instance of Vtiger Module Model for all the modules
	 * @return <Array> - List of Vtiger Module Model or sub class instances
	 */
	public static function getAll() {
		$moduleModels = parent::getAll(array(0,2));
		$fieldAccessModuleModels = array();

		foreach($moduleModels as $tabId => $moduleModel) {
			if($moduleModel->isEntityModule()) {
				$fieldAccessModuleModels[$tabId] = self::getInstanceFromModuleModel($moduleModel);
			}
		}
		return $fieldAccessModuleModels;
	}

}
