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

require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');

$idlist= $_REQUEST['idlist'];
$leadstatusval = $_REQUEST['leadval'];
$idval=$_REQUEST['user_id'];
$viewid = $_REQUEST['viewname'];
$return_module = $_REQUEST['return_module'];
$return_action = $_REQUEST['return_action'];
global $rstart;
if(isset($_REQUEST['start']) && $_REQUEST['start']!='')
	{
	$rstart="&start=".$_REQUEST['start'];
	}
$module_array = array (
                          'Leads' => 'updateLeadGroupRelation',
                          'Accounts' => 'updateAccountGroupRelation',
                          'Contacts' => 'updateContactGroupRelation',
                          'Potentials' => 'updatePotentialGroupRelation',
                          'Quotes' => 'updateQuoteGroupRelation',
                          'SalesOrder' => 'updateSoGroupRelation',
                          'Invoice' => 'updateInvoiceGroupRelation',
                          'PurchaseOrder' => 'updatePoGroupRelation',
                          'HelpDesk' => 'updateTicketGroupRelation',
                          'Campaigns' => 'updateCampaignGroupRelation',
                          'Calendar' => 'updateActivityGroupRelation',
                       );

$deletegroup_array = array (
                          'Leads'=>'vtiger_leadgrouprelation',
                          'Accounts'=>'vtiger_accountgrouprelation',
                          'Contacts'=>'vtiger_contactgrouprelation',
                          'Potentials'=>'vtiger_potentialgrouprelation',
                          'Quotes'=>'vtiger_quotegrouprelation',
                          'SalesOrder'=>'vtiger_sogrouprelation',
                          'Invoice'=>'vtiger_invoicegrouprelation',
                          'PurchaseOrder'=>'vtiger_pogrouprelation',
                          'HelpDesk'=>'vtiger_ticketgrouprelation',
                          'Campaigns'=>'vtiger_campaigngrouprelation',
                          'Calendar'=>'vtiger_activitygrouprelation',
                            );
$tableId_array= array (
                       'Leads'=>'leadid',
                          'Accounts'=>'accountid',
                          'Contacts'=>'contactid',
                          'Potentials'=>'potentialid',
                          'Quotes'=>'quoteid',
                          'SalesOrder'=>'salesorderid',
                          'Invoice'=>'invoiceid',
                          'PurchaseOrder'=>'purchaseorderid',
                          'HelpDesk'=>'ticketid',
                          'Campaigns'=>'campaignid',
                          'Calendar'=>'activityid',       
                      );

global $current_user;
global $adb;
$storearray = explode(";",trim($idlist,';'));

$ids_list = array();

$date_var = date('YmdHis');

if((isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='') || ($_REQUEST['group_id'] != ''))
{
	foreach($storearray as $id)
	{
		if(isPermitted($return_module,'EditView',$id) == 'yes')
		{
			if($_REQUEST['user_id'] != '' && $id != '')
			{
				//First we have to delete the group relationship
				$delete_query = "delete from ". $deletegroup_array[$return_module] ." where " . $tableId_array[$return_module] . "='".$id."'";
				$result = $adb->query($delete_query); 
				//Inserting changed owner information to salesmanactivityrel table
				$insert = "insert into vtiger_salesmanactivityrel values(".$idval.",".$id.")";
				$result = $adb->query($insert);
				//Now we have to update the smownerid
				$sql = "update vtiger_crmentity set modifiedby=".$current_user->id.",smownerid='" .$idval ."', modifiedtime=".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var)." where crmid='" .$id."'";
				$result = $adb->query($sql);
			}
			else if($_REQUEST['group_id'] != '' && $id != '')
			{
				//CHANGE HERE -- Here we have to use the getGroupName function. But that function is not correct one because they have used this function to get the assigned group name for the entity - Mickie
				$groupname = $adb->query_result($adb->query("select groupname from vtiger_groups where groupid=".$_REQUEST['group_id']),0,'groupname');
				//This is to update the entity - group relation
				$module_array[$return_module]($id,$groupname); 
				//Now we have to set the smownerid as 0 
				$adb->query("update vtiger_crmentity set smownerid=0 where crmid=$id");
			}
		}
		else
		{
			$ids_list[] = $id;
		}
	}
}
elseif(isset($_REQUEST['leadval']) && $_REQUEST['leadval']!='')
{

	foreach($storearray as $id)
	{
		if(isPermitted($return_module,'EditView',$id) == 'yes')
		{
			if($id != '') {
				$sql = "update vtiger_leaddetails set leadstatus='" .$leadstatusval ."' where leadid='" .$id."'";
				$result = $adb->query($sql);
				$query = "update vtiger_crmentity set modifiedby=".$current_user->id.",modifiedtime=".$adb->formatString("vtiger_crmentity","modifiedtime",$date_var)." where crmid=".$id;
				$result1 = $adb->query($query);
			}
		}
		else
		{
			$ids_list[] = $id;
		}

	}
}
if(count($ids_list) > 0)
{
	$ret_owner = getEntityName($return_module,$ids_list);
        $errormsg = implode(',',$ret_owner);
}else
{
        $errormsg = '';
}
if($return_action == 'ActivityAjax')
{
	$view       = $_REQUEST['view'];
	$day        = $_REQUEST['day'];
	$month      = $_REQUEST['month'];
	$year       = $_REQUEST['year'];
	$type       = $_REQUEST['type'];
	$viewOption = $_REQUEST['viewOption'];
	$subtab     = $_REQUEST['subtab'];
	
	header("Location: index.php?module=$return_module&action=".$return_action."&type=".$type.$rstart."&view=".$view."&day=".$day."&month=".$month."&year=".$year."&viewOption=".$viewOption."&subtab=".$subtab);
}
else
{
	header("Location: index.php?module=$return_module&action=".$return_module."Ajax&file=ListView&ajax=changestate".$rstart."&viewname=".$viewid."&errormsg=".$errormsg);
}
				

?>
