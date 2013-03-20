<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class Invoice_MassSave_Action extends Inventory_MassSave_Action {

	public function process(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$recordModels = $this->getRecordModelsFromRequest($request);
		foreach($recordModels as $recordId => $recordModel) {
			if(Users_Privileges_Model::isPermitted($moduleName, 'Save', $recordId)) {
				//Inventory line items getting wiped out
				$_REQUEST['action'] = 'MassEditSave';
				$recordModel->save();
			}
		}

		$response = new Vtiger_Response();
		$response->setResult(true);
		$response->emit();
	}

	/**
	 * Function to get the record model based on the request parameters
	 * @param Vtiger_Request $request
	 * @return Vtiger_Record_Model or Module specific Record Model instance
	 */
	public function getRecordModelsFromRequest(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);

		$recordModels = parent::getRecordModelsFromRequest($request);
		$fieldModelList = $moduleModel->getFields();

		foreach($recordModels as $id => $recordModel) {
			foreach ($fieldModelList as $fieldName => $fieldModel) {
				$fieldDataType = $fieldModel->getFieldDataType();

				// This is added as we are marking massedit in vtiger6 as not an ajax operation
				// and this will force the date fields to be saved in user format. If the user format
				// is other than y-m-d then it fails.
				if($fieldDataType == 'date') {
					$uiTypeModel = $fieldModel->getUITypeModel();
					$recordModel->set($fieldName, $uiTypeModel->getDBInsertValue($recordModel->get($fieldName)));
				}
			}
		}
		return $recordModels;
	}
}