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
 * Input Parameter are $module - module name, $focus - module object, $num_of_products - no.of vtiger_products associated with it  * $associated_prod = associated product details
 * column vtiger_fields/
 */

function getProductDetailsBlockInfo($mode,$module,$focus='',$num_of_products='',$associated_prod='')
{
	global $log;
	$log->debug("Entering getProductDetailsBlockInfo(".$mode.",".$module.",".$focus.",".$num_of_products.",".$associated_prod.") method ...");
	
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
	if($focus != '')
	{
		$productBlock[] = Array('mode'=>$focus->mode);
		$productBlock[] = $productDetails['product_details'];
		$productBlock[] = Array('taxvalue' => $focus->column_fields['txtTax']);
		$productBlock[] = Array('taxAdjustment' => $focus->column_fields['txtAdjustment']);
		$productBlock[] = Array('hdnSubTotal' => $focus->column_fields['hdnSubTotal']);
		$productBlock[] = Array('hdnGrandTotal' => $focus->column_fields['hdnGrandTotal']);
	}
	else
	{
		$productBlock[] = Array(Array());
		
	}
	$log->debug("Exiting getProductDetailsBlockInfo method ...");
	return $productBlock;
}

/**
 * This function updates the stock information once the product is ordered.
 * Param $productid - product id
 * Param $qty - product quantity in no's
 * Param $mode - mode type
 * Param $ext_prod_arr - existing vtiger_products 
 * Param $module - module name
 * return type void
 */

function updateStk($product_id,$qty,$mode,$ext_prod_arr,$module)
{
	global $log;
	$log->debug("Entering updateStk(".$product_id.",".$qty.",".$mode.",".$ext_prod_arr.",".$module.") method ...");
	global $adb;
	global $current_user;

	$log->debug("Inside updateStk function, module=".$module);
	$log->debug("Product Id = $product_id & Qty = $qty");

	$prod_name = getProductName($product_id);
	$qtyinstk= getPrdQtyInStck($product_id);
	$log->debug("Prd Qty in Stock ".$qtyinstk);
	
	if($mode == 'edit')
	{
		if(array_key_exists($product_id,$ext_prod_arr))
		{
			$old_qty = $ext_prod_arr[$product_id];
			if($old_qty > $qty)
			{
				$diff_qty = $old_qty - $qty;
				$upd_qty = $qtyinstk+$diff_qty;
				if($module == 'Invoice')
				{
					updateProductQty($product_id, $upd_qty);
					sendPrdStckMail($product_id,$upd_qty,$prod_name,'','',$module);
				}
				else
					sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty,$module);
			}
			elseif($old_qty < $qty)
			{
				$diff_qty = $qty - $old_qty;
				$upd_qty = $qtyinstk-$diff_qty;
				if($module == 'Invoice')
				{
					updateProductQty($product_id, $upd_qty);
					sendPrdStckMail($product_id,$upd_qty,$prod_name,'','',$module);
				}
				else
					sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty,$module);
			}
		}
		else
		{
			$upd_qty = $qtyinstk-$qty;
			if($module == 'Invoice')
			{
				updateProductQty($product_id, $upd_qty);
				sendPrdStckMail($product_id,$upd_qty,$prod_name,'','',$module);
			}
			else
				sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty,$module);
		}
	}
	else
	{
			$upd_qty = $qtyinstk-$qty;
			if($module == 'Invoice')
			{
				updateProductQty($product_id, $upd_qty);
				sendPrdStckMail($product_id,$upd_qty,$prod_name,'','',$module);
			}
			else
				sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty,$module);
	}
	$log->debug("Exiting updateStk method ...");
}

/**
 * This function sends a mail to the handler whenever the product reaches the reorder level.
 * Param $product_id - product id
 * Param $upd_qty - updated product quantity in no's
 * Param $prod_name - product name
 * Param $qtyinstk - quantity in stock 
 * Param $qty - quantity  
 * Param $module - module name
 * return type void
 */

function sendPrdStckMail($product_id,$upd_qty,$prod_name,$qtyinstk,$qty,$module)
{
	global $log;
	$log->debug("Entering sendPrdStckMail(".$product_id.",".$upd_qty.",".$prod_name.",".$qtyinstk.",".$qty.",".$module.") method ...");
	global $current_user;
	global $adb;
	$reorderlevel = getPrdReOrderLevel($product_id);
	$log->debug("Inside sendPrdStckMail function, module=".$module);
	$log->debug("Prd reorder level ".$reorderlevel);
	if($upd_qty < $reorderlevel)
	{
		//send mail to the handler
		$handler=getPrdHandler($product_id);
		$handler_name = getUserName($handler);
		$to_address= getUserEmail($handler);

		//Get the email details from database;
		if($module == 'SalesOrder')
		{
			$notification_table = 'SalesOrderNotification';
			$quan_name = '{SOQUANTITY}';
		}
		if($module == 'Quotes')
		{
			$notification_table = 'QuoteNotification';
			$quan_name = '{QUOTEQUANTITY}';
		}
		if($module == 'Invoice')
		{
			$notificationname = 'InvoiceNotification';
		}
		$query = "select * from vtiger_inventorynotification where notificationname='".$notification_table."'";
		$result = $adb->query($query);

		$subject = $adb->query_result($result,0,'notificationsubject');
		$body = $adb->query_result($result,0,'notificationbody');

		$subject = str_replace('{PRODUCTNAME}',$prod_name,$subject);
		$body = str_replace('{HANDLER}',$handler_name,$body);	
		$body = str_replace('{PRODUCTNAME}',$prod_name,$body);	
		if($module == 'Invoice')
		{
			$body = str_replace('{CURRENTSTOCK}',$upd_qty,$body);	
			$body = str_replace('{REORDERLEVELVALUE}',$reorderlevel,$body);
		}
		else
		{
			$body = str_replace('{CURRENTSTOCK}',$qtyinstk,$body);	
			$body = str_replace($quan_name,$qty,$body);	
		}
		$body = str_replace('{CURRENTUSER}',$current_user->user_name,$body);	

		$mail_status = send_mail($module,$to_address,$current_user->user_name,$current_user->email1,$subject,$body);
	}
	$log->debug("Exiting sendPrdStckMail method ...");
}

/**This function is used to get the quantity in stock of a given product
*Param $product_id - product id
*Returns type numeric
*/
function getPrdQtyInStck($product_id)
{
	global $log;
	$log->debug("Entering getPrdQtyInStck(".$product_id.") method ...");
	global $adb;
	$query1 = "SELECT qtyinstock FROM vtiger_products WHERE productid = ".$product_id;
	$result=$adb->query($query1);
	$qtyinstck= $adb->query_result($result,0,"qtyinstock");
	$log->debug("Exiting getPrdQtyInStck method ...");
	return $qtyinstck;
}

/**This function is used to get the reorder level of a product
*Param $product_id - product id
*Returns type numeric
*/

function getPrdReOrderLevel($product_id)
{
	global $log;
	$log->debug("Entering getPrdReOrderLevel(".$product_id.") method ...");
	global $adb;
	$query1 = "SELECT reorderlevel FROM vtiger_products WHERE productid = ".$product_id;
	$result=$adb->query($query1);
	$reorderlevel= $adb->query_result($result,0,"reorderlevel");
	$log->debug("Exiting getPrdReOrderLevel method ...");
	return $reorderlevel;
}

/**This function is used to get the handler for a given product
*Param $product_id - product id
*Returns type numeric
*/

function getPrdHandler($product_id)
{
	global $log;
	$log->debug("Entering getPrdHandler(".$product_id.") method ...");
	global $adb;
	$query1 = "SELECT handler FROM vtiger_products WHERE productid = ".$product_id;
	$result=$adb->query($query1);
	$handler= $adb->query_result($result,0,"handler");
	$log->debug("Exiting getPrdHandler method ...");
	return $handler;
}

/**	function to get the taxid
 *	@param string $type - tax type (VAT or Sales or Service)
 *	return int   $taxid - taxid corresponding to the Tax type from vtiger_inventorytaxinfo vtiger_table
 */
function getTaxId($type)
{
	global $adb, $log;
	$log->debug("Entering into getTaxId($type) function.");

	$res = $adb->query("SELECT taxid FROM vtiger_inventorytaxinfo WHERE taxname='$type'");
	$taxid = $adb->query_result($res,0,'taxid');

	$log->debug("Exiting from getTaxId($type) function. return value=$taxid");
	return $taxid;
}

/**	function to get the taxpercentage
 *	@param string $type       - tax type (VAT or Sales or Service)
 *	return int $taxpercentage - taxpercentage corresponding to the Tax type from vtiger_inventorytaxinfo vtiger_table
 */
function getTaxPercentage($type)
{
	global $adb, $log;
	$log->debug("Entering into getTaxPercentage($type) function.");

	$taxpercentage = '';

	$res = $adb->query("SELECT percentage FROM vtiger_inventorytaxinfo WHERE taxname = '$type'");
	$taxpercentage = $adb->query_result($res,0,'percentage');

	$log->debug("Exiting from getTaxPercentage($type) function. return value=$taxpercentage");
	return $taxpercentage;
}

/**	function to get the product's taxpercentage
 *	@param string $type       - tax type (VAT or Sales or Service)
 *	@param id  $productid     - productid to which we want the tax percentage
 *	@param id  $default       - if 'default' then first look for product's tax percentage and product's tax is empty then it will return the default configured tax percentage, else it will return the product's tax (not look for default value)
 *	return int $taxpercentage - taxpercentage corresponding to the Tax type from vtiger_inventorytaxinfo vtiger_table
 */
function getProductTaxPercentage($type,$productid,$default='')
{
	global $adb, $log;
	$log->debug("Entering into getProductTaxPercentage($type,$productid) function.");

	$taxpercentage = '';

	$res = $adb->query("SELECT taxpercentage
			FROM vtiger_inventorytaxinfo
			INNER JOIN vtiger_producttaxrel
				ON vtiger_inventorytaxinfo.taxid = vtiger_producttaxrel.taxid
			WHERE vtiger_producttaxrel.productid = $productid
			AND vtiger_inventorytaxinfo.taxname = '$type'");
	$taxpercentage = $adb->query_result($res,0,'taxpercentage');

	//This is to retrive the default configured value if the taxpercentage related to product is empty
	if($taxpercentage == '' && $default == 'default')
		$taxpercentage = getTaxPercentage($type);


	$log->debug("Exiting from getProductTaxPercentage($productid,$type) function. return value=$taxpercentage");
	return $taxpercentage;
}

/**	Function used to add the history entry in the relevant tables for PO, SO, Quotes and Invoice modules
 *	@param string 	$module		- current module name
 *	@param int 	$id		- entity id
 *	@param string 	$relatedname	- parent name of the entity ie, required field venor name for PO and account name for SO, Quotes and Invoice
 *	@param float 	$total		- grand total value of the product details included tax
 *	@param string 	$history_fldval	- history field value ie., quotestage for Quotes and status for PO, SO and Invoice
 */
function addInventoryHistory($module, $id, $relatedname, $total, $history_fldval)
{
	global $log, $adb;
	$log->debug("Entering into function addInventoryHistory($module, $id, $relatedname, $total, $history_fieldvalue)");

	$history_table_array = Array(
					"PurchaseOrder"=>"vtiger_postatushistory",
					"SalesOrder"=>"vtiger_sostatushistory",
					"Quotes"=>"vtiger_quotestagehistory",
					"Invoice"=>"vtiger_invoicestatushistory"
				    );

	$modifiedtime = date('YmdHis');
	$query = "insert into $history_table_array[$module] values('',$id,\"$relatedname\",\"$total\",\"$history_fldval\",\"$modifiedtime\")";
	$adb->query($query);

	$log->debug("Exit from function addInventoryHistory");
}

?>
