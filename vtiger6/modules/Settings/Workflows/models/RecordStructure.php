<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Workflows_RecordStructure_Model extends Vtiger_RecordStructure_Model {

	function setWorkFlowModel($workFlowModel) {
		$this->workFlowModel = $workFlowModel;
	}

	function getWorkFlowModel() {
		return $this->workFlowModel;
	}
	/**
	 * Function to get the values in stuctured format
	 * @return <array> - values in structure array('block'=>array(fieldinfo));
	 */
	public function getStructure() {
		if(!empty($this->structuredValues)) {
			return $this->structuredValues;
		}

		$recordModel = $this->getWorkFlowModel();
		$recordId = $recordModel->getId();

		$values = array();
		$moduleModel = $this->getModule();
		$blockModelList = $moduleModel->getBlocks();
		foreach($blockModelList as $blockLabel=>$blockModel) {
			$fieldModelList = $blockModel->getFields();
			if (!empty ($fieldModelList)) {
				$values[$blockLabel] = array();
				foreach($fieldModelList as $fieldName=>$fieldModel) {
					if($fieldModel->isViewable()) {
						if(!empty($recordId)) {
							//Set the fieldModel with the valuetype for the client side.
							$fieldValueType = $recordModel->getFieldFilterValueType($fieldName);
							$fieldInfo = $fieldModel->getFieldInfo();
							$fieldInfo['workflow_valuetype'] = $fieldValueType;
							$fieldModel->setFieldInfo($fieldInfo);
						}
						// This will be used during editing task like email, sms etc
						$fieldModel->set('workflow_columnname', $fieldName)->set('workflow_columnlabel', $fieldModel->get('label'));
						// This is used to identify the field belongs to source module of workflow
						$fieldModel->set('workflow_sourcemodule_field', true);
						$values[$blockLabel][$fieldName] = $fieldModel;
					}
				}
			}
		}

		//All the reference fields should also be sent
		$fields = $moduleModel->getFieldsByType(array('reference', 'owner'));
		foreach($fields as $parentFieldName => $field) {
			$type = $field->getFieldDataType();
			$referenceModules = $field->getReferenceList();
			if($type == 'owner') $referenceModules = array('Users');
			foreach($referenceModules as $refModule) {
				$moduleModel = Vtiger_Module_Model::getInstance($refModule);
				$blockModelList = $moduleModel->getBlocks();
				foreach($blockModelList as $blockLabel=>$blockModel) {
					$fieldModelList = $blockModel->getFields();
					if (!empty ($fieldModelList)) {
						foreach($fieldModelList as $fieldName=>$fieldModel) {
							if($fieldModel->isViewable()) {
								$name = "($parentFieldName : ($refModule) $fieldName)";
								$label = $field->get('label').' : ('.$refModule.') '.$fieldModel->get('label');
								$fieldModel->set('workflow_columnname', $name)->set('workflow_columnlabel', $label);
								if(!empty($recordId)) {
									$fieldValueType = $recordModel->getFieldFilterValueType($name);
									$fieldInfo = $fieldModel->getFieldInfo();
									$fieldInfo['workflow_valuetype'] = $fieldValueType;
									$fieldModel->setFieldInfo($fieldInfo);
								}
								$values[$field->get('label')][$name] = $fieldModel;
							}
						}
					}
				}
			}
		}
		$this->structuredValues = $values;
		return $values;
	}

	/**
	 * Function returns all the email fields for the workflow record structure
	 * @return type
	 */
	public function getAllEmailFields() {
		return $this->getFieldsByType('email');
	}
	
	/**
	 * Function returns all the date time fields for the workflow record structure
	 * @return type
	 */
	public function getAllDateTimeFields() {
		$fieldTypes = array('date','datetime');
		return $this->getFieldsByType($fieldTypes);
	}
	
	/**
	 * Function returns fields based on type
	 * @return type
	 */
	public function  getFieldsByType($fieldTypes) {
		$fieldTypesArray = array();
		if(gettype($fieldTypes) == 'string'){
			array_push($fieldTypesArray,$fieldTypes);
		} else {
			$fieldTypesArray = $fieldTypes;
		}
		$structure = $this->getStructure();
		$fieldsBasedOnType = array();
		if(!empty($structure)) {
			foreach($structure as $block => $fields) {
				foreach($fields as $metaKey => $field) {
					$type = $field->getFieldDataType();
					if(in_array($type, $fieldTypesArray)){
						$fieldsBasedOnType[$metaKey] = $field;
					}
				}
			}
		}
		return $fieldsBasedOnType;
	}

	public static function getInstanceForWorkFlowModule($workFlowModel) {
		$self = new self();
		$self->setWorkFlowModel($workFlowModel);
		$self->setModule($workFlowModel->getModule());
		return $self;
	}
}