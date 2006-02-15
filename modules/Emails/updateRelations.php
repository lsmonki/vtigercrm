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

//echo '<pre>'; print_r($_REQUEST['entityid']); echo '</pre>';

if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
{
	//split the strings & store in an array
	$storearray = explode (";",$idlist);
	foreach($storearray as $id)
	{
		if($id != '')
		{
			$record = $_REQUEST["parentid"];
			$sql = "insert into seactivityrel values (". PearDatabase::quote($id).",".PearDatabase::quote($_REQUEST["parentid"]) .")";
			$adb->query($sql);
		}
	}
	header("Location: index.php?action=CallRelatedList&module=Emails&record=".$record);
}
elseif (isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{
	$record = $_REQUEST["parid"];
	//$sql = "insert into seactivityrel values (". $_REQUEST["entityid"] .",".$_REQUEST["parid"] .")";
	$sql = "insert into seactivityrel values (". PearDatabase::quote($_REQUEST["entityid"]).",".PearDatabase::quote($_REQUEST["parid"]) .")";
	$adb->query($sql);
	header("Location: index.php?action=CallRelatedList&module=Emails&record=".$record);
}



if(isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '')
{
	$record = $_REQUEST['record'];
	//$sql = "insert into salesmanactivityrel values (". $_REQUEST["user_id"] .",".$_REQUEST["record"] .")";
	$sql = "insert into salesmanactivityrel values (".PearDatabase::quote($_REQUEST["user_id"]).",".PearDatabase::quote($_REQUEST["record"]).")";
	$adb->query($sql);
	header("Location: index.php?action=CallRelatedList&module=Emails&record=".$record);
}


?>
