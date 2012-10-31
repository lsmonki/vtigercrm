<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Calendar_Time_UIType extends Vtiger_Time_UIType {


	public function getEditViewDisplayValue($value) {
		if(!empty($value)) {
			return parent::getEditViewDisplayValue($value);
		}

		$specialTimeFields = array('time_start', 'time_end');

		$fieldInstance = $this->get('field')->getWebserviceFieldObject();
		$fieldName = $fieldInstance->getFieldName();

		if(!in_array($fieldName, $specialTimeFields)){
			return parent::getEditViewDisplayValue($value);
		}

		$userModel = Users_Privileges_Model::getCurrentUserModel();
		$timeZone = $userModel->get('time_zone');
		$targetTimeZone = new DateTimeZone($timeZone);
		
		$date = new DateTime();
		$date->setTimezone($targetTimeZone);
		$hour = (int)$date->format('H');
		$nextHour = $hour+1;
		
		$date->setTime($nextHour,0,0);
		
		if($fieldName == 'time_start') {
			$dateTimeField = new DateTimeField($date->format('Y-m-d H:i:s'));
			return $dateTimeField->getDisplayTime();
		}
		else if($fieldName == 'time_end') {
			$endDateHour = $nextHour + 1;
			$date->setTime($endDateHour, 0, 0);
			$dateTimeField = new DateTimeField($date->format('Y-m-d H:i:s'));
			return $dateTimeField->getDisplayTime();
		}
	}

}