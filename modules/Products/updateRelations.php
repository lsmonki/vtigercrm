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
global $adb;

//This if will be true, when we select product from vendor related list
if($_REQUEST['destination_module']=='Products')
{
	if($_REQUEST['parid'] != '' && $_REQUEST['entityid'] != '')
	{
		$sql = "update vtiger_products set vendor_id=".$_REQUEST['parid']." where productid=".$_REQUEST['entityid'];
		$adb->query($sql);
	}
}
if($_REQUEST['destination_module']=='Contacts')
{
	if($_REQUEST['smodule']=='VENDOR')
	{
		$sql = "insert into vtiger_vendorcontactrel values (".$_REQUEST['parid'].",".$_REQUEST['entityid'].")";
		$adb->query($sql);
	}
}

$return_action = 'DetailView';
if($_REQUEST['return_action'] != '')
	$return_action = $_REQUEST['return_action'];

header("Location:index.php?action=$return_action&module=Vendors&record=".$_REQUEST["parid"]);






?>
