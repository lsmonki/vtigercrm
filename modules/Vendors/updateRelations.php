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
$idlist = $_REQUEST['idlist'];

if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
{
	//split the string and store in an array
	$storearray = explode (";",$idlist);
	foreach($storearray as $id)
	{
		if($id != '')
		{
			$sql = "insert into vendorcontactrel values (".$_REQUEST["parentid"].",".$id.")";
			$adb->query($sql);
			$sql = "insert into seproductsrel values (". $_REQUEST["parentid"] .",".$id.")";
			$adb->query($sql);
		}
	}
 		header("Location: index.php?action=CallRelatedList&module=Vendors&record=".$_REQUEST["parentid"]);
}

elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{

		$sql = "insert into vendorcontactrel values (".$_REQUEST['parid'].",".$_REQUEST['entityid'].")";
		$adb->query($sql);
		$sql = "insert into seproductsrel values (". $_REQUEST["parid"] .",".$_REQUEST["entityid"] .")";
		$adb->query($sql);
 		header("Location:index.php?action=CallRelatedList&module=Vendors&record=".$_REQUEST["parid"]);
}





?>
