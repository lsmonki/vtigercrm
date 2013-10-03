<?php
/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/
require_once 'modules/Emails/mail.php';
class Inventory_Save_Action extends Vtiger_Save_Action {
    
    protected function getRecordModelFromRequest(Vtiger_Request $request) {
		$recordModel = parent::getRecordModelFromRequest($request);
		// Added to set the pre tax total value to user format, so that save(CRMEntity) treats
		// this as normal 72 uitype field. All the currency field that appear in Inventory module
		// in final details are not shown in users format. Once these fields are shown in the users
		// format then we need to remove this. To reproduce the issue have decimal separator as ','
		// and group separator as '.', the values saved in the db are incorrect
		$preTaxTotal = $request->get('pre_tax_total');
		if(!empty($preTaxTotal)) {
			$currentUser = Users_Record_Model::getCurrentUserModel();
			$preTaxTotal = CurrencyField::convertToUserFormat($preTaxTotal, $currentUser);
			$recordModel->set('pre_tax_total', $preTaxTotal);
		}
		return $recordModel;
	}
    
}
