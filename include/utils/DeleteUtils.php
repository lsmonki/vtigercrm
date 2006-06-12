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
	global $log;
	$log->debug("Entering DeleteEntity(".$module.",".$return_module.",".$focus.",".$record.",".$return_id.") method ...");
	global $adb;

	switch($module):
	case Accounts:
		if($return_id!='')
		{
			$sql ='delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
			$adb->query($sql);
		}
	break;
	case Activities:
		if($return_module == 'Contacts')
		{
			$sql = 'delete from vtiger_cntactivityrel where contactid = '.$return_id.' and activityid = '.$record;
			$adb->query($sql);
		}
		else
		{
			$sql= 'delete from vtiger_seactivityrel where activityid='.$record;
			$adb->query($sql);
		}

		if($return_module == 'HelpDesk')
		{
			$sql = 'delete from vtiger_seticketsrel where ticketid = '.$return_id.' and crmid = '.$record;
			$adb->query($sql);
		}
		$sql = 'delete from vtiger_activity_reminder where activity_id='.$record;
 		$adb->query($sql);

 		$sql = 'delete  from vtiger_recurringevents where activityid='.$record;
 		$adb->query($sql);
	break;
	case Contacts:
		if($return_module == 'Accounts')
		{
			$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$record;
			$adb->query($sql);
		}
		if($return_module == 'Potentials' && $record != '' && $return_id != '')
		{
			$sql = 'delete from vtiger_contpotentialrel where contactid='.$record.' and potentialid='.$return_id;
			$adb->query($sql);
		}
		if($record != '' && $return_id != '')
		{
			$sql = 'delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
			$adb->query($sql);
			$sql_recentviewed ='delete from vtiger_tracker where user_id = '.$current_user->id.' and item_id = '.$record;
			$adb->query($sql_recentviewed);
		}
		if($return_module == 'Products')
		{
			$sql = 'delete from vtiger_vendorcontactrel where contactid='.$record.' and vendorid='.$return_id;
			$adb->query($sql);
		}
	break;
	case Emails:
		$sql='delete from vtiger_seactivityrel where activityid='.$adb->quote($record);
		$adb->query($sql);
	break;
	case HelpDesk:
		if($return_module == 'Contacts' || $return_module == 'Accounts')
		{
			$sql = "update vtiger_troubletickets set parent_id='' where ticketid=".$record;
			$adb->query($sql);
			$se_sql= 'delete from vtiger_seticketsrel where ticketid='.$record;
			$adb->query($se_sql);

		}
		if($return_module == 'Products')
		{
			$sql = "update vtiger_troubletickets set product_id='' where ticketid=".$record;
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
			$relation_query = "UPDATE vtiger_invoice set salesorderid='' where invoiceid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module=="Products")
		{
			//Removing the relation from the vtiger_quotes product rel
			$inv_query = "select * from vtiger_invoiceproductrel where productid=".$return_id;
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
			//delete the relation from vtiger_quotes product rel
			$del_query = "delete from vtiger_invoiceproductrel where productid=".$return_id." and invoiceid=".$record;
			$adb->query($del_query);
		}
	break;
	case Leads:
		$sql = 'delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
		$adb->query($sql);
	break;
	case Notes:
		if($return_module== 'Contacts')
		{
			$sql = 'update vtiger_notes set contact_id = 0 where notesid = '.$record;
			$adb->query($sql);
		}
		$sql = 'delete from vtiger_senotesrel where notesid = '.$record. ' and crmid = '.$return_id;
		$adb->query($sql);

	break;
	case Potentials:
		if($return_module == 'Accounts')
		{
			$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$record;
			$adb->query($sql);
		}
		$sql ='delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
		$adb->query($sql);
	break;
	case Products:
		if($record != '' && $return_id != '')
		{
			if($return_module == 'Activities')
				$sql = 'delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;

			if($return_module == 'Potentials' || $return_module == 'Accounts' || $return_module == 'Leads')
				$sql = 'delete from vtiger_seproductsrel where crmid = '.$return_id.' and productid = '.$record;

			$adb->query($sql);
		}
		if($return_module == "Contacts")
		{
			$sql = "UPDATE vtiger_products set contactid = '' where productid = ".$record;
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
			$sql_req ='DELETE from vtiger_purchaseorder where purchaseorderid= '.$record;
			$adb->query($sql_req);
		}
		elseif($return_module == "Products")
		{
			//Removing the relation from the po product rel
			$po_query = "select * from vtiger_poproductrel where productid=".$return_id;
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
			$del_query = "delete from vtiger_poproductrel where productid=".$return_id." and purchaseorderid=".$record;
			$adb->query($del_query);

		}
		elseif($return_module == "Contacts")
		{
			$sql_req ='UPDATE vtiger_purchaseorder set contactid="" where purchaseorderid = '.$record;
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
			$relation_query = "UPDATE vtiger_quotes set potentialid='' where quoteid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_quotes set contactid='' where quoteid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Products")
		{
			//Removing the relation from the vtiger_quotes product rel
			$qt_query = "select * from vtiger_quotesproductrel where productid=".$return_id;
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
			//delete the relation from vtiger_quotes product rel
			$del_query = "delete from vtiger_quotesproductrel where productid=".$return_id." and quoteid=".$record;
			$adb->query($del_query);

		}
	case SalesOrder:
		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Quotes")
		{
			$relation_query = "UPDATE vtiger_salesorder set quoteid='' where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE vtiger_salesorder set potentialid='' where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_salesorder set contactid='' where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Products")
		{
			//Removing the relation from the so product rel
			$so_query = "select * from vtiger_soproductrel where productid=".$return_id;
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
			$del_query = "delete from vtiger_soproductrel where productid=".$return_id." and salesorderid=".$record;
			$adb->query($del_query);

		}
	break;
		case Rss:
			$del_query = "delete from vtiger_rss where rssid=".$record;
			$adb->query($del_query);
	break;
		case Portal:
			$del_query = "delete from vtiger_portal where portalid=".$record;
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
		$sql_recentviewed ='delete from vtiger_tracker where user_id = '.$current_user->id.' and item_id = '.$record;
        	$adb->query($sql_recentviewed);
	}
	$log->debug("Entering DeleteEntity method ...");
}
?>
