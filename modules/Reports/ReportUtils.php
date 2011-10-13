<?php

/* +*******************************************************************************
 *  The contents of this file are subject to the vtiger CRM Public License Version 1.0
 *  ("License"); You may not use this file except in compliance with the License
 *  The Original Code is:  vtiger CRM Open Source
 *  The Initial Developer of the Original Code is vtiger.
 *  Portions created by vtiger are Copyright (C) vtiger.
 *  All Rights Reserved.
 *
 * ******************************************************************************* */

/**
 * Description of ReportUtils
 *
 * @author Musavir Ahmed Khan<musavir at vtiger.com>
 */

function getFieldByReportLabel($module, $label) {
	
	// this is required so the internal cache is populated or reused.
	getColumnFields($module);
	//lookup all the accessible fields
	$cachedModuleFields = VTCacheUtils::lookupFieldInfo_Module($module);
	if(empty($cachedModuleFields)) {
		return null;
	}
	foreach ($cachedModuleFields as $fieldInfo) {
		$fieldLabel = str_replace(' ', '_', $fieldInfo['fieldlabel']);
		if($label == $fieldLabel) {
			return $fieldInfo;
		}
	}
	return null;
}

/**
 *
 * @global Users $current_user
 * @param ReportRun $report
 * @param Array $picklistArray
 * @param ADOFieldObject $dbField
 * @param Array $valueArray
 * @param String $fieldName
 * @return String 
 */
function getReportFieldValue ($report, $picklistArray, $dbField, $valueArray, $fieldName) {
	global $current_user;
	
	$db = PearDatabase::getInstance();
	$value = $valueArray[$fieldName];
	$fld_type = $dbField->type;
	list($module, $fieldLabel) = explode('_', $dbField->name, 2);
	$fieldInfo = getFieldByReportLabel($module, $fieldLabel);
	$fieldType = null;
	if(!empty($fieldInfo)) {
		$field = WebserviceField::fromArray($db, $fieldInfo);
		$fieldType = $field->getFieldDataType();
	}
	if (in_array($dbField->name, $report->convert_currency) || 
		$fieldType == 'currency') {
		if($value!='')
			$fieldvalue = convertFromMasterCurrency($value,$current_user->conv_rate);
		else
			$fieldvalue = getTranslatedString($value);
	} elseif(in_array($dbField->name, $report->append_currency_symbol_to_value)) {
		$curid_value = explode("::", $value);
		$currency_id = $curid_value[0];
		$currency_value = $curid_value[1];
		$cur_sym_rate = getCurrencySymbolandCRate($currency_id);
		if($value!='')
			$fieldvalue = $cur_sym_rate['symbol']." ".$currency_value;
		else
			$fieldvalue = getTranslatedString($value);
	} elseif ($dbField->name == "PurchaseOrder_Currency" || $dbField->name == "SalesOrder_Currency" 
				|| $dbField->name == "Invoice_Currency" || $dbField->name == "Quotes_Currency") {
		if($value!='')
			$fieldvalue = getCurrencyName($value);
		else
			$fieldvalue =getTranslatedString($value);
	}elseif ((in_array($dbField->name,$report->ui10_fields) || $fieldType == 'reference') && 
			!empty($value)) {
		$type = getSalesEntityType($value);
		$tmp =getEntityName($type,$value);
		if (is_array($tmp)){
			foreach($tmp as $key=>$val){
				$fieldvalue = $val;
				break;
			}
		}else{
			$fieldvalue = $value;
		}
	} elseif (in_array($dbField->name,$report->ui101_fields) && !empty($value)) {
		$entityNames = getEntityName('Users', $value);
		$fieldvalue = $entityNames[$value];
	} elseif( $fieldType == 'date' && !empty($value)) {
		if($module == 'Calendar' && $field->getFieldName() == 'due_date') {
			$endTime = $valueArray['calendar_end_time'];
			if(empty($endTime)) {
				$recordId = $valueArray['calendar_id'];
				$endTime = getSingleFieldValue('vtiger_activity', 'time_end', 'activityid', $recordId);
			}
			$date = new DateTimeField($value.' '.$endTime);
			$fieldvalue = $date->getDisplayDate();
		} else {
			$fieldvalue = DateTimeField::convertToUserFormat($value);
		}
	} elseif( $fieldType == "datetime" && !empty($value)) {
		$date = new DateTimeField($value);
		$fieldvalue = $date->getDisplayDateTimeValue();
	} elseif( $fieldType == 'time' && !empty($value) && $field->getFieldName() 
			!= 'duration_hours') {
		$date = new DateTimeField($value);
		$fieldvalue = $date->getDisplayTime();
	} elseif( $fieldType == "picklist" && !empty($value) ) {
		if(is_array($picklistArray)) {
			if(is_array($picklistArray[$dbField->name]) && 
					$field->getFieldName() != 'activitytype' && !in_array(
					$value, $picklistArray[$dbField->name])){
				$fieldvalue =$app_strings['LBL_NOT_ACCESSIBLE'];
			} else {
				$fieldvalue = getTranslatedString($value, $module);
			}
		} else {
			$fieldvalue = getTranslatedString($value, $module);
		}
	} elseif( $fieldType == "multipicklist" && !empty($value) ) {
		if(is_array($picklistArray[1])) {
			$valueList = explode(' |##| ', $value);
			$translatedValueList = array();
			foreach ( $valueList as $value) {
				if(is_array($picklistArray[1][$dbField->name]) && !in_array(
						$value, $picklistArray[1][$dbField->name])) {
					$translatedValueList[] = 
							$app_strings['LBL_NOT_ACCESSIBLE'];
				} else {
					$translatedValueList[] = getTranslatedString($value, 
							$module);
				}
			}
		}
		if (!is_array($picklistArray[1]) || !is_array($picklistArray[1][$dbField->name])) {
			$fieldvalue = str_replace(' |##| ', ', ', $value);
		} else {
			implode(', ', $translatedValueList);
		}
	} else {
		if($value!='')
			$fieldvalue = getTranslatedString( $value, $module);
		else
			$fieldvalue = getTranslatedString( $value, $module);
	}
	if($fieldvalue == "") {
		return "-";
	}
	$fieldvalue = str_replace("<", "&lt;", $fieldvalue);
	$fieldvalue = str_replace(">", "&gt;", $fieldvalue);
	$fieldvalue = decode_html($fieldvalue);
	
	if (stristr($fieldvalue, "|##|") && empty($fieldType)) {
		$fieldvalue = str_ireplace(' |##| ', ', ', $fieldvalue);
	} elseif ($fld_type == "date" && empty($fieldType)) {
		$fieldvalue = DateTimeField::convertToUserFormat($fieldvalue);
	} elseif ($fld_type == "datetime" && empty($fieldType)) {
		$date = new DateTimeField($fieldvalue);
		$fieldvalue = $date->getDisplayDateTimeValue();
	}
	
	return $fieldvalue;
}

?>
