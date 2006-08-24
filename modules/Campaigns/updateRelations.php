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
require_once('user_privileges/default_module_view.php');
global $adb, $singlepane_view;
$idlist = $_REQUEST['idlist'];
$update_mod = $_REQUEST['destination_module'];
if($update_mod == 'Leads')
{
	$rel_table = 'vtiger_campaignleadrel';
	$mod_table = 'vtiger_leaddetails';
	$mod_field = 'leadid';
}
elseif($update_mod == 'Contacts')
{
	$rel_table = 'vtiger_campaigncontrel';
	$mod_table = 'vtiger_contactdetails';
	$mod_field = 'contactid';
}
if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
{
	//split the string and store in an array
	$storearray = explode (";",$idlist);
	foreach($storearray as $id)
	{
		if($id != '')
		{
		    $sql = "insert into  ".$rel_table." values(".$_REQUEST["parentid"]." , ".$id.")";
	            $adb->query($sql);
		    $sql = "update ".$mod_table." set campaignid = ".$_REQUEST["parentid"]." where ".$mod_field." = ".$id;
                    $adb->query($sql);
		}
	}
	if($singlepane_view == 'true')
		header("Location: index.php?action=DetailView&module=Campaigns&record=".$_REQUEST["parentid"]);
	else
 		header("Location: index.php?action=CallRelatedList&module=Campaigns&record=".$_REQUEST["parentid"]);
}
elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{	
		$sql = "insert into ".$rel_table." values(".$_REQUEST["parid"].",".$_REQUEST["entityid"].")";
		$adb->query($sql);
		$sql = "update ".$mod_table." set campaignid = ".$_REQUEST["parid"]." where ".$mod_field." = ".$_REQUEST["entityid"];
                $adb->query($sql);
		
		if($singlepane_view == 'true')
			header("Location: index.php?action=DetailView&module=Campaigns&record=".$_REQUEST["parid"]);
		else
 			header("Location: index.php?action=CallRelatedList&module=Campaigns&record=".$_REQUEST["parid"]);
}

?>
