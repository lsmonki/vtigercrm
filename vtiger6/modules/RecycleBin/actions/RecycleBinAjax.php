<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

class RecycleBin_RecycleBinAjax_Action extends Vtiger_Mass_Action {
	
	function __construct() {
		parent::__construct();
		$this->exposeMethod('restoreRecords');
		$this->exposeMethod('emptyRecycleBin');
		$this->exposeMethod('deleteRecords');
	}
	
	function checkPermission(Vtiger_Request $request) {
		$moduleName = $request->getModule();
		$moduleModel = Vtiger_Module_Model::getInstance($moduleName);

		$currentUserPriviligesModel = Users_Privileges_Model::getCurrentUserPrivilegesModel();
		if(!$currentUserPriviligesModel->hasModuleActionPermission($moduleModel->getId(), 'Delete')) {
			throw new AppException('LBL_PERMISSION_DENIED');
		}
	}

	function preProcess(Vtiger_Request $request) {
		return true;
	}

	function postProcess(Vtiger_Request $request) {
		return true;
	}

	public function process(Vtiger_Request $request) {
		$mode = $request->get('mode');
		
		if(!empty($mode)) {
			$this->invokeExposedMethod($mode, $request);
			return;
		}
	}
	
	/**
	 * Function to restore the deleted records.
	 * @param type $sourceModule
	 * @param type $recordIds
	 */
	public function restoreRecords(Vtiger_Request $request){
		$sourceModule = $request->get('sourceModule');
		$recordIds = $this->getRecordsListFromRequest($request);

		$moduleModel = RecycleBin_Module_Model::getInstance($request->getModule());
 
		$response = new Vtiger_Response();	
		if ($recordIds) {
			$moduleModel->restore($sourceModule, $recordIds);
			$response->setResult(array(true));
		} 
		
		$response->emit();

	}
	
	/**
	 * Function to delete the reccords perminently in vitger CRM database
	 */
	public function emptyRecycleBin(Vtiger_Request $request){
		$moduleModel = RecycleBin_Module_Model::getInstance($request->getModule());
		
		$status = $moduleModel->emptyRecycleBin();
		
		if($status){
			$response = new Vtiger_Response();
			$response->setResult(array($status));
			$response->emit();
		}
	}
	
	/**
	 * Function to deleted the records perminently in CRM
	 * @param type $reocrdIds
	 */
	public function deleteRecords(Vtiger_Request $request){
		$recordIds = $this->getRecordsListFromRequest($request);

		$moduleModel = RecycleBin_Module_Model::getInstance($request->getModule());
 
		$response = new Vtiger_Response();	
		if ($recordIds) {
			$moduleModel->deleteRecords($recordIds);
			$response->setResult(array(true));
			$response->emit();
		} 
	}
	
}
