<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Vtiger_Currency_UIType extends Vtiger_Base_UIType {

	/**
	 * Function to get the Template name for the current UI Type object
	 * @return <String> - Template Name
	 */
	public function getTemplateName() {
		return 'uitypes/Currency.tpl';
	}

	/**
	 * Function to get the Display Value, for the current field type with given DB Insert Value
	 * @param <Object> $value
	 * @return <Object>
	 */
	public function getDisplayValue($value) {
		$uiType = $this->get('field')->get('uitype');
		if ($value) {
			if ($uiType == 72) {
				// Some of the currency fields like Unit Price, Totoal , Sub-total - doesn't need currency conversion during save
				$value = CurrencyField::convertToUserFormat($value, null, true);
			} else {
				$value = CurrencyField::convertToUserFormat($value);
			}
		}
		return currencyDecimalFormat($value);
	}

	/**
	 * Function to get the Value of the field in the format, the user provides it on Save
	 * @param <Object> $value
	 * @return <Object>
	 */
	public function getUserRequestValue($value) {
		return $this->getDisplayValue($value);
	}

	/**
	 * Function to transform display value for currency field
	 * @param $value
	 * @return converted user format value
	 */
	public static function transformDisplayValue($value) {
		return CurrencyField::convertToUserFormat($value);
	}

	/**
	 * Function converts User currency format to database format
	 * @param <Object> $value - Currency value
	 * @param <User Object> $user
	 * @param <Boolean> $skipConversion
	 */
	public static function convertToDBFormat($value, $user=null, $skipConversion=false) {
		return CurrencyField::convertToDBFormat($value, $user, $skipConversion);
	}

	/**
	 * Function to get the display value in edit view
	 * @param <String> $value
	 * @return <String>
	 */
	public function getEditViewDisplayValue($value) {
		return $this->getDisplayValue($value);
	}
}