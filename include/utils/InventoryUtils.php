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

function getProductDetailsBlockInfo($mode,$module,$focus='',$num_of_products='',$associated_prod='')
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
	return $productBlock;
}

/**
 * This function updates the stock information once the product is ordered.
 * Param $productid - product id
 * Param $qty - product quantity in no's
 * Param $mode - mode type
 * Param $ext_prod_arr - existing products 
 * Param $module - module name
 * return type void
 */

function updateStk($product_id,$qty,$mode,$ext_prod_arr,$module)
{
	global $adb;
	global $current_user;
	global $log;

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
	global $current_user;
	global $adb;
	global $log;
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
		$query = "select * from inventorynotification where notificationname='".$notification_table."'";
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
}

/**This function is used to get the quantity in stock of a given product
*Param $product_id - product id
*Returns type numeric
*/
function getPrdQtyInStck($product_id)
{
	global $adb;
	$query1 = "select qtyinstock from products where productid=".$product_id;
	$result=$adb->query($query1);
	$qtyinstck= $adb->query_result($result,0,"qtyinstock");
	return $qtyinstck;
}

/**This function is used to get the reorder level of a product
*Param $product_id - product id
*Returns type numeric
*/

function getPrdReOrderLevel($product_id)
{
	global $adb;
	$query1 = "select reorderlevel from products where productid=".$product_id;
	$result=$adb->query($query1);
	$reorderlevel= $adb->query_result($result,0,"reorderlevel");
	return $reorderlevel;
}

/**This function is used to get the handler for a given product
*Param $product_id - product id
*Returns type numeric
*/

function getPrdHandler($product_id)
{
	global $adb;
	$query1 = "select handler from products where productid=".$product_id;
	$result=$adb->query($query1);
	$handler= $adb->query_result($result,0,"handler");
	return $handler;
}


?>
