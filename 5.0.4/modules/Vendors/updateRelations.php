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

if($singlepane_view == 'true')
	$action = "DetailView";
else
	$action = "CallRelatedList";

$dest_mod = $_REQUEST['destination_module'];

//This will be true, when we select product from vendor related list
if($_REQUEST['destination_module']=='Products')
{
	if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
	{
		$record = $_REQUEST["parentid"];
		$storearray = explode (";",$idlist);
		foreach($storearray as $id)
		{
			if($id != '')
				$adb->query("update vtiger_products set vendor_id=".$record." where productid=".$id);
		}
	}
	elseif($_REQUEST['parid'] != '' && $_REQUEST['entityid'] != '')
	{
		$sql = "update vtiger_products set vendor_id=".$_REQUEST['parid']." where productid=".$_REQUEST['entityid'];
		$adb->query($sql);
		$record = $_REQUEST['parid'];
	}
}

//select contact from vendor relatedlist
if($_REQUEST['destination_module']=='Contacts')
{
	if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
	{
		$record = $_REQUEST["parentid"];

		//split the string and store in an array
		$storearray = explode (";",$idlist);
		foreach($storearray as $id)
		{
			if($id != '')
			{
				$sql = "insert into vtiger_vendorcontactrel values (".$record.",".$id.")";
				$adb->query($sql);
			}
		}
	}
	elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
	{
		$record = $_REQUEST["parid"];

		$sql = "insert into vtiger_vendorcontactrel values (".$record.",".$_REQUEST['entityid'].")";
		$adb->query($sql);
	}
}


header("Location:index.php?action=$action&module=Vendors&record=".$record);



?>
