<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Leads_Field_Model extends Vtiger_Field_Model {

	/**
	 * Function to get field data type
	 * @return <String> data type
	 */
	public function getFieldDataType() {
		$fieldDataType = '';
		$uitype = $this->get('uitype');
		switch ($uitype) {
			case 7		: $fieldDataType = 'decimal';	break;
			case 9		: $fieldDataType = 'percent';	break;
			case 19		:
			case 21		: $fieldDataType = 'textArea';	break;
			case 33		: $fieldDataType = 'multiSelectCombo';	break;
			case 56		: $fieldDataType = 'checkBox';	break;
		}

		if (!$fieldDataType) {
			$webserviceField = $this->getWebserviceFieldObject();
			$fieldDataType = $webserviceField->getFieldDataType();
		}
		return $fieldDataType;
	}

	/**
	 * Function to get clean instance
	 * @return <Settings_Leads_Field_Model>
	 */
	public static function getCleanInstance() {
		return new self();
	}

	/**
	 * Function to get instance
	 * @param <String/Integer> $value
	 * @param <String> $module
	 * @return <Settings_Leads_Field_Model> field model
	 */
	public static function getInstance($value, $module) {
		$fieldModel = parent::getInstance($value, $module);
		$objectProperties = get_object_vars($fieldModel);

		$fieldModel = new self();
		foreach	($objectProperties as $properName => $propertyValue) {
			$fieldModel->$properName = $propertyValue;
		}
		return $fieldModel;
	}
}