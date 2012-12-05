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
 * Vtiger Summary View Record Structure Model
 */
class Vtiger_SummaryRecordStructure_Model extends Vtiger_DetailRecordStructure_Model {

	/**
	 * Function to get the values in stuctured format
	 * @return <array> - values in structure array('block'=>array(fieldinfo));
	 */
	public function getStructure() {
		$structuredValues = parent::getStructure();
		$summaryFieldsList = $this->getModule()->getSummaryViewFieldsList();

		foreach($structuredValues as $blockLabel => $fieldModelsList) {
			if ($summaryFieldsList) {
				foreach ($fieldModelsList as $fieldName => $fieldModel) {
					if (in_array($fieldName, $summaryFieldsList)) {
						$fieldModels[$fieldName] = $fieldModel;
					}
				}
				$summaryFieldModelsList['SUMMARY_FIELDS'] = $fieldModels;
			} else {
				$summaryFieldModelsList[$blockLabel] = $fieldModelsList;
				break;
			}
		}
		return $summaryFieldModelsList;
	}
}