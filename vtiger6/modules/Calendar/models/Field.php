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
 * Calendar Field Model Class
 */
class Calendar_Field_Model extends Vtiger_Field_Model {

	/**
	 * Function returns special validator for fields
	 * @return <Array>
	 */
	function getValidator() {
		$validator = array();
		$fieldName = $this->getName();

		switch($fieldName) {
			case 'due_date':	$funcName = array('name' => 'greaterThanDependentField',
												'params' => array('date_start'));
								array_push($validator, $funcName);
								break;
			// NOTE: Letting user to add pre or post dated Event.
			/*case 'date_start' : $funcName = array('name'=>'greaterThanToday');
								array_push($validator, $funcName);
								break;*/
			default : $validator = parent::getValidator();
						break;
		}
		return $validator;
	}

	/**
	 * Function to get the Webservice Field data type
	 * @return <String> Data type of the field
	*/
	public function getFieldDataType() {
		if($this->getName() == 'date_start' || $this->getName() == 'due_date') {
			return 'datetime';
		} else if($this->get('uitype') == '30') {
			return 'reminder';
		}
		$webserviceField = $this->getWebserviceFieldObject();
		return $webserviceField->getFieldDataType();
	}

	/**
	 * Customize the display value for detail view.
	 */
	public function getDisplayValue($value, $record = false, $recordInstance = false) {
		if ($recordInstance) {
			if ($this->getName() == 'date_start') {
				$dateTimeValue = $value . ' '. $recordInstance->get('time_start');
				$value = $this->getUITypeModel()->getDisplayValue($dateTimeValue);
				list($startDate, $startTime) = explode(' ', $value);
				$time = Vtiger_Time_UIType::getTimeValueInAMorPM($startTime);

				return $startDate . ' ' . $time;
			}
		}
		return parent::getDisplayValue($value, $record);
	}

	/**
	 * Function to get Edit view display value
	 * @param <String> Data base value
	 * @return <String> value
	 */
	public function getEditViewDisplayValue($value) {
		if ($this->getName() == 'time_start' || $this->getName() == 'time_end') {
			$time = Vtiger_Time_UIType::getDisplayTimeValue($value);
			return Vtiger_Time_UIType::getTimeValueInAMorPM($time);
		}
		return parent::getEditViewDisplayValue($value);
	}
}
