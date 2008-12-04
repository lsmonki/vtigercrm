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
	$storearray = explode (";",trim($idlist,";")t);
	foreach($storearray as $id)
	{
		if($id != '')
		{
			$record = $_REQUEST["parentid"];
			$sql = "insert into vtiger_seactivityrel values (?,?)";
			$adb->pquery($sql, array($id, $_REQUEST["parentid"]));
		}
	}
	header("Location: index.php?action=CallRelatedList&module=Emails&record=".$record);
}
elseif (isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{
	$record = $_REQUEST["parid"];
	//$sql = "insert into vtiger_seactivityrel values (". $_REQUEST["entityid"] .",".$_REQUEST["parid"] .")";
	$sql = "insert into vtiger_seactivityrel values (?,?)";
	$adb->pquery($sql, array($_REQUEST["entityid"], $_REQUEST["parid"]));
	header("Location: index.php?action=CallRelatedList&module=Emails&record=".$record);
}



if(isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != '')
{
	$record = $_REQUEST['record'];
	//$sql = "insert into vtiger_salesmanactivityrel values (". $_REQUEST["user_id"] .",".$_REQUEST["record"] .")";
	$sql = "insert into vtiger_salesmanactivityrel values (?,?)";
	$adb->pquery($sql, array($_REQUEST["user_id"], $_REQUEST["record"]));
	header("Location: index.php?action=CallRelatedList&module=Emails&record=".$record);
}


?>
