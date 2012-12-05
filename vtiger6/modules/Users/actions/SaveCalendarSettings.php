<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Users_SaveCalendarSettings_Action extends Users_Save_Action {


	public function process(Vtiger_Request $request) {
		$recordModel = $this->getRecordModelFromRequest($request);
		
		$recordModel->save();
		if($request->get('shared_ids')){
			$this->updateCalendarSharing($request);
		}
		header("Location: index.php?module=Calendar&view=Calendar");
	}

	/**
	 * Function to update Calendar Sharing information
	 * @params - Vtiger_Request $request
	 */
	public function updateCalendarSharing(Vtiger_Request $request){
		$db = PearDatabase::getInstance();
		$currentUserModel = Users_Record_Model::getCurrentUserModel();
		$sharedIds = $request->get('shared_ids');
		if(!empty($sharedIds)){
			$delquery = "DELETE FROM vtiger_sharedcalendar WHERE userid=?";
			$db->pquery($delquery, array($currentUserModel->id));
			foreach ($sharedIds as $id) {
				$sql = "INSERT INTO vtiger_sharedcalendar VALUES (?,?)";
				$db->pquery($sql, array($currentUserModel->id, $id));
			}
		}
	}
}
