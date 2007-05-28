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
$rel_table = 'vtiger_campaigncontrel';
if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
{
	//split the string and store in an array
	$storearray = explode (";",$idlist);
	foreach($storearray as $id)
	{
		if($id != '')
		{
		    $sql = "insert into  ".$rel_table." values(".$id.",".$_REQUEST["parentid"].")";
	            $adb->query($sql);
		}
	}
	if($singlepane_view == 'true')
		header("Location: index.php?action=DetailView&module=Contacts&record=".$_REQUEST["parentid"]);
	else
 		header("Location: index.php?action=CallRelatedList&module=Contacts&record=".$_REQUEST["parentid"]);
}
elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{	
		$sql = "insert into ".$rel_table." values(".$_REQUEST["entityid"].",".$_REQUEST["parid"].")";
		$adb->query($sql);
		if($singlepane_view == 'true')
			header("Location: index.php?action=DetailView&module=Contacts&record=".$_REQUEST["parid"]);
		else
 			header("Location: index.php?action=CallRelatedList&module=Contacts&record=".$_REQUEST["parid"]);
}

?>
