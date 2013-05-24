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
		$element = $this->sanitizeShippingTaxes($element);
		$lineItems = $element['LineItems'];
		if (!empty($lineItems)) {
			$element = parent::create($elementType, $element);
			$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
			$handler->setLineItems('LineItem', $lineItems, $element);
		} else {
			throw new WebServiceException(WebServiceErrorCode::$MANDFIELDSMISSING, "Mandatory Fields Missing..");
		}
		return $element;
	}

	public function update($element) {
		$element = $this->sanitizeInventoryForInsert($element);
		$lineItemList = $element['LineItems'];
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		if (!empty($lineItemList)) {
			$updatedElement = parent::update($element);
			$handler->cleanLineItemList($updatedElement['id']);
			$handler->setLineItems('LineItem', $lineItemList, $updatedElement);
		} else {
			$updatedElement = $this->revise($element);
		}
		return $updatedElement;
	}

	public function revise($element) {
		$element = $this->sanitizeInventoryForInsert($element);
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		$components = vtws_getIdComponents($element['id']);
		$parentId = $components[1];
		if (!empty($element['LineItems'])) {
			$lineItemList = $element['LineItems'];
			unset($element['LineItems']);
		} else {
			$lineItemList = $handler->getAllLineItemForParent($parentId);
		}
		$updatedElement = parent::revise($element);
		$handler->cleanLineItemList($updatedElement['id']);
		$handler->setLineItems('LineItem', $lineItemList, $updatedElement);
		return $updatedElement;
	}

	public function retrieve($id) {
		$element = parent::retrieve($id);
		$skipLineItemFields = getLineItemFields();
		foreach ($skipLineItemFields as $key => $field) {
			if (array_key_exists($field, $element)) {
				unset($element[$field]);
			}
		}
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		$idComponents = vtws_getIdComponents($id);
		$lineItems = $handler->getAllLineItemForParent($idComponents[1]);
		$element['LineItems'] = $lineItems;
		return $element;
	}

	public function delete($id) {
		$components = vtws_getIdComponents($id);
		$parentId = $components[1];
		$handler = vtws_getModuleHandlerFromName('LineItem', $this->user);
		$handler->cleanLineItemList($id);
		$result = parent::delete($id);
		return $result;
	}
	/**
	 * function to display discounts,taxes and adjustments
	 * @param type $element
	 * @return type
	 */
	protected function sanitizeInventoryForInsert($element) {
		$meta = $this->getMeta();
		if (!empty($element['hdnTaxType'])) {
			$_REQUEST['taxtype'] = $element['hdnTaxType'];
		}
		if (!empty($element['hdnSubTotal'])) {
			$_REQUEST['subtotal'] = $element['hdnSubTotal'];
		}
		$_REQUEST['shipping_handling_charge'] = $element['hdnS_H_Amount'];
		if (!empty($element['hdnDiscountAmount'])) {
			$_REQUEST['discount_type_final'] = 'amount';
			$_REQUEST['discount_amount_final'] = $element['hdnDiscountAmount'];
		} elseif (!empty($element['hdnDiscountPercent'])) {
			$_REQUEST['discount_type_final'] = 'percentage';
			$_REQUEST['discount_percentage_final'] = $element['hdnDiscountPercent'];
		}
		

		if (!empty($element['txtAdjustment'])) {
			$_REQUEST['adjustmentType'] = ((int) $element['txtAdjustment'] < 0) ? '-' : '+';
			$_REQUEST['adjustment'] = abs($element['txtAdjustment']);
		}
		if (!empty($element['hdnGrandTotal'])) {
			$_REQUEST['total'] = $element['hdnGrandTotal'];
		}



		return $element;
	}
	
	public function sanitizeShippingTaxes($element){
			$_REQUEST['shipping_handling_charge'] = $element['hdnS_H_Amount'];
			$taxDetails = getAllTaxes('all', 'sh');
			foreach ($taxDetails as $taxInfo) {
				if ($taxInfo['deleted'] == '0' || $taxInfo['deleted'] === 0) {
						$_REQUEST[$taxInfo['taxname'] . '_sh_percent'] = $taxInfo['percentage'];
				}
			}
			return $element;
		
	}
	
}



?>