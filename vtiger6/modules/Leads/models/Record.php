<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Leads_Record_Model extends Vtiger_Record_Model {

	/**
	 * Function returns the url for converting lead
	 */
	function getConvertLeadUrl() {
		return 'index.php?module='.$this->getModuleName().'&view=ConvertLead&record='.$this->getId();
	}

	/**
	 * Static Function to get the list of records matching the search key
	 * @param <String> $searchKey
	 * @return <Array> - List of Vtiger_Record_Model or Module Specific Record Model instances
	 */
	public static function getSearchResult($searchKey, $module=false) {
		$db = PearDatabase::getInstance();

		$deletedCondition = $this->getModule()->getDeletedRecordCondition();
		$query = 'SELECT * FROM vtiger_crmentity
                    INNER JOIN vtiger_leaddetails ON vtiger_leaddetails.leadid = vtiger_crmentity.crmid
                    WHERE label LIKE ? AND '.$deletedCondition;
		$params = array("%$searchKey%");
		$result = $db->pquery($query, $params);
		$noOfRows = $db->num_rows($result);

		$moduleModels = array();
		$matchingRecords = array();
		for($i=0; $i<$noOfRows; ++$i) {
			$row = $db->query_result_rowdata($result, $i);
			$row['id'] = $row['crmid'];
			$moduleName = $row['setype'];
			if(!array_key_exists($moduleName, $moduleModels)) {
				$moduleModels[$moduleName] = Vtiger_Module_Model::getInstance($moduleName);
			}
			$moduleModel = $moduleModels[$moduleName];
			$modelClassName = Vtiger_Loader::getComponentClassName('Model', 'Record', $moduleName);
			$recordInstance = new $modelClassName();
			$matchingRecords[$moduleName][$row['id']] = $recordInstance->setData($row)->setModuleFromInstance($moduleModel);
		}
		return $matchingRecords;
	}

	/**
	 * Function returns Account fields for Lead Convert
	 * @return Array
	 */
	function getAccountFieldsForLeadConvert() {
		$accountsFields = array();
		$privilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$moduleName = 'Accounts';

		if(!Users_Privileges_Model::isPermitted($moduleName, 'EditView')) {
			return;
		}

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if ($moduleModel->isActive()) {
			$fieldModels = $moduleModel->getFields();
			foreach ($fieldModels as $fieldName => $fieldModel) {
				if($fieldModel->isMandatory() && $fieldName != 'assigned_user_id') {
					$leadMappedField = $this->getConvertLeadMappedField($fieldName, $moduleName);
					$fieldModel->set('fieldvalue', $this->get($leadMappedField));
					$accountsFields[] = $fieldModel;
				}
			}

			if($privilegeModel->hasFieldWriteAccess($moduleName, 'industry')) {
				$industryFieldModel = $moduleModel->getField('industry');
				$industryLeadMappedField = $this->getConvertLeadMappedField('industry', $moduleName);
				$industryFieldModel->set('fieldvalue', $this->get($industryLeadMappedField));
				$accountsFields[] = $industryFieldModel;
			}
		}
		return $accountsFields;
	}

	/**
	 * Function returns Contact fields for Lead Convert
	 * @return Array
	 */
	function getContactFieldsForLeadConvert() {
		$contactsFields = array();
		$privilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$moduleName = 'Contacts';

		if(!Users_Privileges_Model::isPermitted($moduleName, 'EditView')) {
			return;
		}

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if ($moduleModel->isActive()) {
			$fieldModels = $moduleModel->getFields();
					foreach($fieldModels as $fieldName => $fieldModel) {
						if($fieldModel->isMandatory() &&  $fieldName != 'assigned_user_id') {
							$leadMappedField = $this->getConvertLeadMappedField($fieldName, $moduleName);
							$fieldValue = $this->get($leadMappedField);
							if ($fieldName === 'account_id') {
								$fieldValue = $this->get('company');
							}
							$fieldModel->set('fieldvalue', $fieldValue);
					$contactsFields[] = $fieldModel;
				}
			}

			$additionalFields = array('firstname', 'email');
			foreach($additionalFields as $fieldName) {
				if($privilegeModel->hasFieldWriteAccess($moduleName, $fieldName)) {
					$leadMappedField = $this->getConvertLeadMappedField($fieldName, $moduleName);
					$fieldModel = $moduleModel->getField($fieldName);
					$fieldModel->set('fieldvalue', $this->get($leadMappedField));
					$contactsFields[] = $fieldModel;
				}
			}
		}
		return $contactsFields;
	}

	/**
	 * Function returns Potential fields for Lead Convert
	 * @return Array
	 */
	function getPotentialsFieldsForLeadConvert() {
		$potentialFields = array();
		$privilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		$moduleName = 'Potentials';

		if(!Users_Privileges_Model::isPermitted($moduleName, 'EditView')) {
			return;
		}

		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);
		if ($moduleModel->isActive()) {
			$fieldModels = $moduleModel->getFields();
			foreach($fieldModels as $fieldName => $fieldModel) {
				if($fieldModel->isMandatory() &&  $fieldName != 'assigned_user_id' && $fieldName != 'related_to') {
					$leadMappedField = $this->getConvertLeadMappedField($fieldName, $moduleName);
					$fieldModel->set('fieldvalue', $this->get($leadMappedField));
					$potentialFields[] = $fieldModel;
				}
			}

			if($privilegeModel->hasFieldWriteAccess($moduleName, 'amount')) {
				$fieldModel = $moduleModel->getField('amount');
				$amountLeadMappedField = $this->getConvertLeadMappedField('amount', $moduleName);
				$fieldModel->set('fieldvalue', $this->get($amountLeadMappedField));
				$potentialFields[] = $fieldModel;
			}
		}
		return $potentialFields;
	}

	/**
	 * Function returns field mapped to Leads field, used in Lead Convert for settings the field values
	 * @param <String> $fieldName
	 * @return <String>
	 */
	function getConvertLeadMappedField($fieldName, $moduleName) {
		$mappingFields = $this->get('mappingFields');

		if (!$mappingFields) {
			$db = PearDatabase::getInstance();
			$mappingFields = array();

			$result = $db->pquery('SELECT * FROM vtiger_convertleadmapping', array());
			$numOfRows = $db->num_rows($result);

			for($i=0; $i<$numOfRows; $i++) {
				//Lead Field Name
				$leadFieldId = $db->query_result($result, $i, 'leadfid');
				$leadFieldInstance = Vtiger_Field_Model::getInstance($leadFieldId);
				$leadFieldName = $leadFieldInstance->getName();

				//Account Field Name
				$accountFieldId = $db->query_result($result, $i, 'accountfid');
				if ($accountFieldId) {
					$accountFieldInstance = Vtiger_Field_Model::getInstance($accountFieldId);
					$mappingFields['Accounts'][$accountFieldInstance->getName()] = $leadFieldName;
				}

				//Contact Field Name
				$contactFieldId = $db->query_result($result, $i, 'contactfid');
				if ($contactFieldId) {
					$contactFieldInstance = Vtiger_Field_Model::getInstance($contactFieldId);
					$mappingFields['Contacts'][$contactFieldInstance->getName()] = $leadFieldName;
				}

				//Potential Field Name
				$potentialFieldId = $db->query_result($result, $i, 'potentialfid');
				if ($potentialFieldId) {
					$potentialFieldInstance = Vtiger_Field_Model::getInstance($potentialFieldId);
					$mappingFields['Potentials'][$potentialFieldInstance->getName()] = $leadFieldName;
				}
			}

			$this->set('mappingFields', $mappingFields);
		}

		return $mappingFields[$moduleName][$fieldName];
	}

	/**
	 * Function returns the fields required for Lead Convert
	 * @return <Array of Vtiger_Field_Model>
	 */
	function getConvertLeadFields() {
		$convertFields = array();
		$accountFields = $this->getAccountFieldsForLeadConvert();
		if(!empty($accountFields)) {
			$convertFields['Accounts'] = $accountFields;
		}

		$contactFields = $this->getContactFieldsForLeadConvert();
		if(!empty($contactFields)) {
			$convertFields['Contacts'] = $contactFields;
		}

		$potentialsFields = $this->getPotentialsFieldsForLeadConvert();
		if(!empty($potentialsFields)) {
			$convertFields['Potentials'] = $potentialsFields;
		}
		return $convertFields;
	}

	/**
	 * Function returns the url for create event
	 * @return <String>
	 */
	function getCreateEventUrl() {
		$calendarModuleModel = Vtiger_Module_Model::getInstance('Calendar');
		return $calendarModuleModel->getCreateEventRecordUrl().'&parent_id='.$this->getId();
	}

	/**
	 * Function returns the url for create todo
	 * @return <String>
	 */
	function getCreateTaskUrl() {
		$calendarModuleModel = Vtiger_Module_Model::getInstance('Calendar');
		return $calendarModuleModel->getCreateTaskRecordUrl().'&parent_id='.$this->getId();
	}

}
