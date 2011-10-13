<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once 'modules/Vtiger/EditView.php';

if($record) {
	$service_base_currency = getProductBaseCurrency($focus->id,$currentModule);
} else {
	$service_base_currency = fetchCurrency($current_user->id);
}

//Tax handling (get the available taxes only) - starts
if($focus->mode == 'edit') {
	$retrieve_taxes = true;
	$serviceid = $focus->id;
	$tax_details = getTaxDetailsForProduct($serviceid,'available_associated');

} elseif($_REQUEST['isDuplicate'] == 'true') {
	$retrieve_taxes = true;
	$serviceid = $_REQUEST['record'];
	$tax_details = getTaxDetailsForProduct($serviceid,'available_associated');
	
} else {
	$tax_details = getAllTaxes('available');
	$smarty->assign("PROD_MODE", "create");
}

for($i=0;$i<count($tax_details);$i++) {
	$tax_details[$i]['check_name'] = $tax_details[$i]['taxname'].'_check';
	$tax_details[$i]['check_value'] = 0;
}

//For Edit and Duplicate we have to retrieve the service associated taxes and show them
if($retrieve_taxes) {
	for($i=0;$i<count($tax_details);$i++) {
		$tax_value = getProductTaxPercentage($tax_details[$i]['taxname'],$serviceid);
		$tax_details[$i]['percentage'] = $tax_value;
		$tax_details[$i]['check_value'] = 1;
		//if the tax is not associated with the service then we should get the default value and unchecked
		if($tax_value == '') {
			$tax_details[$i]['check_value'] = 0;
			$tax_details[$i]['percentage'] = getTaxPercentage($tax_details[$i]['taxname']);
		}
	}
}

$smarty->assign("TAX_DETAILS", $tax_details);
//Tax handling - ends

$unit_price = $focus->column_fields['unit_price'];
$price_details = getPriceDetailsForProduct($serviceid, $unit_price, 'available',$currentModule);
$smarty->assign("PRICE_DETAILS", $price_details);

$base_currency = 'curname' . $service_base_currency;	
$smarty->assign("BASE_CURRENCY", $base_currency);

if($focus->mode == 'edit') {
	$smarty->display('Inventory/InventoryEditView.tpl');
} else {
	$smarty->display('Inventory/InventoryCreateView.tpl');
}

?>