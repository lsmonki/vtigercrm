<?php
/*+*******************************************************************************
 *  The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 *********************************************************************************/
require_once 'include/Webservices/VtigerModuleOperation.php';
require_once 'include/Webservices/Utils.php';

/**
 * Description of VtigerInventoryOperation
 */
class VtigerInventoryOperation extends VtigerModuleOperation {

	public function create($elementType, $element) {
		$element = $this->sanitizeInventoryForInsert($element);
        $lineItems = $element['LineItems'];
        $element = parent::create($elementType, $element);
        if(!empty ($lineItems)){
            $handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
            $handler->setLineItems('LineItem', $lineItems, $element);
        }else{
            throw new WebServiceException(WebServiceErrorCode::$MANDFIELDSMISSING,"Mandatory Fields Missing..");
        }
        return $element;
	}

	public function update($element) {
		$element = $this->sanitizeInventoryForInsert($element);
		$components = vtws_getIdComponents($element['id']);
		$parentId = $components[1];
        $handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
        $lineItemList = $handler->getAllLineItemForParent($parentId);
        $handler->cleanLineItemList($element['id']);
        $updatedElement = parent::update($element);
        $r = $handler->setLineItems('LineItem', $lineItemList, $element);
		return $updatedElement;
	}

	public function delete($id) {
		$components = vtws_getIdComponents($id);
		$parentId = $components[1];
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		$handler->cleanLineItemList($id);
		$result = parent::delete($id);
		return $result;
	}
	protected function sanitizeInventoryForInsert($element){
		$meta = $this->getMeta();
		if(!empty($element['hdnTaxType'])){
			$_REQUEST['taxtype'] = $element['hdnTaxType'];
		}
		if(!empty($element['hdnSubTotal'])){
			$_REQUEST['subtotal'] = $element['hdnSubTotal'];
		}

		if(!empty($element['hdnDiscountAmount'])){
			$_REQUEST['discount_type_final'] = 'amount';
			$_REQUEST['discount_amount_final'] = $element['hdnDiscountAmount'];
		}elseif(!empty($element['hdnDiscountPercent'])){
			$_REQUEST['discount_type_final'] = 'percentage';
			$_REQUEST['discount_percentage_final'] = $element['hdnDiscountPercent'];
		}

		if(!empty($element['hdnS_H_Amount'])){
			$_REQUEST['shipping_handling_charge'] = $element['hdnS_H_Amount'];
		}

		if(!empty($element['txtAdjustment'])){
			$_REQUEST['adjustmentType'] = ((int)$element['txtAdjustment'] < 0)? '-':'+';
			$_REQUEST['adjustment'] = abs($element['txtAdjustment']);
		}
		if(!empty($element['hdnGrandTotal'])){
			$_REQUEST['total'] = $element['hdnGrandTotal'];
		}

		$taxDetails = getAllTaxes('all','sh');
		foreach ($taxDetails as $taxInfo) {
			if($taxInfo['deleted'] == '0' || $taxInfo['deleted'] === 0){
				$_REQUEST[$taxInfo['taxname'].'_sh_percent'] = $taxInfo['percentage'];
			}
		}

		return $element;
	}

}
?>
