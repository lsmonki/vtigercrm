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
 * Field Access Field Model Class
 */
class Settings_FieldAccess_Field_Model extends Vtiger_Field_Model {

	const FIELD_ACCESS_ENABLED = 0;
	const FIELD_ACCESS_DISABLED = 1;
	const FIELD_ACCESS_DEFAULT_VALUE = self::FIELD_ACCESS_ENABLED;
	const FIELD_ACCESS_READONLY_DISPLAY_TYPE = 3;
	const FIELD_ACCESS_READONLY_PRESENCE = 0;

	private static $FIELD_ACCESS_READONLY_FIELD_NAMES = array('activitytype');

	public function isEnabled() {
		return ($this->get('visible') == self::FIELD_ACCESS_ENABLED);
	}

	public function isDisabled() {
		return ($this->get('visible') == self::FIELD_ACCESS_DISABLED);
	}

	public function  isReadOnly() {
		if($this->isMandatory() || $this->getDisplayType()==self::FIELD_ACCESS_READONLY_DISPLAY_TYPE
				|| $this->get('presence') == self::FIELD_ACCESS_READONLY_PRESENCE
				|| in_array($this->get('name'), self::$FIELD_ACCESS_READONLY_FIELD_NAMES)){
			return true;
		}
		return false;
	}

	/**
	 * Static Function to get the instance fo Vtiger Field Model from a given Vtiger_Field object
	 * @param Vtiger_Field $fieldObj - vtlib field object
	 * @return Vtiger_Field_Model instance
	 */
	public static function getInstanceFromFieldModel(Vtiger_Field_Model $fieldModel) {
		$objectProperties = get_object_vars($fieldModel);
		$fieldAccessModel = new Settings_FieldAccess_Field_Model();
		foreach($objectProperties as $properName=>$propertyValue) {
			$fieldAccessModel->$properName = $propertyValue;
		}
		return $fieldAccessModel;
	}
}