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

	//if we delete the entity from relatedlist then this will be used ie., the relationship will be deleted otherwise if we delete the entiry from listview then the relationship will not be deleted where as total entity will be deleted 
	//if($module != $return_module)
	//{

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
			//Backup Activity-Leads Relation
			$act_q = "select activityid from vtiger_seactivityrel where crmid = ?";
			$act_res = $adb->pquery($act_q, array($record));
			if ($adb->num_rows($act_res) > 0) {
				for($k=0;$k < $adb->num_rows($act_res);$k++)
				{
					$act_id = $adb->query_result($act_res,$k,"activityid");
					$params = array($record, RB_RECORD_DELETED, 'vtiger_seactivityrel', 'crmid', 'activityid', $act_id);
					$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
				}
			}
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
		else {
			delAccRelRecords($record);
		}
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
			$sql = 'update vtiger_contactdetails set accountid = 0 where contactid = ?';
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
		if($record != '') {
			if($return_id != '')
			{
				$sql = 'delete from vtiger_seactivityrel where crmid = ? and activityid = ?';
				$adb->pquery($sql, array($record, $return_id));
				$sql_recentviewed ='delete from vtiger_tracker where user_id = ? and item_id = ?';
				$adb->pquery($sql_recentviewed, array($current_user->id, $record));
			}
			else {
				delContactRelRecords($record);
			}	
		}	
		break;
	case Potentials:
		if($return_module == 'Accounts')
		{
			//we can call $focus->mark_deleted($record)
			$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
			$adb->pquery($sql, array($record));
						
			//Backup Activity-Potentails Relation
			$act_q = "select activityid from vtiger_seactivityrel where crmid = ?";
			$act_res = $adb->pquery($act_q, array($record));
			if ($adb->num_rows($act_res) > 0) {
				for($k=0;$k < $adb->num_rows($act_res);$k++)
				{
					$act_id = $adb->query_result($act_res,$k,"activityid");
					$params = array($record, RB_RECORD_DELETED, 'vtiger_seactivityrel', 'crmid', 'activityid', $act_id);
					$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
				}
			}
			$sql ='delete from vtiger_seactivityrel where crmid = ?';
			$adb->pquery($sql, array($record));
		}
		elseif($return_module == 'Campaigns')
		{
			$sql = 'update vtiger_potential set campaignid = 0 where potentialid = ?';
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
			//Backup Activity-Potentials Relation
			$act_q = "select activityid from vtiger_seactivityrel where crmid = ?";
			$act_res = $adb->pquery($act_q, array($record));
			if ($adb->num_rows($act_res) > 0) {
				for($k=0;$k < $adb->num_rows($act_res);$k++)
				{
					$act_id = $adb->query_result($act_res,$k,"activityid");
					$params = array($record, RB_RECORD_DELETED, 'vtiger_seactivityrel', 'crmid', 'activityid', $act_id);
					$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
				}
			}
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
		elseif($return_module == 'HelpDesk')
		{
			$sql = 'delete from vtiger_seticketsrel where ticketid = ? and crmid = ?';
			$adb->pquery($sql, array($return_id, $record));
		}
		elseif($return_id != '')
		{
			$sql= 'delete from vtiger_seactivityrel where activityid=? and crmid=?';
			$adb->pquery($sql, array($record, $return_id));
		}
		else
		{
			delCalendarRelRecords($record);
		}
		
		break;
	case Emails:
		$sql='delete from vtiger_seactivityrel where activityid=?';
		$adb->pquery($sql, array($record));
		break;
	case HelpDesk:
		if($return_module == 'Contacts' || $return_module == 'Accounts')
		{
			$sql = "update vtiger_troubletickets set parent_id=0 where ticketid=?";
			$adb->pquery($sql, array($record));
			$se_sql= 'delete from vtiger_seticketsrel where ticketid=?';
			$adb->pquery($se_sql, array($record));

		}
		if($return_module == 'Products')
		{
			$sql = "update vtiger_troubletickets set product_id=0 where ticketid=?";
			$adb->pquery($sql, array($record));
		}
		break;
	case Documents:
		if($return_id == '') {
			//Backup Documents Related Records
			$se_q = "select crmid from vtiger_senotesrel where notesid = ?";
			$se_res = $adb->pquery($se_q, array($record));
			if ($adb->num_rows($se_res) > 0) {
				for($k=0;$k < $adb->num_rows($se_res);$k++)
				{
					$se_id = $adb->query_result($se_res,$k,"crmid");
					$params = array($record, RB_RECORD_DELETED, 'vtiger_senotesrel', 'notesid', 'crmid', $se_id);
					$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
				}
			}
			$sql = 'delete from vtiger_senotesrel where notesid = ?';
			$adb->pquery($sql, array($record));			
		} else {
			$sql = 'delete from vtiger_senotesrel where notesid = ? and crmid = ?';
			$adb->pquery($sql, array($record, $return_id));		
		}
		break;
	case Services:
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

			if($return_module == "Vendors")
			{
				$sql = "update vtiger_products set vendor_id = 0 where productid = ?";
				$adb->pquery($sql, array($record));
			}
			
		} else {
			//Backup Campaigns-Product Relation
			$cmp_q = "select campaignid from vtiger_campaign where product_id = ?";
			$cmp_res = $adb->pquery($cmp_q, array($record));
			if ($adb->num_rows($cmp_res) > 0) {
				$cmp_ids_list = array();
				for($k=0;$k < $adb->num_rows($cmp_res);$k++)
				{
					$cmp_ids_list[] = $adb->query_result($cmp_res,$k,"campaignid");
				}
				$params = array($record, RB_RECORD_UPDATED, 'vtiger_campaign', 'product_id', 'campaignid', implode(",", $cmp_ids_list));
				$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
			}
			//we have to update the product_id as null for the campaigns which are related to this product
			delProductRelRecords($record);
			$adb->pquery("update vtiger_campaign set product_id=0 where product_id = ?", array($record));
			$adb->pquery("update vtiger_products set parentid = 0 where parentid = ?", array($record));
		}
		break;
	case PurchaseOrder:
		if($return_module == "Vendors")
		{
			$sql_req ='update vtiger_crmentity set deleted = 1 where crmid= ?';
			$adb->pquery($sql_req, array($record));
		}
		elseif($return_module == "Contacts")
		{
			$sql_req ='UPDATE vtiger_purchaseorder set contactid=0 where purchaseorderid = ?';
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
			$relation_query = "UPDATE vtiger_salesorder set quoteid=0 where salesorderid=?";
			$adb->pquery($relation_query, array($record));
		}
		elseif($return_module == "Potentials")
		{
			$relation_query = "UPDATE vtiger_salesorder set potentialid=0 where salesorderid=?";
			$adb->pquery($relation_query, array($record));
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_salesorder set contactid=0 where salesorderid=?";
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
			$relation_query = "UPDATE vtiger_quotes set potentialid=0 where quoteid=?";
			$adb->pquery($relation_query, array($record));
		}
		elseif($return_module == "Contacts")
		{
			$relation_query = "UPDATE vtiger_quotes set contactid=0 where quoteid=?";
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
			$relation_query = "UPDATE vtiger_invoice set salesorderid=0 where invoiceid=?";
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
	//}
	// Remove relationships from generic table vtiger_crmentityrel - BACKUP/RESTORE yet to be handled
	if(!empty($return_module) && $return_module != $module) {
		$adb->pquery("DELETE FROM vtiger_crmentityrel WHERE (crmid=? AND relcrmid=?) OR (relcrmid=? AND crmid=?)", array($record, $return_id, $record, $return_id));
	}
	//this is added to update the crmentity.deleted=1 when we delete from listview and not from relatedlist
	if($return_module == $module && $return_module !='Rss' && $return_module !='Portal')
	{	
		if(!($return_id!='' && $record != $return_id)){
			require_once('include/freetag/freetag.class.php');
			$freetag=new freetag();
			$freetag->delete_all_object_tags_for_user($current_user->id,$record);
			$focus->mark_deleted($record);
		}
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
	$pot_ids_list = array();
	for($k=0;$k < $adb->num_rows($pot_res);$k++)
	{
		$pot_id = $adb->query_result($pot_res,$k,"crmid");
		$pot_ids_list[] = $pot_id;
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
		$adb->pquery($sql, array($pot_id));
	}
	//Backup deleted Account related Potentials.
	$params = array($record, RB_RECORD_UPDATED, 'vtiger_crmentity', 'deleted', 'crmid', implode(",", $pot_ids_list));
	$adb->pquery("insert into vtiger_relatedlists_rb values(?,?,?,?,?,?)", $params);
	
	//Deleting Account related Quotes.
	$quo_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_quotes on vtiger_crmentity.crmid=vtiger_quotes.quoteid inner join vtiger_account on vtiger_account.accountid=vtiger_quotes.accountid where  vtiger_crmentity.deleted=0 and vtiger_quotes.accountid=?";
	$quo_res = $adb->pquery($quo_q, array($record));
	$quo_ids_list = array();
	for($k=0;$k < $adb->num_rows($quo_res);$k++)
	{
		$quo_id = $adb->query_result($quo_res,$k,"crmid");
		$quo_ids_list[] = $quo_id;
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
		$adb->pquery($sql, array($quo_id));
	}
	//Backup deleted Account related Potentials.
	$params = array($record, RB_RECORD_UPDATED, 'vtiger_crmentity', 'deleted', 'crmid', implode(",", $quo_ids_list));
	$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	
	//Backup Contact-Account Relation
	$con_q = "select contactid from vtiger_contactdetails where accountid = ?";
	$con_res = $adb->pquery($con_q, array($record));
	if ($adb->num_rows($con_res) > 0) {
		$con_ids_list = array();
		for($k=0;$k < $adb->num_rows($con_res);$k++)
		{
			$con_ids_list[] = $adb->query_result($con_res,$k,"contactid");
		}
		$params = array($record, RB_RECORD_UPDATED, 'vtiger_contactdetails', 'accountid', 'contactid', implode(",", $con_ids_list));
		$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	}
	//Deleting Contact-Account Relation.
	$con_q = "update vtiger_contactdetails set accountid = 0 where accountid = ?";
	$adb->pquery($con_q, array($record));

	//Backup Trouble Tickets-Account Relation
	$tkt_q = "select ticketid from vtiger_troubletickets where parent_id = ?";
	$tkt_res = $adb->pquery($tkt_q, array($record));
	if ($adb->num_rows($tkt_res) > 0) {
		$tkt_ids_list = array();
		for($k=0;$k < $adb->num_rows($tkt_res);$k++)
		{
			$tkt_ids_list[] = $adb->query_result($tkt_res,$k,"ticketid");
		}
		$params = array($record, RB_RECORD_UPDATED, 'vtiger_troubletickets', 'parent_id', 'ticketid', implode(",", $tkt_ids_list));
		$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	}
	//Deleting Trouble Tickets-Account Relation.
	$tt_q = "update vtiger_troubletickets set parent_id = 0 where parent_id = ?";
	$adb->pquery($tt_q, array($record));

	//Backup Activity-Account Relation
	$act_q = "select activityid from vtiger_seactivityrel where crmid = ?";
	$act_res = $adb->pquery($act_q, array($record));
	if ($adb->num_rows($act_res) > 0) {
		for($k=0;$k < $adb->num_rows($act_res);$k++)
		{
			$act_id = $adb->query_result($act_res,$k,"activityid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_seactivityrel', 'crmid', 'activityid', $act_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}
	//Deleting Activity-Account Relation
   	$sql="delete from vtiger_seactivityrel where crmid=?";
    $adb->pquery($sql, array($record));
}

function delVendorRelRecords($record){
	
	global $adb;

	//Deleting Vendor related PO.
	$po_q = "select vtiger_crmentity.crmid from vtiger_crmentity inner join vtiger_purchaseorder on vtiger_crmentity.crmid=vtiger_purchaseorder.purchaseorderid inner join vtiger_vendor on vtiger_vendor.vendorid=vtiger_purchaseorder.vendorid where vtiger_crmentity.deleted=0 and vtiger_purchaseorder.vendorid=?";
	$po_res = $adb->pquery($po_q, array($record));
	$po_ids_list = array();
	for($k=0;$k < $adb->num_rows($po_res);$k++)
	{
		$po_id = $adb->query_result($po_res,$k,"crmid");
		$po_ids_list[] = $po_id;
		$sql = 'update vtiger_crmentity set deleted = 1 where crmid = ?';
		$adb->pquery($sql, array($po_id));
	}
	//Backup deleted Vendors related Potentials.
	$params = array($record, RB_RECORD_UPDATED, 'vtiger_crmentity', 'deleted', 'crmid', implode(",", $po_ids_list));
	$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);

	//Backup Product-Vendor Relation
	$pro_q = "select productid from vtiger_products where vendor_id=?";
	$pro_res = $adb->pquery($pro_q, array($record));
	if ($adb->num_rows($pro_res) > 0) {
		$pro_ids_list = array();
		for($k=0;$k < $adb->num_rows($pro_res);$k++)
		{
			$pro_ids_list[] = $adb->query_result($pro_res,$k,"productid");
		}
		$params = array($record, RB_RECORD_UPDATED, 'vtiger_products', 'vendor_id', 'productid', implode(",", $pro_ids_list));
		$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	}
	//Deleting Product-Vendor Relation.
	$pro_q = "update vtiger_products set vendor_id = 0 where vendor_id = ?";
	$adb->pquery($pro_q, array($record));

	//Backup Contact-Vendor Relaton
	$con_q = "select contactid from vtiger_vendorcontactrel where vendorid = ?";
	$con_res = $adb->pquery($con_q, array($record));
	if ($adb->num_rows($con_res) > 0) {
		for($k=0;$k < $adb->num_rows($con_res);$k++)
		{
			$con_id = $adb->query_result($con_res,$k,"contactid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_vendorcontactrel', 'vendorid', 'contactid', $con_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}	
	//Deleting Contact-Vendor Relaton
	$vc_sql = "delete from vtiger_vendorcontactrel where vendorid=?";
	$adb->pquery($vc_sql, array($record));
}

function delContactRelRecords($record)
{

	global $adb,$log;

	$log->debug("Entering delContactRelRecords($record) method [Contacts Mass Delete] ...");

	//Backup Contact Potential Relation
	$pot_q = "select potentialid from vtiger_contpotentialrel where contactid = ?";
	$pot_res = $adb->pquery($pot_q, array($record));
	if ($adb->num_rows($pot_res) > 0) {
		for($k=0;$k < $adb->num_rows($pot_res);$k++)
		{
			$pot_id = $adb->query_result($pot_res,$k,"potentialid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_contpotentialrel', 'contactid', 'potentialid', $pot_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}
	//Deleting Contact Potential Relation
	$adb->pquery('delete from vtiger_contpotentialrel where contactid=?', array($record));

	//Backup Contact Campaign Relation
	$cmp_q = "select campaignid from vtiger_campaigncontrel where contactid = ?";
	$cmp_res = $adb->pquery($cmp_q, array($record));
	if ($adb->num_rows($cmp_res) > 0) {
		for($k=0;$k < $adb->num_rows($cmp_res);$k++)
		{
			$cmp_id = $adb->query_result($cmp_res,$k,"campaignid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_campaigncontrel', 'contactid', 'campaignid', $cmp_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}
	//Deleting Contact Campaign Relation
	$adb->pquery('delete from vtiger_campaigncontrel where contactid=?', array($record));
	
	//Backup Contact Products Relation
	$prod_q = "select productid from vtiger_seproductsrel where crmid=?";
	$prod_res = $adb->pquery($prod_q, array($record));
	if ($adb->num_rows($prod_res) > 0) {
		for($k=0;$k < $adb->num_rows($prod_res);$k++)
		{
			$prod_id = $adb->query_result($prod_res,$k,"productid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_seproductsrel', 'crmid', 'productid', $prod_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}
	//Deleting Contact Products Relation
	$adb->pquery('delete from vtiger_seproductsrel where crmid=?', array($record));
	
	//Backup Contact Vendor Relation
	$vend_q = "select vendorid from vtiger_vendorcontactrel where contactid=?";
	$vend_res = $adb->pquery($vend_q, array($record));
	if ($adb->num_rows($vend_res) > 0) {
		for($k=0;$k < $adb->num_rows($vend_res);$k++)
		{
			$vend_id = $adb->query_result($vend_res,$k,"vendorid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_vendorcontactrel', 'contactid', 'vendorid', $vend_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}	
	//Deleting Contact Vendor Relation
	$adb->pquery('delete from vtiger_vendorcontactrel where contactid=?', array($record));
	
	//Backup Contact Activity Relation
	$act_q = "select activityid from vtiger_cntactivityrel where contactid=?";
	$act_res = $adb->pquery($act_q, array($record));
	if ($adb->num_rows($act_res) > 0) {
		for($k=0;$k < $adb->num_rows($act_res);$k++)
		{
			$act_id = $adb->query_result($act_res,$k,"activityid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_cntactivityrel', 'contactid', 'activityid', $act_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}
	//Deleting Contact Activity Relation
	$adb->pquery('delete from vtiger_cntactivityrel where contactid=?', array($record));
	
	//Backup Contact Activity Relation
	$act_q = "select activityid from vtiger_seactivityrel where crmid=?";
	$act_res = $adb->pquery($act_q, array($record));
	if ($adb->num_rows($act_res) > 0) {
		for($k=0;$k < $adb->num_rows($act_res);$k++)
		{
			$act_id = $adb->query_result($act_res,$k,"activityid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_seactivityrel', 'crmid', 'activityid', $act_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
	}
	//Deleting Contact Activity Relation
	$adb->pquery('delete from vtiger_seactivityrel where crmid = ?', array($record));

	//Backup Contact-Documents Relation
	$notes_q = "select notesid from vtiger_senotesrel where crmid=?";
	$notes_res = $adb->pquery($notes_q, array($record));
	if ($adb->num_rows($notes_res) > 0) {
		$notes_ids_list = array();
		for($k=0;$k < $adb->num_rows($notes_res);$k++)
		{
			$notes_id = $adb->query_result($notes_res,$k,"notesid");
			$params = array($record, RB_RECORD_DELETED, 'vtiger_senotesrel', 'crmid', 'notesid', $notes_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);	
		}
	}
	//removing the relationship of contacts with notes
	$adb->pquery("delete from vtiger_senotesrel where crmid=?", array($record));

	//Backup Contact-Trouble Tickets Relation
	$tkt_q = "select ticketid from vtiger_troubletickets where parent_id=?";
	$tkt_res = $adb->pquery($tkt_q, array($record));
	if ($adb->num_rows($tkt_res) > 0) {
		$tkt_ids_list = array();
		for($k=0;$k < $adb->num_rows($tkt_res);$k++)
		{
			$tkt_ids_list[] = $adb->query_result($tkt_res,$k,"ticketid");
		}
		$params = array($record, RB_RECORD_UPDATED, 'vtiger_troubletickets', 'parent_id', 'ticketid', implode(",", $tkt_ids_list));
		$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	}	
	//removing the relationship of contacts with Trouble Tickets
	$adb->pquery("update vtiger_troubletickets set parent_id=0 where parent_id=?", array($record));

	//Backup Contact-PurchaseOrder Relation
	$po_q = "select purchaseorderid from vtiger_purchaseorder where contactid=?";
	$po_res = $adb->pquery($po_q, array($record));
	if ($adb->num_rows($po_res) > 0) {
		$po_ids_list = array();
		for($k=0;$k < $adb->num_rows($po_res);$k++)
		{
			$po_ids_list[] = $adb->query_result($po_res,$k,"purchaseorderid");
		}
		$params = array($record, RB_RECORD_UPDATED, 'vtiger_purchaseorder', 'contactid', 'purchaseorderid', implode(",", $po_ids_list));
		$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	}	
	//removing the relationship of contacts with PurchaseOrder
	$adb->pquery("update vtiger_purchaseorder set contactid=0 where contactid=?", array($record));

	//Backup Contact-SalesOrder Relation
	$so_q = "select salesorderid from vtiger_salesorder where contactid=?";
	$so_res = $adb->pquery($so_q, array($record));
	if ($adb->num_rows($so_res) > 0) {
		$so_ids_list = array();
		for($k=0;$k < $adb->num_rows($so_res);$k++)
		{
			$so_ids_list[] = $adb->query_result($so_res,$k,"salesorderid");
		}
		$params = array($record, RB_RECORD_UPDATED, 'vtiger_salesorder', 'contactid', 'salesorderid', implode(",", $so_ids_list));
		$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	}	
	//removing the relationship of contacts with SalesOrder
	$adb->pquery("update vtiger_salesorder set contactid=0 where contactid=?", array($record));

	//Backup Contact-Quotes Relation
	$quo_q = "select quoteid from vtiger_quotes where contactid=?";
	$quo_res = $adb->pquery($quo_q, array($record));
	if ($adb->num_rows($quo_res) > 0) {
		$quo_ids_list = array();
		for($k=0;$k < $adb->num_rows($quo_res);$k++)
		{
			$quo_ids_list[] = $adb->query_result($quo_res,$k,"quoteid");
		}
		$params = array($record, RB_RECORD_UPDATED, 'vtiger_quotes', 'contactid', 'quoteid', implode(",", $quo_ids_list));
		$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
	}	
	//removing the relationship of contacts with Quotes
	$adb->pquery("update vtiger_quotes set contactid=0 where contactid=?", array($record));

	$log->debug("Exiting delContactRelRecords method ...");

}

function delCalendarRelRecords($record){
	
	global $adb;

	$sql = 'delete from vtiger_activity_reminder where activity_id=?';
	$adb->pquery($sql, array($record));
	
	$sql = 'delete  from vtiger_recurringevents where activityid=?';
	$adb->pquery($sql, array($record));
	
	//Backup Contact-Activity Relaton
	$con_q = "select contactid from vtiger_cntactivityrel where activityid = ?";
	$con_res = $adb->pquery($con_q, array($record));
	if ($adb->num_rows($con_res) > 0) {
		$con_id_list = array();
		for($k=0;$k < $adb->num_rows($con_res);$k++)
		{
			$con_id = $adb->query_result($con_res,$k,"contactid");
			$con_id_list[] = $con_id;
			$params = array($record, RB_RECORD_DELETED, 'vtiger_cntactivityrel', 'activityid', 'contactid', $con_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}	
		//Deleting Contact-Activity Relaton
		$sql = 'delete from vtiger_cntactivityrel where activityid = ? and contactid in (' . generateQuestionMarks($con_id_list) .')';
		$adb->pquery($sql, array($record, $con_id_list));
	}
	
	//Backup Trouble Ticket-Activity Relaton
	$tkt_q = "select ticketid from vtiger_seticketsrel where crmid = ?";
	$tkt_res = $adb->pquery($tkt_q, array($record));
	if ($adb->num_rows($tkt_res) > 0) {
		$tkt_id_list = array();
		for($k=0;$k < $adb->num_rows($tkt_res);$k++)
		{
			$tkt_id = $adb->query_result($tkt_res,$k,"ticketid");
			$tkt_id_list[] = $tkt_id;
			$params = array($record, RB_RECORD_DELETED, 'vtiger_seticketsrel', 'crmid', 'ticketid', $tkt_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}
		//Deleting Trouble Ticket-Activity Relaton
		$sql = 'delete from vtiger_seticketsrel where crmid = ? and ticketid in ('. generateQuestionMarks($tkt_id_list) .')';
		$adb->pquery($sql, array($record, $tkt_id_list));
	}
	
	//Backup Other modules-Activity Relaton
	$se_q = "select crmid from vtiger_seactivityrel where activityid = ?";
	$se_res = $adb->pquery($se_q, array($record));
	if ($adb->num_rows($se_res) > 0) {
		$se_id_list = array();
		for($k=0;$k < $adb->num_rows($se_res);$k++)
		{
			$se_id = $adb->query_result($se_res,$k,"crmid");
			$se_id_list[] = $se_id;
			$params = array($record, RB_RECORD_DELETED, 'vtiger_seactivityrel', 'activityid', 'crmid', $se_id);
			$adb->pquery("insert into vtiger_relatedlists_rb values (?,?,?,?,?,?)", $params);
		}	
		//Deleting Other modules-Activity Relaton
		$sql= 'delete from vtiger_seactivityrel where activityid=? and crmid in (' . generateQuestionMarks($se_id_list) .')';
		$adb->pquery($sql, array($record, $se_id_list));
	}
}

function delProductRelRecords($record){
	global $adb;
	$adb->pquery("DELETE from vtiger_seproductsrel WHERE productid=? or crmid=?",array($record,$record));
}
?>