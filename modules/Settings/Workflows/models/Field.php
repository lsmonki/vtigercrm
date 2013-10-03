<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Settings_Workflows_Field_Model extends Vtiger_Field_Model {


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
			'is' => 'is',
			'contains' => 'contains',
			'does not contain' => 'does not contain',
			'starts with' => 'starts with',
			'ends with' => 'ends with',
			'has changed' => 'has changed',
			'is empty' => 'is empty',
			'is not empty' => 'is not empty',
			'less than' => 'less than',
			'greater than' => 'greater than',
			'does not equal' => 'does not equal',
			'less than or equal to' => 'less than or equal to',
			'greater than or equal to' => 'greater than or equal to',
			'has changed' => 'has changed',
			'before' => 'before',
			'after' => 'after',
			'between' => 'between',
		);
	}

	/**
	 * Function to get the advanced filter option names by Field type
	 * @return <Array>
	 */
	public static function getAdvancedFilterOpsByFieldType() {
		return array(
			'string' => array('is', 'contains', 'does not contain', 'starts with', 'ends with', 'has changed', 'is empty', 'is not empty'),
			'salutation' => array('is', 'contains', 'does not contain', 'starts with', 'ends with', 'has changed', 'is empty', 'is not empty'),
			'text' => array('is', 'contains', 'does not contain', 'starts with', 'ends with', 'has changed', 'is empty', 'is not empty'),
			'url' => array('is', 'contains', 'does not contain', 'starts with', 'ends with', 'has changed', 'is empty', 'is not empty'),
			'email' => array('is', 'contains', 'does not contain', 'starts with', 'ends with', 'has changed', 'is empty', 'is not empty'),
			'phone' => array('is', 'contains', 'does not contain', 'starts with', 'ends with', 'has changed', 'is empty', 'is not empty'),
			'integer' => array('equal to', 'less than', 'greater than', 'does not equal', 'less than or equal to', 'greater than or equal to', 'has changed'),
			'double' => array('equal to', 'less than', 'greater than', 'does not equal', 'less than or equal to', 'greater than or equal to', 'has changed'),
			'currency' => array('equal to', 'less than', 'greater than', 'does not equal', 'less than or equal to', 'greater than or equal to', 'has changed'),
			'picklist' => array('is', 'is not', 'has changed'),
			'multipicklist' => array('is', 'is not', 'has changed'),
			'datetime' => array('is', 'is not', 'has changed','less than hours before', 'less than hours later', 'more than hours before', 'more than hours later'),
			'time' => array('is', 'is not', 'has changed'),
			'date' => array('is', 'is not', 'has changed', 'between', 'before', 'after', 'is today', 'less than days ago', 'more than days ago', 'in less than', 'in more than',
							'days ago', 'days later'),
			'boolean' => array('is', 'is not', 'has changed'),
			'reference' => array('has changed'),
			'owner' => array('has changed'),
			'recurrence' => array('is', 'is not', 'has changed')
		);
	}
}
