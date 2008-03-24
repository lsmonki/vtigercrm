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
			$sql = 'delete from vtiger_campaignleadrel where leadid=? and campaignid=?';
			$adb->pquery($sql, array($record, $return_id));
		}
		elseif($return_module == 'Products')//Delete Lead from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=? and productid=?";
			$adb->pquery($sql, array($record, $return_id));
		}
		else
		{
			$sql = 'delete from vtiger_seactivityrel where crmid = ?';
			$adb->pquery($sql, array($record));
		}
	break;
	case Accounts:
		if($return_module == 'Products')//Delete Account from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=? and productid=?";
			$adb->pquery($sql, array($record, $return_id));
		}
		delAccRelRecords($record);
	break;
	case Campaigns:
		if($return_module == "Leads") {
			$sql = 'delete from vtiger_campaignleadrel where campaignid=? and leadid=?';
			$adb->pquery($sql, array($record, $return_id));
		} elseif($return_module == "Contacts") {
			$sql = 'delete from vtiger_campaigncontrel where campaignid=? and contactid=?';
			$adb->pquery($sql, array($record, $return_id));
		}
	break;
	case Contacts:
		if($return_module == 'Accounts')
		{
			$sql = 'update vtiger_contactdetails set accountid = null where contactid = ?';
			$adb->pquery($sql, array($record));
		}
		elseif($return_module == 'Potentials' && $record != '' && $return_id != '')
		{
			$sql = 'delete from vtiger_contpotentialrel where contactid=? and potentialid=?';
			$adb->pquery($sql, array($record, $return_id));
		}
		elseif($return_module == "Campaigns") {
			$sql = 'delete from vtiger_campaigncontrel where contactid=? and campaignid=?';
			$adb->pquery($sql, array($record, $return_id));
		}
		elseif($return_module == 'Products')//Delete Contact from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=? and productid=?";
			$adb->pquery($sql, array($record, $return_id));
		}
		elseif($return_module == 'Vendors')
		{
			$sql = "delete from vtiger_vendorcontactrel where vendorid=? and contactid=?";
			$adb->pquery($sql, array($return_id, $record));
		}
		else
		{
			$sql = "delete from vtiger_cntactivityrel where contactid=?";
			$adb->pquery($sql, array($record));
		}	
		if($record != '' && $return_id != '')
		{
			$sql = 'delete from vtiger_seactivityrel where crmid = ? and activityid = ?';
			$adb->pquery($sql, array($record, $return_id));
			$sql_recentviewed ='delete from vtiger_tracker where user_id = ? and item_id = ?';
			$adb->pquery($sql_recentviewed, array($current_user->id, $record));
		}
		//remove the relationship of contacts with notes while deleting the contact
		$adb->pquery("update vtiger_notes set contact_id=NULL where contact_id=?", array($record));
		$adb->pquery("update vtiger_troubletickets set parent_id=NULL where parent_id=?", array($record));
		$adb->pquery("update vtiger_purchaseorder set contactid=NULL where contactid=?", array($record));
		$adb->pquery("update vtiger_salesorder set contactid=NULL where contactid=?", array($record));
		$adb->pquery("update vtiger_quotes set contactid=NULL where contactid=?", array($record));
		
	break;
	case Potentials:
		if($return_module == 'Accounts')
		{
			//we can call $focus->mark_deleted($record)
			$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
			$adb->pquery($sql, array($record));
			$sql ='delete from vtiger_seactivityrel where crmid = ?';
			$adb->pquery($sql, array($record));
		}
		elseif($return_module == 'Campaigns')
		{
			$sql = 'update vtiger_potential set campaignid = null where potentialid = ?';
			$adb->pquery($sql, array($record));
		}
		elseif($return_module == 'Products')//Delete Potential from Product relatedlist
		{
			$sql = "delete from vtiger_seproductsrel where crmid=? and productid=?";
			$adb->pquery($sql, array($record, $return_id));
		}
		elseif($return_module == 'Contacts')
		{
			$sql = "delete from vtiger_contpotentialrel where potentialid=? and contactid=?";
			$adb->pquery($sql, array($record, $return_id));
		}
		else
		{
			$sql ='delete from vtiger_seactivityrel where crmid = ?';
			$adb->pquery($sql, array($record));
		}	
	break;
	case Calendar:
		if($return_module == 'Contacts')
		{
			$sql = 'delete from vtiger_cntactivityrel where contactid = ? and activityid = ?';
			$adb->pquery($sql, array($return_id, $record));
		}
		else
		{
			$sql= 'delete from vtiger_seactivityrel where activityid=?';
			$adb->pquery($sql, array($record));
		}

		if($return_module == 'HelpDesk')
		{
			$sql = 'delete from vtiger_seticketsrel where ticketid = ? and crmid = ?';
			$adb->pquery($sql, array($return_id, $record));
		}
		$sql = 'delete from vtiger_activity_reminder where activity_id=?';
		$adb->pquery($sql, array($record));

 		$sql = 'delete  from vtiger_recurringevents where activityid=?';
		$adb->pquery($sql, array($record));
	break;
	case Emails:
		$sql='delete from vtiger_seactivityrel where activityid=?';
		$adb->pquery($sql, array($record));
	break;
	case HelpDesk:
		if($return_module == 'Contacts' || $return_module == 'Accounts')
		{
			$sql = "update vtiger_troubletickets set parent_id=null where ticketid=?";
			$adb->pquery($sql, array($record));
			$se_sql= 'delete from vtiger_seticketsrel where ticketid=?';
			$adb->pquery($se_sql, array($record));

		}
		if($return_module == 'Products')
		{
			$sql = "update vtiger_troubletickets set product_id=null where ticketid=?";
			$adb->pquery($sql, array($record));
		}
	break;
	case Notes:
		if($return_module== 'Contacts')
		{
			$sql = 'update vtiger_notes set contact_id = 0 where notesid = ?';
			$adb->pquery($sql, array($record));
		}
		$sql = 'delete from vtiger_senotesrel where notesid = ?';
		$adb->pquery($sql, array($record));
	break;
	case Products:
		if($record != '' && $return_id != '')
		{
			if($return_module == 'Calendar')
				$sql = 'delete from vtiger_seactivityrel where crmid = ? and activityid = ?';

			if($return_module == 'Leads' || $return_module == 'Accounts' || $return_module == 'Contacts' || $return_module == 'Potentials')
				$sql = 'delete from vtiger_seproductsrel where productid = ? and crmid = ?';
			
			if ($sql != null) {
				$adb->pquery($sql, array($record, $return_id));
			}
		}
		if($return_module == "Vendors")
		{
			$sql = "update vtiger_products set vendor_id = null where productid = ?";
			$adb->pquery($sql, array($record));
		}
		//we have to update the product_id as null for the campaigns which are related to this product
		$adb->pquery("update vtiger_campaign set product_id=NULL where product_id = ?", array($record));
	break;
	case PurchaseOrder:
		if($return_module == "Vendors")
		{
			$sql_req ='update vtiger_crmentity set deleted = 1 where crmid= ?';
			$adb->pquery($sql_req, array($record));
		}
		elseif($return_module == "Contacts")
		{
			$sql_req ='UPDATE vtiger_purchaseorder set contactid="" where purchaseorderid = ?';
			$adb->pquery($sql_req, array($record));
		}
	break;
	case SalesOrder:
		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Quotes")
		{
			$relation_query = "UPDATE vtiger_salesorder set quoteid=null where salesorderid=?";
			$adb->pquery($relation_query, array($record));
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE vtiger_salesorder set potentialid=null where salesorderid=?";
			$adb->pquery($relation_query, array($record));
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_salesorder set contactid=null where salesorderid=?";
			$adb->pquery($relation_query, array($record));
		}
	break;
	case Quotes:	
		if($return_module == "Accounts" )
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE vtiger_quotes set potentialid=null where quoteid=?";
			$adb->pquery($relation_query, array($record));
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_quotes set contactid=null where quoteid=?";
			$adb->pquery($relation_query, array($record));
		}
	break;
	case Invoice:
		if($return_module == "Accounts")
		{
			$focus->mark_deleted($record);
		}
		elseif($return_module=="SalesOrder")
		{
			$relation_query = "UPDATE vtiger_invoice set salesorderid=null where invoiceid=?";
			$adb->pquery($relation_query, array($record));
		}
	break;
	case Rss:
		$del_query = "delete from vtiger_rss where rssid=?";
		$adb->pquery($del_query, array($record));
	break;
	case Portal:
		$del_query = "delete from vtiger_portal where portalid=?";
		$adb->pquery($del_query, array($record));
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
		$sql_recentviewed ='delete from vtiger_tracker where user_id = ? and item_id = ?';
        $adb->pquery($sql_recentviewed, array($current_user->id, $record));
	}
	$log->debug("Entering DeleteEntity method ...");
}

function delAccRelRecords($record){

	global $adb;

	//Deleting Account related Potentials.
	$pot_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_potential on vtiger_crmentity.crmid=vtiger_potential.potentialid inner join vtiger_account on vtiger_account.accountid=vtiger_potential.accountid where vtiger_crmentity.deleted=0 and vtiger_potential.accountid=?";
	$pot_res = $adb->pquery($pot_q, array($record));
	for($k=0;$k < $adb->num_rows($pot_res);$k++)
	{
		$pot_id = $adb->query_result($pot_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
		$adb->pquery($sql, array($pot_id));
	}
	//Deleting Account related Quotes.
	$quo_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_quotes on vtiger_crmentity.crmid=vtiger_quotes.quoteid inner join vtiger_account on vtiger_account.accountid=vtiger_quotes.accountid where  vtiger_crmentity.deleted=0 and vtiger_quotes.accountid=?";
	$quo_res = $adb->pquery($quo_q, array($record));
	for($k=0;$k < $adb->num_rows($quo_res);$k++)
	{
		$quo_id = $adb->query_result($quo_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
		$adb->pquery($sql, array($quo_id));
	}
	//Deleting Contact-Account Relation.
	$con_q = "update vtiger_contactdetails set accountid = null where accountid = ?";
	$adb->pquery($con_q, array($record));

	//Deleting Trouble Tickets-Account Relation.
	$tt_q = "update vtiger_troubletickets set parent_id = null where parent_id = ?";
	$adb->pquery($tt_q, array($record));

	//Deleting Activity-Account Relation
        $sql="delete from vtiger_seactivityrel where crmid=?";
        $adb->pquery($sql, array($record));
}

function delVendorRelRecords($record){
	
	global $adb;

	//Deleting Vendor related PO.
	$po_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_purchaseorder on vtiger_crmentity.crmid=vtiger_purchaseorder.purchaseorderid inner join vtiger_vendor on vtiger_vendor.vendorid=vtiger_purchaseorder.vendorid where vtiger_crmentity.deleted=0 and vtiger_purchaseorder.vendorid=?";
	$po_res = $adb->pquery($po_q, array($record));
	for($k=0;$k < $adb->num_rows($po_res);$k++)
	{
		$po_id = $adb->query_result($po_res,$k,"crmid");
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
		$adb->pquery($sql, array($po_id));
	}

	//Deleting Product-Vendor Relation.
	$pro_q = "update vtiger_products set vendor_id = null where vendor_id = ?";
	$adb->pquery($pro_q, array($record));
	//Deleting Contact-Vendor Relaton
	$vc_sql = "delete from vtiger_vendorcontactrel where vendorid=?";
	$adb->pquery($vc_sql, array($record));
}

function delContactRelRecords($record)
{

	global $adb,$log;

	$log->debug("Entering delContactRelRecords($record) method [Contacts Mass Delete] ...");

	//Deleting Contact Potential Relation
	$adb->pquery('delete from vtiger_contpotentialrel where contactid=?', array($record));
	//Deleting Contact Campaign Relation
	$adb->pquery('delete from vtiger_campaigncontrel where contactid=?', array($record));
	//Deleting Contact Products Relation
	$adb->pquery('delete from vtiger_seproductsrel where crmid=?', array($record));
	//Deleting Contact Vendor Relation
	$adb->pquery('delete from vtiger_vendorcontactrel where contactid=?', array($record));
	//Deleting Contact Activity Relation
	$adb->pquery('delete from vtiger_cntactivityrel where contactid=?', array($record));
	$adb->pquery('delete from vtiger_seactivityrel where crmid = ?', array($record));


	//removing the relationship of contacts with notes
	$adb->pquery("update vtiger_notes set contact_id=NULL where contact_id=?", array($record));
	//removing the relationship of contacts with Trouble Tickets
	$adb->pquery("update vtiger_troubletickets set parent_id=NULL where parent_id=?", array($record));
	//removing the relationship of contacts with PurchaseOrder
	$adb->pquery("update vtiger_purchaseorder set contactid=NULL where contactid=?", array($record));
	//removing the relationship of contacts with SalesOrder
	$adb->pquery("update vtiger_salesorder set contactid=NULL where contactid=?", array($record));
	//removing the relationship of contacts with Quotes
	$adb->pquery("update vtiger_quotes set contactid=NULL where contactid=?", array($record));

	$log->debug("Exiting delContactRelRecords method ...");

}

?>
