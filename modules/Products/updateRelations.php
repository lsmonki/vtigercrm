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

if($_REQUEST['destination_module']=='Products')
{
	if($_REQUEST['smodule']=='VENDOR')
	{
		$sql = "update products set vendor_id=".$_REQUEST['parid']." where productid=".$_REQUEST['entityid'];
		$adb->query($sql);
	}
}
if($_REQUEST['destination_module']=='Contacts')
{
	if($_REQUEST['smodule']=='VENDOR')
	{
		$sql = "insert into vendorcontactrel values (".$_REQUEST['parid'].",".$_REQUEST['entityid'].")";
		$adb->query($sql);
	}
}


 header("Location:index.php?action=DetailView&module=Vendors&record=".$_REQUEST["parid"]);






?>
