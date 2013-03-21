<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
include_once dirname(__FILE__).'/../../../../vtlib/Vtiger/Field.php';

/**
 * Vtiger Field Model Class
 */
class Vtiger_Field_Model extends Vtiger_Field {

	var $webserviceField = false;
	const REFERENCE_TYPE = 'reference';

    const QUICKCREATE_MANDATORY = 0;
    const QUICKCREATE_NOT_ENABLED = 1;
    const QUICKCREATE_ENABLED = 2;
    const QUICKCREATE_NOT_PERMITTED = 3;

	/**
	 * Function to get the value of a given property
	 * @param <String> $propertyName
	 * @return <Object>
	 * @throws Exception
	 */
	public function get($propertyName) {
		if(property_exists($this,$propertyName)) {
			return $this->$propertyName;
		}
		return null;
	}

	/**
	 * Function which sets value for given name
	 * @param <String> $name - name for which value need to be assinged
	 * @param <type> $value - values that need to be assigned
	 * @return Vtiger_Field_Model
	 */
	public function set($name, $value) {
		$this->$name = $value;
		return $this;
	}

	/**
	 * Function to get the Field Id
	 * @return <Number>
	 */
	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	/**
	 * Function to retrieve full data
	 * @return <array>
	 */
	public function getData(){
		return get_object_vars($this);
	}

	public function getModule() {
		if(!$this->module) {
			$moduleObj = $this->block->module;
			$this->module = Vtiger_Module_Model::getInstanceFromModuleObject($moduleObj);
		}
		return $this->module;
	}

	/**
	 * Function to retieve display value for a value
	 * @param <String> $value - value which need to be converted to display value
	 * @return <String> - converted display value
	 */
	public function getDisplayValue($value, $record=false, $recordInstance = false) {
		if(!$this->uitype_instance) {
			$this->uitype_instance = Vtiger_Base_UIType::getInstanceFromField($this);
		}
		$uiTypeInstance = $this->uitype_instance;
		return $uiTypeInstance->getDisplayValue($value, $record, $recordInstance);
	}

	/**
	 * Function to retrieve display type of a field
	 * @return <String> - display type of the field
	 */
	public function getDisplayType() {
		return $this->get('displaytype');
	}

	/**
	 * Function to get the Webservice Field Object for the current Field Object
	 * @return WebserviceField instance
	 */
	public function getWebserviceFieldObject() {
		if($this->webserviceField == false) {
			$db = PearDatabase::getInstance();

			$row = array();
			$row['uitype'] = $this->get('uitype');
			$row['block'] = $this->get('block');
			$row['tablename'] = $this->get('table');
			$row['columnname'] = $this->get('column');
			$row['fieldname'] = $this->get('name');
			$row['fieldlabel'] = $this->get('label');
			$row['displaytype'] = $this->get('displaytype');
			$row['masseditable'] = $this->get('masseditable');
			$row['typeofdata'] = $this->get('typeofdata');
			$row['presence'] = $this->get('presence');
			$row['tabid'] = $this->getModuleId();
			$row['fieldid'] = $this->get('id');
			$row['readonly'] = !$this->getProfileReadWritePermission();
			$row['defaultvalue'] = $this->get('defaultvalue');

			$this->webserviceField = WebserviceField::fromArray($db, $row);
		}
		return $this->webserviceField;
	}

	/**
	 * Function to get the Webservice Field data type
	 * @return <String> Data type of the field
	 */
	public function getFieldDataType() {
		if($this->get('uitype') == '69') {
			return 'image';
		} else if($this->get('uitype') == '26') {
			return 'documentsFolder';
		} else if($this->get('uitype') == '27') {
			return 'fileLocationType';
		} else if($this->get('uitype') == '9') {
			return 'percentage';
		} else if($this->get('uitype') == '28') {
			return 'documentsFileUpload';
		} else if($this->get('uitype') == '83') {
			return 'productTax';
		} else if($this->get('uitype') == '117') {
			return 'currencyList';
		} else if($this->get('uitype') == '55' && $this->getName() === 'salutationtype') {
			return 'picklist';
		} else if($this->get('uitype') == '55' && $this->getName() === 'firstname') {
			return 'salutation';
		}
		$webserviceField = $this->getWebserviceFieldObject();
		return $webserviceField->getFieldDataType();
	}

	/**
	 * Function to get list of modules the field refernced to
	 * @return <Array> -  list of modules for which field is refered to
	 */
	public function getReferenceList() {
		$webserviceField = $this->getWebserviceFieldObject();
		return $webserviceField->getReferenceList();
	}

	/**
	 * Function to check if the field is named field of the module
	 * @return <Boolean> - True/False
	 */
	public function isNameField() {
		$moduleNameFields = $this->getModule()->getNameFields();
		if(in_array($this->get('name'), $moduleNameFields)) {
			return true;
		}
		return false;
	}

	/**
	 * Function to check whether the current field is read-only
	 * @return <Boolean> - true/false
	 */
	public function isReadOnly() {
		$webserviceField = $this->getWebserviceFieldObject();
		return $webserviceField->isReadOnly();
	}

	/**
	 * Function to get the UI Type model for the uitype of the current field
	 * @return Vtiger_Base_UIType or UI Type specific model instance
	 */
	public function getUITypeModel() {
		return Vtiger_Base_UIType::getInstanceFromField($this);
	}

	/**
	 * Function to get all the available picklist values for the current field
	 * @return <Array> List of picklist values if the field is of type picklist or multipicklist, null otherwise.
	 */
	public function getPicklistValues() {
			$fieldDataType = $this->getFieldDataType();
			if($fieldDataType == 'picklist' || $fieldDataType == 'multipicklist') {
			$webservice = $this->getWebserviceFieldObject();
			$picklistValues = $webservice->getPicklistDetails();
			foreach($picklistValues as $values) {
				$fieldPickListValues[$values['value']] = vtranslate($values['value'],$this->getModuleName());
			}
			return $fieldPickListValues;
			}
		return null;
		}

	/**
	 * Function to check if the current field is mandatory or not
	 * @return <Boolean> - true/false
	 */
	public function isMandatory() {
		list($type,$mandatory)= explode('~',$this->get('typeofdata'));
		return $mandatory=='M' ? true:false;
	}

	/**
	 * Function to get the field type
	 * @return <String> type of the field
	 */
	public function getFieldType(){
		$webserviceField = $this->getWebserviceFieldObject();
		return $webserviceField->getFieldType();
	}

	/**
	 * Function to check if the field is shown in detail view
	 * @return <Boolean> - true/false
	 */
	public function isViewEnabled() {
		$db = PearDatabase::getInstance();
		$cache = Vtiger_Cache::getInstance();
		if($cache->getFieldPermision($this->getModule()->getId(),$this->getId())){
		    return $cache->getFieldPermision($this->getModule()->getId(),$this->getId());
		} else {
			$current_user =  Users_Record_Model::getCurrentUserModel();
			$privileges= $current_user->getPrivileges(); 
		    $permision = $privileges->hasFieldReadAccess($this->getModule()->getName(),$this->getName());
		    
		    if($this->getDisplayType() == '4' || in_array($this->get('presence'), array(1,3))) {
			$cache->setFieldPermision($this->getModule()->getId(),$this->getId(),false);
			return false;
		    }
		    $cache->setFieldPermision($this->getModule()->getId(),$this->getId(),$permision);
		    return $permision;
		}
	}


	/**
	 * Function to check if the field is shown in detail view
	 * @return <Boolean> - true/false
	 */
	public function isViewable() {
		if(!$this->isViewEnabled()) {
			return false;
		}
		return true;
	}

	/**
	 * Function to check if the field is shown in detail view
	 * @return <Boolean> - true/false
	 */
	public function isViewableInDetailView() {
		if(!$this->isViewable() || $this->getDisplayType() == '3' || $this->getDisplayType() == '5') {
			return false;
		}
		return true;
	}

	public function isEditEnabled() {
		$displayType = (int)$this->get('displaytype');
		$editEnabledDisplayTypes = array(1,3);
		if(!$this->isViewEnabled() ||
				!in_array($displayType, $editEnabledDisplayTypes) ||
				strcasecmp($this->getFieldDataType(),"autogenerated") ===0 ||
				strcasecmp($this->getFieldDataType(),"id") ===0) {
			
			return false;
		}
		return true;
	}

    public function isQuickCreateEnabled() {
        $quickCreate = $this->get('quickcreate');
        if($quickCreate == self::QUICKCREATE_MANDATORY || $quickCreate == self::QUICKCREATE_ENABLED
                || $this->isMandatory()) {
            return true;
        }
        return false;
    }

	/**
	 * Function to check whether the current field is editable
	 * @return <Boolean> - true/false
	 */
	public function isEditable() {
		if(!$this->isEditEnabled()
				|| !$this->isViewable() ||
				((int)$this->get('displaytype')) != 1 ||
				$this->isReadOnly() == true ||
				$this->get('uitype') ==  4) {
			
			return false;
		}
		return true;
	}

	/**
	 * Function to check whether field is ajax editable'
	 * @return <Boolean>
	 */
	public function isAjaxEditable() {
		$ajaxRestrictedFields = array('4', '72');
		if(!$this->isEditable() || in_array($this->get('uitype'), $ajaxRestrictedFields)) {
			return false;
		}
		return true;
	}

	/**
	 * Static Function to get the instance fo Vtiger Field Model from a given Vtiger_Field object
	 * @param Vtiger_Field $fieldObj - vtlib field object
	 * @return Vtiger_Field_Model instance
	 */
	public static function getInstanceFromFieldObject(Vtiger_Field $fieldObj) {
		$objectProperties = get_object_vars($fieldObj);
		$className = Vtiger_Loader::getComponentClassName('Model', 'Field', $fieldObj->getModuleName());
		$fieldModel = new $className();
		foreach($objectProperties as $properName=>$propertyValue) {
			$fieldModel->$properName = $propertyValue;
		}
		return $fieldModel;
	}

	/**
	 * Function to get the custom view column name transformation of the field for a date field used in date filters
	 * @return <String> - tablename:columnname:fieldname:module_fieldlabel
	 */
	public function getCVDateFilterColumnName() {
		$moduleName = $this->getModuleName();
		$tableName = $this->get('table');
		$columnName = $this->get('column');
		$fieldName = $this->get('name');
		$fieldLabel = $this->get('label');

		$escapedFieldLabel = str_replace(' ', '_', $fieldLabel);
		$moduleFieldLabel = $moduleName.'_'.$escapedFieldLabel;

		return $tableName.':'.$columnName.':'.$fieldName.':'.$moduleFieldLabel;
	}

	/**
	 * Function to get the custom view column name transformation of the field
	 * @return <String> - tablename:columnname:fieldname:module_fieldlabel:fieldtype
	 */
	public function getCustomViewColumnName() {
		$moduleName = $this->getModuleName();
		$tableName = $this->get('table');
		$columnName = $this->get('column');
		$fieldName = $this->get('name');
		$fieldLabel = $this->get('label');
		$typeOfData = $this->get('typeofdata');

		$fieldTypeOfData = explode('~', $typeOfData);
		$fieldType = $fieldTypeOfData[0];

		//Special condition need for reference field as they should be treated as string field
		if($this->getFieldDataType() == 'reference') {
			$fieldType = 'V';
		} else {
			$fieldType = ChangeTypeOfData_Filter($tableName, $columnName, $fieldType);
		}

		$escapedFieldLabel = str_replace(' ', '_', $fieldLabel);
		$moduleFieldLabel = $moduleName.'_'.$escapedFieldLabel;

		return $tableName.':'.$columnName.':'.$fieldName.':'.$moduleFieldLabel.':'.$fieldType;
	}

	/**
	 * Function to get the Report column name transformation of the field
	 * @return <String> - tablename:columnname:module_fieldlabel:fieldname:fieldtype
	 */
	public function getReportFilterColumnName() {
		$moduleName = $this->getModuleName();
		$tableName = $this->get('table');
		$columnName = $this->get('column');
		$fieldName = $this->get('name');
		$fieldLabel = $this->get('label');
		$typeOfData = $this->get('typeofdata');

		$fieldTypeOfData = explode('~', $typeOfData);
		$fieldType = $fieldTypeOfData[0];

		$escapedFieldLabel = str_replace(' ', '_', $fieldLabel);
		$moduleFieldLabel = $moduleName.'_'.$escapedFieldLabel;
		
		if($tableName == 'vtiger_crmentity' && $columnName !='smownerid'){
			$tableName = 'vtiger_crmentity'.$moduleName;
		} elseif($columnName == 'smownerid') {
			$tableName = 'vtiger_users'.$moduleName;
			$columnName ='user_name';
		}

		return $tableName.':'.$columnName.':'.$moduleFieldLabel.':'.$fieldName.':'.$fieldType;
	}
	/**
	 * Function to get the field details
	 * @return <Array> - array of field values
	 */
	public function getFieldInfo() {
		$currentUser = Users_Record_Model::getCurrentUserModel();
		$fieldInfo =  array(
			'mandatory' => $this->isMandatory(),
			'type' => $this->getFieldDataType(),
			'name' => $this->get('name'),
			'label' => vtranslate($this->get('label'), $this->getModuleName())
		);

		$pickListValues = $this->getPicklistValues();
		if(!empty($pickListValues)) {
			$fieldInfo['picklistvalues'] = $pickListValues;
		}

		if($this->getFieldDataType() == 'date' || $this->getFieldDataType() == 'datetime'){
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$fieldInfo['date-format'] = $currentUser->get('date_format');
		}

		if($this->getFieldDataType() == 'currency') {
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$fieldInfo['currency_symbol'] = $currentUser->get('currency_symbol');
		}

		if($this->getFieldDataType() == 'owner') {
			$userList = $currentUser->getAccessibleUsers();
			$groupList = $currentUser->getAccessibleGroups();
			$pickListValues = array();
			$pickListValues[vtranslate('LBL_USERS', $this->getModuleName())] = $userList;
			$pickListValues[vtranslate('LBL_GROUPS', $this->getModuleName())] = $groupList;
			$fieldInfo['picklistvalues'] = $pickListValues;
		}

		return $fieldInfo;
	}

	/**
	 * Function to get the date values for the given type of Standard filter
	 * @param <String> $type
	 * @return <Array> - 2 date values representing the range for the given type of Standard filter
	 */
	protected static function getDateForStdFilterBytype($type) {
		$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d"), date("Y")));
		$tomorrow = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 1, date("Y")));
		$yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));

		$currentmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m"), "01", date("Y")));
		$currentmonth1 = date("Y-m-t");
		$lastmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") - 1, "01", date("Y")));
		$lastmonth1 = date("Y-m-t", strtotime("-1 Month"));
		$nextmonth0 = date("Y-m-d", mktime(0, 0, 0, date("m") + 1, "01", date("Y")));
		$nextmonth1 = date("Y-m-t", strtotime("+1 Month"));

		$lastweek0 = date("Y-m-d", strtotime("-2 week Sunday"));
		$lastweek1 = date("Y-m-d", strtotime("-1 week Saturday"));

		$thisweek0 = date("Y-m-d", strtotime("-1 week Sunday"));
		$thisweek1 = date("Y-m-d", strtotime("this Saturday"));

		$nextweek0 = date("Y-m-d", strtotime("this Sunday"));
		$nextweek1 = date("Y-m-d", strtotime("+1 week Saturday"));

		$next7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 6, date("Y")));
		$next30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 29, date("Y")));
		$next60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 59, date("Y")));
		$next90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 89, date("Y")));
		$next120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") + 119, date("Y")));

		$last7days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 6, date("Y")));
		$last30days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 29, date("Y")));
		$last60days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 59, date("Y")));
		$last90days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 89, date("Y")));
		$last120days = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d") - 119, date("Y")));

		$currentFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
		$currentFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y")));
		$lastFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") - 1));
		$lastFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") - 1));
		$nextFY0 = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
		$nextFY1 = date("Y-m-t", mktime(0, 0, 0, "12", date("d"), date("Y") + 1));

		if (date("m") <= 3) {
			$cFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
			$nFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
			$pFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y") - 1));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y") - 1));
		} else if (date("m") > 3 and date("m") <= 6) {
			$cFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
			$nFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
			$pFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y")));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y")));
		} else if (date("m") > 6 and date("m") <= 9) {
			$cFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
			$nFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
			$pFq = date("Y-m-d", mktime(0, 0, 0, "04", "01", date("Y")));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "06", "30", date("Y")));
		} else {
			$cFq = date("Y-m-d", mktime(0, 0, 0, "10", "01", date("Y")));
			$cFq1 = date("Y-m-d", mktime(0, 0, 0, "12", "31", date("Y")));
			$nFq = date("Y-m-d", mktime(0, 0, 0, "01", "01", date("Y") + 1));
			$nFq1 = date("Y-m-d", mktime(0, 0, 0, "03", "31", date("Y") + 1));
			$pFq = date("Y-m-d", mktime(0, 0, 0, "07", "01", date("Y")));
			$pFq1 = date("Y-m-d", mktime(0, 0, 0, "09", "30", date("Y")));
		}

		$dateValues = array();
		if ($type == "today") {
			$dateValues[0] = $today;
			$dateValues[1] = $today;
		} elseif ($type == "yesterday") {
			$dateValues[0] = $yesterday;
			$dateValues[1] = $yesterday;
		} elseif ($type == "tomorrow") {
			$dateValues[0] = $tomorrow;
			$dateValues[1] = $tomorrow;
		} elseif ($type == "thisweek") {
			$dateValues[0] = $thisweek0;
			$dateValues[1] = $thisweek1;
		} elseif ($type == "lastweek") {
			$dateValues[0] = $lastweek0;
			$dateValues[1] = $lastweek1;
		} elseif ($type == "nextweek") {
			$dateValues[0] = $nextweek0;
			$dateValues[1] = $nextweek1;
		} elseif ($type == "thismonth") {
			$dateValues[0] = $currentmonth0;
			$dateValues[1] = $currentmonth1;
		} elseif ($type == "lastmonth") {
			$dateValues[0] = $lastmonth0;
			$dateValues[1] = $lastmonth1;
		} elseif ($type == "nextmonth") {
			$dateValues[0] = $nextmonth0;
			$dateValues[1] = $nextmonth1;
		} elseif ($type == "next7days") {
			$dateValues[0] = $today;
			$dateValues[1] = $next7days;
		} elseif ($type == "next30days") {
			$dateValues[0] = $today;
			$dateValues[1] = $next30days;
		} elseif ($type == "next60days") {
			$dateValues[0] = $today;
			$dateValues[1] = $next60days;
		} elseif ($type == "next90days") {
			$dateValues[0] = $today;
			$dateValues[1] = $next90days;
		} elseif ($type == "next120days") {
			$dateValues[0] = $today;
			$dateValues[1] = $next120days;
		} elseif ($type == "last7days") {
			$dateValues[0] = $last7days;
			$dateValues[1] = $today;
		} elseif ($type == "last30days") {
			$dateValues[0] = $last30days;
			$dateValues[1] = $today;
		} elseif ($type == "last60days") {
			$dateValues[0] = $last60days;
			$dateValues[1] = $today;
		} else if ($type == "last90days") {
			$dateValues[0] = $last90days;
			$dateValues[1] = $today;
		} elseif ($type == "last120days") {
			$dateValues[0] = $last120days;
			$dateValues[1] = $today;
		} elseif ($type == "thisfy") {
			$dateValues[0] = $currentFY0;
			$dateValues[1] = $currentFY1;
		} elseif ($type == "prevfy") {
			$dateValues[0] = $lastFY0;
			$dateValues[1] = $lastFY1;
		} elseif ($type == "nextfy") {
			$dateValues[0] = $nextFY0;
			$dateValues[1] = $nextFY1;
		} elseif ($type == "nextfq") {
			$dateValues[0] = $nFq;
			$dateValues[1] = $nFq1;
		} elseif ($type == "prevfq") {
			$dateValues[0] = $pFq;
			$dateValues[1] = $pFq1;
		} elseif ($type == "thisfq") {
			$dateValues[0] = $cFq;
			$dateValues[1] = $cFq1;
		} else {
			$dateValues[0] = "";
			$dateValues[1] = "";
		}

		return $dateValues;
	}

	/**
	 * Function to get all the date filter type informations
	 * @return <Array>
	 */
	public static function getDateFilterTypes() {
		$dateFilters = Array('custom' => array('label' => 'LBL_CUSTOM'),
								'prevfy' => array('label' => 'LBL_PREVIOUS_FY'),
								'thisfy' => array('label' => 'LBL_CURRENT_FY'),
								'nextfy' => array('label' => 'LBL_NEXT_FY'),
								'prevfq' => array('label' => 'LBL_PREVIOUS_FQ'),
								'thisfq' => array('label' => 'LBL_CURRENT_FQ'),
								'nextfq' => array('label' => 'LBL_NEXT_FQ'),
								'yesterday' => array('label' => 'LBL_YESTERDAY'),
								'today' => array('label' => 'LBL_TODAY'),
								'tomorrow' => array('label' => 'LBL_TOMORROW'),
								'lastweek' => array('label' => 'LBL_LAST_WEEK'),
								'thisweek' => array('label' => 'LBL_CURRENT_WEEK'),
								'nextweek' => array('label' => 'LBL_NEXT_WEEK'),
								'lastmonth' => array('label' => 'LBL_LAST_MONTH'),
								'thismonth' => array('label' => 'LBL_CURRENT_MONTH'),
								'nextmonth' => array('label' => 'LBL_NEXT_MONTH'),
								'last7days' => array('label' => 'LBL_LAST_7_DAYS'),
								'last30days' => array('label' => 'LBL_LAST_30_DAYS'),
								'last60days' => array('label' => 'LBL_LAST_60_DAYS'),
								'last90days' => array('label' => 'LBL_LAST_90_DAYS'),
								'last120days' => array('label' => 'LBL_LAST_120_DAYS'),
								'next30days' => array('label' => 'LBL_NEXT_30_DAYS'),
								'next60days' => array('label' => 'LBL_NEXT_60_DAYS'),
								'next90days' => array('label' => 'LBL_NEXT_90_DAYS'),
								'next120days' => array('label' => 'LBL_NEXT_120_DAYS')
							);

		foreach($dateFilters as $filterType => $filterDetails) {
			$dateValues = self::getDateForStdFilterBytype($filterType);
			$dateFilters[$filterType]['startdate'] = $dateValues[0];
			$dateFilters[$filterType]['enddate'] = $dateValues[1];
		}
		return $dateFilters;
	}

	/**
	 * Function to get all the supported advanced filter operations
	 * @return <Array>
	 */
	public static function getAdvancedFilterOptions() {
		return array(
			'e' => 'LBL_EQUALS',
			'n' => 'LBL_NOT_EQUAL_TO',
			's' => 'LBL_STARTS_WITH',
			'ew' => 'LBL_ENDS_WITH',
			'c' => 'LBL_CONTAINS',
			'k' => 'LBL_DOES_NOT_CONTAIN',
			'l' => 'LBL_LESS_THAN',
			'g' => 'LBL_GREATER_THAN',
			'm' => 'LBL_LESS_THAN_OR_EQUAL',
			'h' => 'LBL_GREATER_OR_EQUAL',
			'b' => 'LBL_BEFORE',
			'a' => 'LBL_AFTER',
			'bw' => 'LBL_BETWEEN',
		);
	}


	/**
	 * Function to get the advanced filter option names by Field type
	 * @return <Array>
	 */
	public static function getAdvancedFilterOpsByFieldType() {
		return array(
			'V' => array('e','n','s','ew','c','k'),
			'N' => array('e','n','l','g','m','h'),
			'T' => array('e','n','l','g','m','h','bw','b','a'),
			'I' => array('e','n','l','g','m','h'),
			'C' => array('e','n'),
			'D' => array('e','n','bw','b','a'),
			'DT' => array('e','n','bw','b','a'),
			'NN' => array('e','n','l','g','m','h'),
			'E' => array('e','n','s','ew','c','k')
		);
	}

	/**
	 * Function to retrieve field model for specific block and module
	 * @param <Vtiger_Block_Model> $blockModel - block instance
	 * @param <type> $moduleModel - optional parameter - module instance
	 * @return <array> List of field model
	 */
	public static function getAllForBlock($blockModel, $moduleModel=false){
		$fieldObjects = parent::getAllForBlock($blockModel, $moduleModel);
		$fieldModelList = array();

		//if block dont have any fields
		if(!is_array($fieldObjects)){
			$fieldObjects = array();
		}

		foreach($fieldObjects as $fieldObject){
			$fieldModelList[] = self::getInstanceFromFieldObject($fieldObject);
		}
		return $fieldModelList;
	}

	/**
	 * Function to get instance
	 * @param <String> $value - fieldname or fieldid
	 * @param <type> $module - optional - module instance
	 * @return <Vtiger_Field_Model>
	 */
	public static function  getInstance($value, $module = false) {
		$fieldObject = parent::getInstance($value, $module);
		if($fieldObject) {
			return self::getInstanceFromFieldObject($fieldObject);
		}
		return false;
	}

	/**
	 * Added function that returns the folders in a Document
	 * @return <Array>
	 */
	function getDocumentFolders() {
		$db = PearDatabase::getInstance();
		$result = $db->pquery('SELECT * FROM vtiger_attachmentsfolder', array());
		$rows = $db->num_rows($result);
		$folders = array();
		for($i=0; $i<$rows; $i++){
			$folderId = $db->query_result($result, $i, 'folderid');
			$folderName = $db->query_result($result, $i, 'foldername');
			$folders[$folderId] = $folderName;
		}
		return $folders;
	}

	/**
	 * Function checks if the current Field is Read/Write
	 * @return <Boolean>
	 */
	function getProfileReadWritePermission() {
		$currentUserPrivilegeModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		return $currentUserPrivilegeModel->hasFieldWriteAccess($this->getModuleName(), $this->getName());
	}

	/**
	 * Function returns Client Side Validators name
	 * @return <Array> [name=>Name of the Validator, params=>Extra Parameters]
	*/
	/**TODO: field validator need to be handled in specific module getValidator api  **/
	function getValidator() {
		$validator = array();
		$fieldName = $this->getName();
		switch($fieldName) {
			case 'birthday' : $funcName = array('name'=>'lessThanToday');
							  array_push($validator, $funcName);
								break;
			case 'support_end_date' : $funcName = array('name' => 'greaterThanDependentField',
														'params' => array('support_start_date'));
									array_push($validator, $funcName);
									break;
            case 'support_start_date' : $funcName = array('name' => 'lessThanDependentField',
														'params' => array('support_end_date'));
									array_push($validator, $funcName);
									break;
			case 'targetenddate' :
			case 'actualenddate':
			case 'enddate':
							$funcName = array('name' => 'greaterThanDependentField',
								'params' => array('startdate'));
							array_push($validator, $funcName);
							break;
            case 'startdate': 
                            if($this->getModule()->get('name') == 'Project') {
                                $params = array('targetenddate');
                            }else{
                                //for project task
                                $params = array('enddate');
                            }
                            $funcName = array('name' => 'lessThanDependentField',
								'params' => $params);
							array_push($validator, $funcName);
							break;
			case 'expiry_date':
			case 'due_date':
								$funcName = array('name' => 'greaterThanDependentField',
									'params' => array('start_date'));
								array_push($validator, $funcName);
								break;
			case 'sales_end_date':
								$funcName = array('name' => 'greaterThanDependentField',
									'params' => array('sales_start_date'));
								array_push($validator, $funcName);
								break;
            case 'sales_start_date':
								$funcName = array('name' => 'lessThanDependentField',
									'params' => array('sales_end_date'));
								array_push($validator, $funcName);
								break;
			case 'qty_per_unit' :
			case 'qtyindemand' :
			case 'hours':
			case 'days':
								$funcName = array('name'=>'PositiveNumber');
							  array_push($validator, $funcName);
								break;
			case 'employees':
								$funcName = array('name'=>'WholeNumber');
							  array_push($validator, $funcName);
								break;
			case 'related_to':
								$funcName = array('name'=>'ReferenceField');
							  array_push($validator, $funcName);
								break;
            //SalesOrder field sepecial validators
            case 'end_period' : $funcName = array('name' => 'greaterThanDependentField',
									'params' => array('start_period'));
								array_push($validator, $funcName);
								break;
             case 'start_period' : $funcName = array('name' => 'lessThanDependentField',
									'params' => array('end_period'));
								array_push($validator, $funcName);
								break;                            
		}
		return $validator;
	}

	/**
	 * Function to retrieve display value in edit view
	 * @param <String> $value - value which need to be converted to display value
	 * @return <String> - converted display value
	 */
	public function getEditViewDisplayValue($value) {
		if(!$this->uitype_instance) {
			$this->uitype_instance = Vtiger_Base_UIType::getInstanceFromField($this);
		}
		$uiTypeInstance = $this->uitype_instance;
		return $uiTypeInstance->getEditViewDisplayValue($value);
	}

	/**
	 * Function to retieve types of file locations in Documents Edit
	 * @return <array> - List of file location types
	 */
	public function getFileLocationType() {
		return array('I'=>'LBL_INTERNAL', 'E'=>'LBL_EXTERNAL');
	}

	/**
	 * Function returns list of Currencies available in the system
	 * @return <Array>
	 */
	public function getCurrencyList() {
		$db = PearDatabase::getInstance();
		$result = $db->pquery('SELECT * FROM vtiger_currency_info WHERE currency_status = ? AND deleted=0', array('Active'));
		for($i=0; $i<$db->num_rows($result); $i++) {
			$currencyId = $db->query_result($result, $i, 'id');
			$currencyName = $db->query_result($result, $i, 'currency_name');
			$currencies[$currencyId] = $currencyName;
		}
		return $currencies;
	}

	/**
	 * Function to get Display value for RelatedList
	 * @param <String> $value
	 * @return <String>
	 */
	public function getRelatedListDisplayValue($value) {
		if(!$this->uitype_instance) {
			$this->uitype_instance = Vtiger_Base_UIType::getInstanceFromField($this);
		}
		$uiTypeInstance = $this->uitype_instance;
		return $uiTypeInstance->getRelatedListDisplayValue($value);
	}
	
	/**
	 * Function to get Default Field Value
	 * @return <String> defaultvalue
	 */
	public function getDefaultFieldValue(){
		return $this->defaultvalue;
	}
    
    
    /**
     * Function whcih will get the databse insert value format from user format
     * @param type $value in user format
     * @return type
     */
    public function getDBInsertValue($value) {
        if(!$this->uitype_instance) {
			$this->uitype_instance = Vtiger_Base_UIType::getInstanceFromField($this);
		}
		$uiTypeInstance = $this->uitype_instance;
        return $uiTypeInstance->getDBInsertValue($value);
    }
}