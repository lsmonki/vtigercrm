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
 * This function returns no value but handles the delete functionality of each entity.
 * Input Parameter are $module - module name, $focus - module object, $record - entity id, $return_id - return entity id. 
 */

function DeleteEntity($module,$return_module,$focus,$record,$return_id)
{
	global $adb;

	switch($module):
	case Accounts:
		if($return_id!='')
		{
			$sql ='delete from seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
			$adb->query($sql);
		}
	break;
	case Activities:
		if($return_module == 'Contacts')
		{
			$sql = 'delete from cntactivityrel where contactid = '.$return_id.' and activityid = '.$record;
			$adb->query($sql);
		}
		else
		{
			$sql= 'delete from seactivityrel where activityid='.$record;
			$adb->query($sql);
		}

		if($return_module == 'HelpDesk')
		{
			$sql = 'delete from seticketsrel where ticketid = '.$return_id.' and crmid = '.$record;
			$adb->query($sql);
		}
		$sql = 'delete from activity_reminder where activity_id='.$record;
 		$adb->query($sql);

 		$sql = 'delete  from recurringevents where activityid='.$record;
 		$adb->query($sql);
	break;
	case Contacts:
		if($return_module == 'Accounts')
		{
			$sql = 'update crmentity set deleted = 1 where crmid = '.$record;
			$adb->query($sql);
		}
		if($return_module == 'Potentials' && $record != '' && $return_id != '')
		{
			$sql = 'delete from contpotentialrel where contactid='.$record.' and potentialid='.$return_id;
			$adb->query($sql);
		}
		if($record != '' && $return_id != '')
		{
			$sql = 'delete from seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
			$adb->query($sql);
			$sql_recentviewed ='delete from tracker where user_id = '.$current_user->id.' and item_id = '.$record;
			$adb->query($sql_recentviewed);
		}
		if($return_module == 'Products')
		{
			$sql = 'delete from vendorcontactrel where contactid='.$record.' and vendorid='.$return_id;
			$adb->query($sql);
		}
	break;
	case Emails:
		$sql='delete from seactivityrel where activityid='.PearDatabase::quote($record);
		$adb->query($sql);
	break;
	case HelpDesk:
		if($return_module == 'Contacts' || $return_module == 'Accounts')
		{
			$sql = "update troubletickets set parent_id='' where ticketid=".$record;
			$adb->query($sql);
			$se_sql= 'delete from seticketsrel where ticketid='.$record;
			$adb->query($se_sql);

		}
		if($return_module == 'Products')
		{
			$sql = "update troubletickets set product_id='' where ticketid=".$record;
			$adb->query($sql);
		}
	break;
	case Invoice:
		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module=="SalesOrder")
		{
			$relation_query = "UPDATE invoice set salesorderid='' where invoiceid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module=="Products")
		{
			//Removing the relation from the quotes product rel
			$inv_query = "select * from invoiceproductrel where productid=".$return_id;
			$result = $adb->query($inv_query);
			$num_rows = $adb->num_rows($result);
			for($i=0; $i< $num_rows; $i++)
			{
				$invoice_id = $adb->query_result($result,$i,"invoiceid");
				$qty = $adb->query_result($result,$i,"quantity");
				$listprice = $adb->query_result($result,$i,"listprice");
				$prod_total = $qty * $listprice;

				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("Invoices","invoice","subtotal","total","invoiceid",$invoice_id,$prod_total);
			}
			//delete the relation from quotes product rel
			$del_query = "delete from invoiceproductrel where productid=".$return_id." and invoiceid=".$record;
			$adb->query($del_query);
		}
	break;
	case Leads:
		$sql = 'delete from seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
		$adb->query($sql);
	break;
	case Notes:
		if($return_module== 'Contacts')
		{
			$sql = 'update notes set contact_id = 0 where notesid = '.$record;
			$adb->query($sql);
		}
		$sql = 'delete from senotesrel where notesid = '.$record. ' and crmid = '.$return_id;
		$adb->query($sql);

	break;
	case Potentials:
		if($return_module == 'Accounts')
		{
			$sql = 'update crmentity set deleted = 1 where crmid = '.$record;
			$adb->query($sql);
		}
		$sql ='delete from seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
		$adb->query($sql);
	break;
	case Products:
		if($record != '' && $return_id != '')
		{
			if($return_module == 'Activities')
				$sql = 'delete from seactivityrel where crmid = '.$record.' and activityid = '.$return_id;

			if($return_module == 'Potentials' || $return_module == 'Accounts' || $return_module == 'Leads')
				$sql = 'delete from seproductsrel where crmid = '.$return_id.' and productid = '.$record;

			$adb->query($sql);
		}
		if($return_module == "Contacts")
		{
			$sql = "UPDATE products set contactid = '' where productid = ".$record;
			$adb->query($sql);
		}
	break;
	case PurchaseOrder:

		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Vendors")
		{
			$sql_req ='DELETE from purchaseorder where purchaseorderid= '.$record;
			$adb->query($sql_req);
		}
		elseif($return_module == "Products")
		{
			//Removing the relation from the po product rel
			$po_query = "select * from poproductrel where productid=".$return_id;
			$result = $adb->query($po_query);
			$num_rows = $adb->num_rows($result);
			for($i=0; $i< $num_rows; $i++)
			{
				$po_id = $adb->query_result($result,$i,"purchaseorderid");
				$qty = $adb->query_result($result,$i,"quantity");
				$listprice = $adb->query_result($result,$i,"listprice");
				$prod_total = $qty * $listprice;
				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("PurchaseOrder","purchaseorder","subtotal","total","purchaseorderid",$po_id,$prod_total);
			}
			//delete the relation from po product rel
			$del_query = "delete from poproductrel where productid=".$return_id." and purchaseorderid=".$record;
			$adb->query($del_query);

		}
		elseif($return_module == "Contacts")
		{
			$sql_req ='UPDATE purchaseorder set contactid="" where purchaseorderid = '.$record;
			$adb->query($sql_req);
		}
	break;
	case Quotes:	
		if($return_module == "Accounts" )
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE quotes set potentialid='' where quoteid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE quotes set contactid='' where quoteid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Products")
		{
			//Removing the relation from the quotes product rel
			$qt_query = "select * from quotesproductrel where productid=".$return_id;
			$result = $adb->query($qt_query);
			$num_rows = $adb->num_rows($result);
			for($i=0; $i< $num_rows; $i++)
			{
				$quote_id = $adb->query_result($result,$i,"quoteid");
				$qty = $adb->query_result($result,$i,"quantity");
				$listprice = $adb->query_result($result,$i,"listprice");
				$prod_total = $qty * $listprice;

				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("Quotes","quotes","subtotal","total","quoteid",$quote_id,$prod_total);
			}
			//delete the relation from quotes product rel
			$del_query = "delete from quotesproductrel where productid=".$return_id." and quoteid=".$record;
			$adb->query($del_query);

		}
	case SalesOrder:
		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Quotes")
		{
			$relation_query = "UPDATE salesorder set quoteid='' where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE salesorder set potentialid='' where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE salesorder set contactid='' where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Products")
		{
			//Removing the relation from the so product rel
			$so_query = "select * from soproductrel where productid=".$return_id;
			$result = $adb->query($so_query);
			$num_rows = $adb->num_rows($result);
			for($i=0; $i< $num_rows; $i++)
			{
				$so_id = $adb->query_result($result,$i,"salesorderid");
				$qty = $adb->query_result($result,$i,"quantity");
				$listprice = $adb->query_result($result,$i,"listprice");
				$prod_total = $qty * $listprice;

				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("SalesOrder","salesorder","subtotal","total","salesorderid",$so_id,$prod_total);
			}
			//delete the relation from so product rel
			$del_query = "delete from soproductrel where productid=".$return_id." and salesorderid=".$record;
			$adb->query($del_query);

		}
	break;
		case Rss:
			$del_query = "delete from rss where rssid=".$record;
			$adb->query($del_query);
	break;
		case Portal:
			$del_query = "delete from portal where portalid=".$record;
			$adb->query($del_query);
	break;
	endswitch;
	global $current_user;
	require_once('include/freetag/freetag.class.php');
	$freetag=new freetag();
	$freetag->delete_all_object_tags_for_user($current_user->id,$record);
	
	if($return_module == $module && $return_module !='Rss' && $return_module !='Portal')
	{	
		$focus->mark_deleted($record);
	}
	if($module != 'Faq')
	{	
		$sql_recentviewed ='delete from tracker where user_id = '.$current_user->id.' and item_id = '.$record;
        	$adb->query($sql_recentviewed);
	}

}
?>
