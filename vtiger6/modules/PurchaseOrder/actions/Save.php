<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
class PurchaseOrder_Save_Action extends Vtiger_Save_Action {
	
	/**
	 * Function to save record
	 * @param <Vtiger_Request> $request - values of the record
	 * @return <RecordModel> - record Model of saved record
	 */
	public function saveRecord($request) {
		$recordModel = parent::saveRecord($request);
		
		if($request->get('postatus') == 'Received Shipment'){
			if($recordModel->get('mode') != 'edit'){
				$recordModel->addStockToProducts($recordModel->getId());
			} else {
				$purchaseOrderStatus = $recordModel->getPurchaseOrderStatus($recordModel->getId());
				if($purchaseOrderStatus != $request->get('postatus')){
					$recordModel->addStockToProducts($recordModel->getId());
				}
			} 
		}
		return $recordModel;
	}
}
