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
 * Vtiger ListView Model Class
 */
class PriceBooks_ListView_Model extends Vtiger_ListView_Model {
	/*
	 * Function to give advance links of a module
	 *	@RETURN array of advanced links
	 */
	public function getAdvancedLinks(){
		return array();
	}
	
	public function getListViewEntries($pagingModel) {
		$entries = parent::getListViewEntries($pagingModel);

		// Pass through the src_record state to dependent model
		if ($this->has('src_record') && !empty($entries)) {
			foreach ($entries as $record) {
					$record->set('src_record', $this->get('src_record'));
			}
		}
		
		return $entries;
		}
}
