<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class ModComments_Module_Model extends Vtiger_Module_Model{

	/**
	 * Function to get the create url with parent id set
	 * @param <type> $parentRecord	- parent record for which comment need to be added
	 * @return <string> Url
	 */
	public function  getCreateRecordUrlWithParent($parentRecord) {
		$createRecordUrl = $this->getCreateRecordUrl();
		$createRecordUrlWithParent = $createRecordUrl.'&parent_id='.$parentRecord->getId();
		return $createRecordUrlWithParent;
	}
}
?>
