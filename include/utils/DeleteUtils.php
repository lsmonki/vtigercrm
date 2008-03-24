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
 * Input Parameter are $module - module name, $return_module - return module name, $focus - module object, $record - entity id, $return_id - return entity id. 
 */

function DeleteEntity($module,$return_module,$focus,$record,$return_id)
{
	global $log;
	$log->debug("Entering DeleteEntity(".$module.",".$return_module.",".get_class($focus).",".$record.",".$return_id.") method ...");
	global $adb;
	global $current_user;

	//if we delete the entity from relatedlist then this if will be used ie., the relationship will be deleted otherwise if we delete the entiry from listview then the relationship will not be deleted where as total entity will be deleted 
	if($module != $related_module)
	{

	switch($module):
	case Leads:
		if($return_module == "Campaigns")
		{
			$sql = 'delete from vtiger_campaignleadrel where leadid='.$record.' and campaignid='.$return_id;
			$adb->query($sql);
		}
		elseif($return_module == 'Products')//Delete Lead from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=$record and productid=$return_id";
			$adb->query($sql);
		}
		else
		{
			$sql = 'delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
			$adb->query($sql);
		}
	break;
	case Accounts:
		if($return_module == 'Products')//Delete Account from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=$record and productid=$return_id";
			$adb->query($sql);
		}
		delAccRelRecords($record);
	break;
	case Campaigns:
		if($return_module == "Leads") {
			$sql = 'delete from vtiger_campaignleadrel where campaignid='.$record.' and leadid='.$return_id;
			$adb->query($sql);
		} elseif($return_module == "Contacts") {
			$sql = 'delete from vtiger_campaigncontrel where campaignid='.$record.' and contactid='.$return_id;
			$adb->query($sql);
		}
	break;
	case Contacts:
		if($return_module == 'Accounts')
		{
			$sql = 'update vtiger_contactdetails set accountid = null where contactid = '.$record;
			$adb->query($sql);
		}
		elseif($return_module == 'Potentials' && $record != '' && $return_id != '')
		{
			$sql = 'delete from vtiger_contpotentialrel where contactid='.$record.' and potentialid='.$return_id;
			$adb->query($sql);
		}
		elseif($return_module == "Campaigns") {
			$sql = 'delete from vtiger_campaigncontrel where contactid='.$record.' and campaignid='.$return_id;
			$adb->query($sql);
		}
		elseif($return_module == 'Products')//Delete Contact from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=$record and productid=$return_id";
			$adb->query($sql);
		}
		elseif($return_module == 'Vendors')
		{
			$sql = "delete from vtiger_vendorcontactrel where vendorid=$return_id and contactid=$record";
			$adb->query($sql);
		}
		else
		{
			$sql = "delete from vtiger_cntactivityrel where contactid=".$record;
			$adb->query($sql);
		}	
		if($record != '' && $return_id != '')
		{
			$sql = 'delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;
			$adb->query($sql);
			$sql_recentviewed ='delete from vtiger_tracker where user_id = '.$current_user->id.' and item_id = '.$record;
			$adb->query($sql_recentviewed);
		}
		//remove the relationship of contacts with notes while deleting the contact
		$adb->query("update vtiger_notes set contact_id=NULL where contact_id=".$record);
		$adb->query("update vtiger_troubletickets set parent_id=NULL where parent_id=".$record);
		$adb->query("update vtiger_purchaseorder set contactid=NULL where contactid=".$record);
		$adb->query("update vtiger_salesorder set contactid=NULL where contactid=".$record);
		$adb->query("update vtiger_quotes set contactid=NULL where contactid=".$record);
		
	break;
	case Potentials:
		if($return_module == 'Accounts')
		{
			//we can call $focus->mark_deleted($record)
			$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$record;
			$adb->query($sql);
			$sql ='delete from vtiger_seactivityrel where crmid = '.$record;
			$adb->query($sql);
		}
		elseif($return_module == 'Campaigns')
		{
			$sql = 'update vtiger_potential set campaignid = null where potentialid = '.$record;
		        $adb->query($sql);	
		}
		elseif($return_module == 'Products')//Delete Potential from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=$record and productid=$return_id";
			$adb->query($sql);
		}
		elseif($return_module == 'Contacts')
		{
			$sql = "delete from vtiger_contpotentialrel where potentialid=$record and contactid=$return_id";
			$adb->query($sql);
		}
		else
		{
			$sql ='delete from vtiger_seactivityrel where crmid = '.$record;
			$adb->query($sql);
		}	
	break;
	case Calendar:
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
	case Emails:
		$sql='delete from vtiger_seactivityrel where activityid='.$adb->quote($record);
		$adb->query($sql);
	break;
	case HelpDesk:
		if($return_module == 'Contacts' || $return_module == 'Accounts')
		{
			$sql = "update vtiger_troubletickets set parent_id=null where ticketid=".$record;
			$adb->query($sql);
			$se_sql= 'delete from vtiger_seticketsrel where ticketid='.$record;
			$adb->query($se_sql);

		}
		if($return_module == 'Products')
		{
			$sql = "update vtiger_troubletickets set product_id=null where ticketid=".$record;
			$adb->query($sql);
		}
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
	case Products:
		if($record != '' && $return_id != '')
		{
			if($return_module == 'Calendar')
				$sql = 'delete from vtiger_seactivityrel where crmid = '.$record.' and activityid = '.$return_id;

			if($return_module == 'Leads' || $return_module == 'Accounts' || $return_module == 'Contacts' || $return_module == 'Potentials')
				$sql = 'delete from vtiger_seproductsrel where crmid = '.$return_id.' and productid = '.$record;
			$adb->query($sql);
		}
		if($return_module == "Vendors")
		{
			$sql = "update vtiger_products set vendor_id = null where productid = $record";
			$adb->query($sql);
		}
		//we have to update the product_id as null for the campaigns which are related to this product
		$adb->query("update vtiger_campaign set product_id=NULL where product_id = $record");
	break;
	case PurchaseOrder:

		if($return_module == "Vendors")
		{
			$sql_req ='update vtiger_crmentity set deleted = 1 where crmid= '.$record;
			$adb->query($sql_req);
		}
		elseif($return_module == "Contacts")
		{
			$sql_req ='UPDATE vtiger_purchaseorder set contactid="" where purchaseorderid = '.$record;
			$adb->query($sql_req);
		}

		//Following condition is commented because in Product Relatedlist we have PO which should not be deleted.
		/*
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
				//Handle here -- Tax calculations

				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("PurchaseOrder","vtiger_purchaseorder","subtotal","total","purchaseorderid",$po_id,$prod_total);
			}
			//delete the relation from po product rel
			$del_query = "delete from vtiger_poproductrel where productid=".$return_id." and purchaseorderid=".$record;
			$adb->query($del_query);

		}
		*/
	break;
	case SalesOrder:
		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Quotes")
		{
			$relation_query = "UPDATE vtiger_salesorder set quoteid=null where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE vtiger_salesorder set potentialid=null where salesorderid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_salesorder set contactid=null where salesorderid=".$record;
			$adb->query($relation_query);
		}
		//Following condition is commented because in Product Relatedlist we have SO which should not be deleted.
		/*
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
				//Handle here -- Tax calculations

				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("SalesOrder","vtiger_salesorder","subtotal","total","salesorderid",$so_id,$prod_total);
			}
			//delete the relation from so product rel
			$del_query = "delete from vtiger_soproductrel where productid=".$return_id." and salesorderid=".$record;
			$adb->query($del_query);

		}
		*/
	break;
	case Quotes:	
		if($return_module == "Accounts" )
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE vtiger_quotes set potentialid=null where quoteid=".$record;
			$adb->query($relation_query);
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_quotes set contactid=null where quoteid=".$record;
			$adb->query($relation_query);
		}
		//Following condition is commented because in Product Relatedlist we have Quotes which should not be deleted.
		/*
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
				//Handle here -- Tax calculation

				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("Quotes","vtiger_quotes","subtotal","total","quoteid",$quote_id,$prod_total);
			}
			//delete the relation from vtiger_quotes product rel
			$del_query = "delete from vtiger_quotesproductrel where productid=".$return_id." and quoteid=".$record;
			$adb->query($del_query);

		}
		*/
	break;
	case Invoice:
		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module=="SalesOrder")
		{
			$relation_query = "UPDATE vtiger_invoice set salesorderid=null where invoiceid=".$record;
			$adb->query($relation_query);
		}
		//Following condition is commented because in Product Relatedlist we have Invoice which should not be deleted.
		/*
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
				//Handle here -- tax calculations should be handle here

				//Get the current sub total from Quotes and update it with the new subtotal
				updateSubTotal("Invoices","vtiger_invoice","subtotal","total","invoiceid",$invoice_id,$prod_total);
			}
			//delete the relation from vtiger_quotes product rel
			$del_query = "delete from vtiger_invoiceproductrel where productid=".$return_id." and invoiceid=".$record;
			$adb->query($del_query);
		}
		*/
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
	}

	//this is added to update the crmentity.deleted=1 when we delete from listview and not from relatedlist
	if($return_module == $module && $return_module !='Rss' && $return_module !='Portal')
	{	
		require_once('include/freetag/freetag.class.php');
		$freetag=new freetag();
		$freetag->delete_all_object_tags_for_user($current_user->id,$record);
		$focus->mark_deleted($record);
	}

	//This is to delete the entity information from tracker ie., lastviewed 
	if($module != 'Faq')
	{	
		$sql_recentviewed ='delete from vtiger_tracker where user_id = '.$current_user->id.' and item_id = '.$record;
        	$adb->query($sql_recentviewed);
	}
	$log->debug("Entering DeleteEntity method ...");
}

function delAccRelRecords($record){

	global $adb;

	//Deleting Account related Potentials.
	$pot_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_potential on vtiger_crmentity.crmid=vtiger_potential.potentialid inner join vtiger_account on vtiger_account.accountid=vtiger_potential.accountid where vtiger_crmentity.deleted=0 and vtiger_potential.accountid=".$record;
	$pot_res = $adb->query($pot_q);
	for($k=0;$k < $adb->num_rows($pot_res);$k++)
	{
		$pot_id = $adb->query_result($pot_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$pot_id;
		$adb->query($sql);
	}
	//Deleting Account related Sales Orders.
	/*$so_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_salesorder on vtiger_crmentity.crmid=vtiger_salesorder.salesorderid inner join vtiger_account on vtiger_account.accountid=vtiger_salesorder.accountid where vtiger_crmentity.deleted=0 and vtiger_salesorder.accountid=".$record;
	$so_res = $adb->query($so_q);
	for($k=0;$k < $adb->num_rows($so_res);$k++)
	{
		$so_id = $adb->query_result($so_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$so_id;
		$adb->query($sql);
	}*/
	//Deleting Account related Quotes.
	$quo_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_quotes on vtiger_crmentity.crmid=vtiger_quotes.quoteid inner join vtiger_account on vtiger_account.accountid=vtiger_quotes.accountid where  vtiger_crmentity.deleted=0 and vtiger_quotes.accountid=".$record;
	$quo_res = $adb->query($quo_q);
	for($k=0;$k < $adb->num_rows($quo_res);$k++)
	{
		$quo_id = $adb->query_result($quo_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$quo_id;
		$adb->query($sql);
	}
	//Deleting Account related Invoices.
	/*$inv_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_invoice on vtiger_crmentity.crmid=vtiger_invoice.invoiceid inner join vtiger_account on vtiger_account.accountid=vtiger_invoice.accountid where  vtiger_crmentity.deleted=0 and vtiger_invoice.accountid=".$record;
	$inv_res = $adb->query($inv_q);
	for($k=0;$k < $adb->num_rows($inv_res);$k++)
	{
		$inv_id = $adb->query_result($inv_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$inv_id;
		$adb->query($sql);
	}*/
	//Deleting Contact-Account Relation.
	$con_q = "update vtiger_contactdetails set accountid = null where accountid = ".$record;
	$adb->query($con_q);

	//Deleting Trouble Tickets-Account Relation.
	$tt_q = "update vtiger_troubletickets set parent_id = null where parent_id = ".$record;
	$adb->query($tt_q);

	//Deleting Activity-Account Relation
        $sql="delete from vtiger_seactivityrel where crmid=".$record;
        $adb->query($sql);
}

function delVendorRelRecords($record){
	
	global $adb;

	//Deleting Vendor related PO.
	$po_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_purchaseorder on vtiger_crmentity.crmid=vtiger_purchaseorder.purchaseorderid inner join vtiger_vendor on vtiger_vendor.vendorid=vtiger_purchaseorder.vendorid where vtiger_crmentity.deleted=0 and vtiger_purchaseorder.vendorid=".$record;
	$po_res = $adb->query($po_q);
	for($k=0;$k < $adb->num_rows($po_res);$k++)
	{
		$po_id = $adb->query_result($po_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = '.$po_id;
		$adb->query($sql);
	}

	//Deleting Product-Vendor Relation.
	$pro_q = "update vtiger_products set vendor_id = null where vendor_id = ".$record;
	$adb->query($pro_q);
	//Deleting Contact-Vendor Relaton
	$vc_sql = "delete from vtiger_vendorcontactrel where vendorid=".$record;
	$adb->query($vc_sql);
}

?>
