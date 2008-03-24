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
	$log->debug("Entering getProductDetailsBlockInfo(".$mode.",".$module.",".$num_of_products.",".$associated_prod.") method ...");
	
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
			$notification_table = 'InvoiceNotification';
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

	$histid = $adb->getUniqueID($history_table_array[$module]);
 	$modifiedtime = $adb->formatDate(date('YmdHis'));
 	$query = "insert into $history_table_array[$module] values($histid,$id,'$relatedname','$total','$history_fldval',$modifiedtime)";	
	$adb->query($query);

	$log->debug("Exit from function addInventoryHistory");
}

/**	Function used to get the list of Tax types as a array
 *	@param string $available - available or empty where as default is all, if available then the taxes which are available now will be returned otherwise all taxes will be returned
 *      @param string $sh - sh or empty, if sh passed then the shipping and handling related taxes will be returned
 *	return array $taxtypes - return all the tax types as a array
 */
function getAllTaxes($available='all', $sh='')
{
	global $adb, $log;
	$log->debug("Entering into the function getAllTaxes($sh)");
	$taxtypes = Array();
	if($sh != '' && $sh == 'sh')
		$tablename = 'vtiger_shippingtaxinfo';
	else
		$tablename = 'vtiger_inventorytaxinfo';
	
	//This where condition is added to get all products or only availble products
	if($available != 'all' && $available == 'available')
	{
		$where = " where $tablename.deleted=0";
	}
	
	$res = $adb->query("select * from $tablename $where order by deleted");
	$noofrows = $adb->num_rows($res);
	for($i=0;$i<$noofrows;$i++)
	{
		$taxtypes[$i]['taxid'] = $adb->query_result($res,$i,'taxid');
		$taxtypes[$i]['taxname'] = $adb->query_result($res,$i,'taxname');
		$taxtypes[$i]['taxlabel'] = $adb->query_result($res,$i,'taxlabel');
		$taxtypes[$i]['percentage'] = $adb->query_result($res,$i,'percentage');
		$taxtypes[$i]['deleted'] = $adb->query_result($res,$i,'deleted');
	}
	$log->debug("Exit from the function getAllTaxes($sh)");
	
	return $taxtypes;
}

/**	Function used to get all the tax details which are associated to the given product
 *	@param int $productid - product id to which we want to get all the associated taxes
 *	@param string $available - available or empty or available_associated where as default is all, if available then the taxes which are available now will be returned, if all then all taxes will be returned otherwise if the value is available_associated then all the associated taxes even they are not available and all the available taxes will be retruned
 *	@return array $tax_details - tax details as a array with productid, taxid, taxname, percentage and deleted
 */
function getTaxDetailsForProduct($productid, $available='all')
{
	global $log, $adb;
	$log->debug("Entering into function getTaxDetailsForProduct($productid)");
	if($productid != '')
	{
		//where condition added to avoid to retrieve the non available taxes
		$where = '';
		if($available != 'all' && $available == 'available')
		{
			$where = ' and vtiger_inventorytaxinfo.deleted=0';
		}
		if($available != 'all' && $available == 'available_associated')
		{
			$query = "SELECT vtiger_producttaxrel.*, vtiger_inventorytaxinfo.* FROM vtiger_inventorytaxinfo left JOIN vtiger_producttaxrel ON vtiger_inventorytaxinfo.taxid = vtiger_producttaxrel.taxid WHERE vtiger_producttaxrel.productid = $productid or vtiger_inventorytaxinfo.deleted=0 GROUP BY vtiger_inventorytaxinfo.taxid";
		}
		else
		{
			$query = "SELECT vtiger_producttaxrel.*, vtiger_inventorytaxinfo.* FROM vtiger_inventorytaxinfo INNER JOIN vtiger_producttaxrel ON vtiger_inventorytaxinfo.taxid = vtiger_producttaxrel.taxid WHERE vtiger_producttaxrel.productid = $productid $where";
		}

		//Postgres 8 fixes
 		if( $adb->dbType == "pgsql")
 		    $query = fixPostgresQuery( $query, $log, 0);
		
		$res = $adb->query($query);
		for($i=0;$i<$adb->num_rows($res);$i++)
		{
			$tax_details[$i]['productid'] = $adb->query_result($res,$i,'productid');
			$tax_details[$i]['taxid'] = $adb->query_result($res,$i,'taxid');
			$tax_details[$i]['taxname'] = $adb->query_result($res,$i,'taxname');
			$tax_details[$i]['taxlabel'] = $adb->query_result($res,$i,'taxlabel');
			$tax_details[$i]['percentage'] = $adb->query_result($res,$i,'taxpercentage');
			$tax_details[$i]['deleted'] = $adb->query_result($res,$i,'deleted');
		}
	}
	else
	{
		$log->debug("Product id is empty. we cannot retrieve the associated products.");
	}

	$log->debug("Exit from function getTaxDetailsForProduct($productid)");
	return $tax_details;
}

/**	Function used to delete the Inventory product details for the passed entity
 *	@param int $objectid - entity id to which we want to delete the product details from REQUEST values where as the entity will be Purchase Order, Sales Order, Quotes or Invoice
 *	@param string $return_old_values - string which contains the string return_old_values or may be empty, if the string is return_old_values then before delete old values will be retrieved
 *	@return array $ext_prod_arr - if the second input parameter is 'return_old_values' then the array which contains the productid and quantity which will be retrieved before delete the product details will be returned otherwise return empty
 */
function deleteInventoryProductDetails($objectid, $return_old_values='')
{
	global $log, $adb;
	$log->debug("Entering into function deleteInventoryProductDetails($objectid, $return_old_values='').");
	
	$ext_prod_arr = Array();

	if($return_old_values == 'return_old_values')
	{
		$query1  = "select * from vtiger_inventoryproductrel where id=".$objectid;
        	$result1 = $adb->query($query1);
        	$num_rows = $adb->num_rows($result1);
        	for($i=0; $i<$num_rows;$i++)
        	{
        	        $pro_id = $adb->query_result($result1,$i,"productid");
        	        $pro_qty = $adb->query_result($result1,$i,"quantity");
        	        $ext_prod_arr[$pro_id] = $pro_qty;
        	}
	}
	
        $query2 = "delete from vtiger_inventoryproductrel where id=".$objectid;
        $adb->query($query2);

        $query3 = "delete from vtiger_inventoryshippingrel where id=".$objectid;
        $adb->query($query3);

	$log->debug("Exit from function deleteInventoryProductDetails($objectid, $return_old_values='').");
	return $ext_prod_arr;
}

/**	Function used to save the Inventory product details for the passed entity
 *	@param object reference $focus - object reference to which we want to save the product details from REQUEST values where as the entity will be Purchase Order, Sales Order, Quotes or Invoice
 *	@param string $module - module name
 *	@param $update_prod_stock - true or false (default), if true we have to update the stock for PO only
 *	@return void
 */
function saveInventoryProductDetails($focus, $module, $update_prod_stock='false', $updateDemand='')
{
	global $log, $adb;
	$log->debug("Entering into function saveInventoryProductDetails($module).");

	$ext_prod_arr = Array();
	if($focus->mode == 'edit')
	{
		$return_old_values = '';
		if($module != 'PurchaseOrder')
		{
			$return_old_values = 'return_old_values';
		}

		//we will retrieve the existing product details and store it in a array and then delete all the existing product details and save new values, retrieve the old value and update stock only for SO, Quotes and Invoice not for PO
		$ext_prod_arr = deleteInventoryProductDetails($focus->id,$return_old_values);
	}

	$tot_no_prod = $_REQUEST['totalProductCount'];

	//If the taxtype is group then retrieve all available taxes, else retrive associated taxes for each product inside loop
	if($_REQUEST['taxtype'] == 'group')
		$all_available_taxes = getAllTaxes('available');

	$prod_seq=1;
	for($i=1; $i<=$tot_no_prod; $i++)
	{
		//if the product is deleted then we should avoid saving the deleted products
		if($_REQUEST["deleted".$i] == 1)
			continue;

	        $prod_id = $_REQUEST['hdnProductId'.$i];
		if(isset($_REQUEST['productDescription'.$i]))
			$description = $_REQUEST['productDescription'.$i];
		else{
			$desc_duery = "select vtiger_products.product_description from vtiger_products where vtiger_products.productid=".$prod_id;
			$desc_res = $adb->query($desc_duery);
			$description = $adb->query_result($desc_res,0,"product_description");
		}	
	        $qty = $_REQUEST['qty'.$i];
	        $listprice = $_REQUEST['listPrice'.$i];
		$listprice = getConvertedPrice($listprice);//convert the listPrice into $

		$comment = addslashes($_REQUEST['comment'.$i]);

		//we have to update the Product stock for PurchaseOrder if $update_prod_stock is true
		if($module == 'PurchaseOrder' && $update_prod_stock == 'true')
		{
			addToProductStock($prod_id,$qty);
		}
		if($module == 'SalesOrder')
		{
			if($updateDemand == '-')
			{
				deductFromProductDemand($prod_id,$qty);
			}
			elseif($updateDemand == '+')
			{
				addToProductDemand($prod_id,$qty);
			}
		}

		$query ="insert into vtiger_inventoryproductrel(id, productid, sequence_no, quantity, listprice, comment, description) values($focus->id, $prod_id , $prod_seq, $qty, $listprice, '$comment','$description')";
		$prod_seq++;
		$adb->query($query);

		if($module != 'PurchaseOrder')
		{
			//update the stock with existing details
			updateStk($prod_id,$qty,$focus->mode,$ext_prod_arr,$module);
		}

		//we should update discount and tax details
		$updatequery = "update vtiger_inventoryproductrel set ";

		//set the discount percentage or discount amount in update query, then set the tax values
		if($_REQUEST['discount_type'.$i] == 'percentage')
		{
			$updatequery .= " discount_percent='".$_REQUEST['discount_percentage'.$i]."',";
		}
		elseif($_REQUEST['discount_type'.$i] == 'amount')
		{
			$discount_amount = getConvertedPrice($_REQUEST['discount_amount'.$i]);//convert the amount to $
			$updatequery .= " discount_amount='".$discount_amount."',";
		}

		if($_REQUEST['taxtype'] == 'group')
		{
			for($tax_count=0;$tax_count<count($all_available_taxes);$tax_count++)
			{
				$tax_name = $all_available_taxes[$tax_count]['taxname'];
				$request_tax_name = $tax_name."_group_percentage";
			
				$updatequery .= "$tax_name = '".$_REQUEST[$request_tax_name]."',";
			}
			$updatequery = trim($updatequery,',')." where id=$focus->id and productid=$prod_id";
		}
		else
		{
			$taxes_for_product = getTaxDetailsForProduct($prod_id,'all');
			for($tax_count=0;$tax_count<count($taxes_for_product);$tax_count++)
			{
				$tax_name = $taxes_for_product[$tax_count]['taxname'];
				$request_tax_name = $tax_name."_percentage".$i;
			
				$updatequery .= "$tax_name = '".$_REQUEST[$request_tax_name]."',";
			}
			$updatequery = trim($updatequery,',')." where id=$focus->id and productid=$prod_id";
		}
		// jens 2006/08/19 - protect against empy update queries
 		if( !preg_match( '/set\s+where/i', $updatequery)) {
 		    $adb->query($updatequery);
 		}
	}

	//we should update the netprice (subtotal), taxtype, group discount, S&H charge, S&H taxes, adjustment and total
	//netprice, group discount, taxtype, S&H amount, adjustment and total to entity table

	$updatequery  = " update $focus->table_name set ";

	$subtotal = getConvertedPrice($_REQUEST['subtotal']);//get the subtotal to $
	$updatequery .= " subtotal='".$subtotal."',";

	$updatequery .= " taxtype='".$_REQUEST['taxtype']."',";

	//for discount percentage or discount amount
	if($_REQUEST['discount_type_final'] == 'percentage')
	{
		$updatequery .= " discount_percent='".$_REQUEST['discount_percentage_final']."',";
	}
	elseif($_REQUEST['discount_type_final'] == 'amount')
	{
		$discount_amount_final = getConvertedPrice($_REQUEST['discount_amount_final']);//convert final discount amount to $
		$updatequery .= " discount_amount='".$discount_amount_final."',";
	}
	
	$shipping_handling_charge = getConvertedPrice($_REQUEST['shipping_handling_charge']);//convert the S&H amount to $
	$updatequery .= " s_h_amount='".$shipping_handling_charge."',";

	//if the user gave - sign in adjustment then add with the value
	$adjustmentType = '';
	if($_REQUEST['adjustmentType'] == '-')
		$adjustmentType = $_REQUEST['adjustmentType'];

	$adjustment = $_REQUEST['adjustment'];
	$adjustment = getConvertedPrice($adjustment);//convert the adjustment to $
	$updatequery .= " adjustment='".$adjustmentType.$adjustment."',";

	$total = getConvertedPrice($_REQUEST['total']);//convert total to $
	$updatequery .= " total='".$total."'";

	$id_array = Array('PurchaseOrder'=>'purchaseorderid','SalesOrder'=>'salesorderid','Quotes'=>'quoteid','Invoice'=>'invoiceid');
	//Added where condition to which entity we want to update these values
	//$updatequery .= " where ".$focus->$module_id."=$focus->id";
	$updatequery .= " where ".$id_array[$module]."=$focus->id";

	$adb->query($updatequery);

	//to save the S&H tax details in vtiger_inventoryshippingrel table
	$sh_tax_details = getAllTaxes('all','sh');
	$sh_query_fields = "id,";
	$sh_query_values = "$focus->id,";
	for($i=0;$i<count($sh_tax_details);$i++)
	{
		$tax_name = $sh_tax_details[$i]['taxname']."_sh_percent";
		if($_REQUEST[$tax_name] != '')
		{
			$sh_query_fields .= $sh_tax_details[$i]['taxname'].",";
			$sh_query_values .= $_REQUEST[$tax_name].",";
		}
	}
	$sh_query_fields = trim($sh_query_fields,',');
	$sh_query_values = trim($sh_query_values,',');

	$sh_query = "insert into vtiger_inventoryshippingrel(".$sh_query_fields.") values(".$sh_query_values.")";
	$adb->query($sh_query);

	$log->debug("Exit from function saveInventoryProductDetails($module).");
}

/**	function used to get the tax type for the entity (PO, SO, Quotes or Invoice)
 *	@param string $module - module name
 *	@param int $id - id of the PO or SO or Quotes or Invoice
 *	@return string $taxtype - taxtype for the given entity which will be individual or group
 */
function getInventoryTaxType($module, $id)
{
	global $log, $adb;

	$log->debug("Entering into function getInventoryTaxType($module, $id).");

	$inv_table_array = Array('PurchaseOrder'=>'vtiger_purchaseorder','SalesOrder'=>'vtiger_salesorder','Quotes'=>'vtiger_quotes','Invoice'=>'vtiger_invoice');
	$inv_id_array = Array('PurchaseOrder'=>'purchaseorderid','SalesOrder'=>'salesorderid','Quotes'=>'quoteid','Invoice'=>'invoiceid');
	
	$res = $adb->query("select taxtype from ".$inv_table_array[$module]." where ".$inv_id_array[$module]."=".$id);

	$taxtype = $adb->query_result($res,0,'taxtype');

	$log->debug("Exit from function getInventoryTaxType($module, $id).");

	return $taxtype;
}

/**	function used to get the taxvalue which is associated with a product for PO/SO/Quotes or Invoice
 *	@param int $id - id of PO/SO/Quotes or Invoice
 *	@param int $productid - product id
 *	@param string $taxname - taxname to which we want the value
 *	@return float $taxvalue - tax value
 */
function getInventoryProductTaxValue($id, $productid, $taxname)
{
	global $log, $adb;
	$log->debug("Entering into function getInventoryProductTaxValue($id, $productid, $taxname).");
	
	$res = $adb->query("select $taxname from vtiger_inventoryproductrel where id = $id and productid = $productid");
	$taxvalue = $adb->query_result($res,0,$taxname);

	if($taxvalue == '')
		$taxvalue = '0.00';

	$log->debug("Exit from function getInventoryProductTaxValue($id, $productid, $taxname).");

	return $taxvalue;
}

/**	function used to get the shipping & handling tax percentage for the given inventory id and taxname
 *	@param int $id - entity id which will be PO/SO/Quotes or Invoice id
 *	@param string $taxname - shipping and handling taxname
 *	@return float $taxpercentage - shipping and handling taxpercentage which is associated with the given entity
 */
function getInventorySHTaxPercent($id, $taxname)
{
	global $log, $adb;
	$log->debug("Entering into function getInventorySHTaxPercent($id, $taxname)");
	
	$res = $adb->query("select $taxname from vtiger_inventoryshippingrel where id= $id");
	$taxpercentage = $adb->query_result($res,0,$taxname);

	if($taxpercentage == '')
		$taxpercentage = '0.00';

	$log->debug("Exit from function getInventorySHTaxPercent($id, $taxname)");

	return $taxpercentage;
}


/**	function used to set invoice string and increment invoice id 
 *	@param string $mode - mode should be configure_invoiceno or increment_incoiceno
 *	@param string $req_str - invoice string which is part of the invoice number, this may be alphanumeric characters
 *	@param int $req_no - This should be a number which will written in file and will be used as a next invoice number
 *	@return void. The invoice string and number are stored in the  file CustomInvoiceNo.php so that concatenated string 		with number will be used as a next invoice number
 */

function setInventoryInvoiceNumber($mode, $req_str='', $req_no='')
{
        global $root_directory;
        $filename = $root_directory.'user_privileges/CustomInvoiceNo.php';
        $readhandle = fopen($filename, "r+");
        $buffer = '';
        $new_buffer = '';

	//when we configure the invoice number in Settings this will be used
	if ($mode == "configure_invoiceno" && $req_str != '' && $req_no != '')
	{

 	        while(!feof($readhandle))
               	{
                       	$buffer = fgets($readhandle, 5200);
			list($starter, $tmp) = explode(" = ", $buffer);

			if($starter == '$inv_str')
			{
				$new_buffer .= "\$inv_str = '".$req_str."';\n";
			}
			elseif($starter == '$inv_no')
			{
				$new_buffer .= "\$inv_no = '".$req_no."';\n";
			}
			else
				$new_buffer .= $buffer;
		}
	}
	else if ($mode == "increment_invoiceno")//when we save new invoice we will increment the invoice id and write
	{
		require_once('user_privileges/CustomInvoiceNo.php');
		while(!feof($readhandle))
		{
			$buffer = fgets($readhandle, 5200);
			list($starter, $tmp) = explode(" = ", $buffer);

			if($starter == '$inv_no')
			{
				//if number is 001, 002 like this (starting with zero) then when we increment 1, zeros will be striped out and result comes as 1,2, etc. So we have added 0 previously for the needed length ie., two zeros for 001, 002, etc.,
				//If the value is less than 0, then we assign 0 to it(to avoid error).
				$strip=strlen($inv_no)-strlen($inv_no+1);
				if($strip<0)$strip=0;

				$temp = str_repeat("0",$strip);
				$new_buffer .= "\$inv_no = '".$temp.($inv_no+1)."';\n";
			}
			else
				$new_buffer .= $buffer;

		}
	}

	//we have the contents in buffer. Going to write the contents in file
	fclose($readhandle);
	$handle = fopen($filename, "w");
	fputs($handle, $new_buffer);
	fclose($handle);
}

/**	Function used to check whether the provided invoicenumber is already available or not
 *	@param int $invoiceno - invoice number, which we are going to check for duplicate
 *	@return binary true or false. If invoice number is already available then return true else return false
 */
function CheckDuplicateInvoiceNumber($invoiceno)
{
	global $adb;
	$result=$adb->query("select invoice_no  from vtiger_invoice where invoice_no = '".$invoiceno."'");
	$num_rows = $adb->num_rows($result);

	if($num_rows > 0)
		return true;
	else
		return false;
}


?>
