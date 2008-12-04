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
$dest_mod = $_REQUEST['destination_module'];
$record = $_REQUEST['record'];
$parenttab = $_REQUEST['parenttab'];

if($singlepane_view == 'true') $action = "DetailView";
else $action = "CallRelatedList";

if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
{
	//split the string and store in an array
	$storearray = explode (";",trim($idlist,";"));
	foreach($storearray as $id)
	{
		if($id != '')
		{
			if($dest_mod == 'Products')
				$adb->pquery("insert into vtiger_seproductsrel values (?,?,?)", array($_REQUEST["parentid"], $id, 'Contacts'));	
			elseif($dest_mod == 'Campaigns')
				$adb->pquery("insert into vtiger_campaigncontrel values(?,?)", array($id, $_REQUEST["parentid"]));
			elseif($dest_mod == 'Documents')
				$adb->pquery("insert into vtiger_senotesrel values(?,?)", array($_REQUEST['parentid'],$id));
		}
	}
	$record = $_REQUEST["parentid"];
}
elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{
	if($dest_mod == 'Products')
		$adb->pquery("insert into vtiger_seproductsrel values (?,?,?)", array($_REQUEST["parid"], $_REQUEST["entityid"], 'Contacts'));
	elseif($dest_mod == 'Campaigns')
		$adb->pquery("insert into vtiger_campaigncontrel values(?,?)", array($_REQUEST["entityid"], $_REQUEST["parid"]));
	elseif($dest_mod == 'Documents')
		$adb->pquery("insert into vtiger_senotesrel values(?,?)", array($_REQUEST["parid"], $_REQUEST["entityid"]));
	$record = $_REQUEST["parid"];
}
elseif(isset($_REQUEST['pot_id']) && $_REQUEST['pot_id'] != '')
{	
	$sql = "insert into vtiger_contpotentialrel values(?,?)";
	$adb->pquery($sql, array($record, $_REQUEST["pot_id"]));
}

header("Location: index.php?action=$action&module=Contacts&record=$record&parenttab=$parenttab");

?>
