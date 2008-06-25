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
$parenttab = $_REQUEST['parenttab'];
if($update_mod == 'Leads')
{
	$rel_table = 'vtiger_campaignleadrel';
}
elseif($update_mod == 'Contacts')
{
	$rel_table = 'vtiger_campaigncontrel';
}
if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
{
	//split the string and store in an array
	$storearray = explode (";",$idlist);
	foreach($storearray as $id)
	{
		if($id != '')
		{
			$sql = "insert into  $rel_table values(?,?)";
	        $adb->pquery($sql, array($_REQUEST["parentid"], $id));
		}
	}
	if($singlepane_view == 'true')
		header("Location: index.php?action=DetailView&module=Campaigns&record=".$_REQUEST["parentid"]."&parenttab=".$parenttab);
	else
 		header("Location: index.php?action=CallRelatedList&module=Campaigns&record=".$_REQUEST["parentid"]."&parenttab=".$parenttab);
}
elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{	
		$sql = "insert into $rel_table values(?,?)";
		$adb->pquery($sql, array($_REQUEST["parid"], $_REQUEST["entityid"]));
		
		if($singlepane_view == 'true')
			header("Location: index.php?action=DetailView&module=Campaigns&record=".$_REQUEST["parid"]."&parenttab=".$parenttab);
		else
 			header("Location: index.php?action=CallRelatedList&module=Campaigns&record=".$_REQUEST["parid"]."&parenttab=".$parenttab);
}

?>
