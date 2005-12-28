<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/

/**
 * This function returns the Product detail block values in array format.
 * Input Parameter are $module - module name, $focus - module object, $num_of_products - no.of products associated with it  * $associated_prod = associated product details
 * column fields/
 */

function getProductDetailsBlockInfo($mode,$module,$focus,$num_of_products='',$associated_prod='')
{
	$productDetails = Array();
	$productBlock = Array();
	if($num_of_products=='')
	{
		$num_of_products = getNoOfAssocProducts($module,$focus);
	}
	$productDetails['no_products'] = $num_of_products;
	if($associated_prod=='')
        {
		$productDetails['product_details'] = getAssociatedProducts($module,$focus);
	}
	else
	{
		$productDetails['product_details'] = $associated_prod;
	}
	$productBlock[] = Array('mode'=>$focus->mode);
	$productBlock[] = $productDetails['product_details'];
	$productBlock[] = Array('taxvalue' => $focus->column_fields['txtTax']);
	$productBlock[] = Array('taxAdjustment' => $focus->column_fields['txtAdjustment']);
	$productBlock[] = Array('hdnSubTotal' => $focus->column_fields['hdnSubTotal']);
	$productBlock[] = Array('hdnGrandTotal' => $focus->column_fields['hdnGrandTotal']);
	return $productBlock;
}
?>
