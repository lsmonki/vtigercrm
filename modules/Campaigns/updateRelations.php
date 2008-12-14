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
global $adb, $singlepane_view, $currentModule;
$idlist = $_REQUEST['idlist'];
$update_mod = $_REQUEST['destination_module'];
$parenttab = $_REQUEST['parenttab'];

$forCRMRecord = $_REQUEST['parentid'];

if($singlepane_view == 'true')
	$action = "DetailView";
else
	$action = "CallRelatedList";
	
if($update_mod == 'Leads')
{
	$rel_table = 'vtiger_campaignleadrel';
}
elseif($update_mod == 'Contacts')
{
	$rel_table = 'vtiger_campaigncontrel';
}

$storearray = array();
if(!empty($_REQUEST['idlist'])) {
	// Split the string of ids
	$storearray = explode (";",trim($idlist,";"));
} else if(!empty($_REQUEST['entityid'])){
	$storearray = array($_REQUEST['entityid']);
}
foreach($storearray as $id)
{
	if($id != '')
	{
		if ($update_mod == 'Leads' || $update_mod == 'Contacts') {
			$sql = "insert into  $rel_table values(?,?)";
	        $adb->pquery($sql, array($forCRMRecord, $id));
		} else {				
			checkFileAccess("modules/$currentModule/$currentModule.php");
			require_once("modules/$currentModule/$currentModule.php");
			$focus = new $currentModule();
			$focus->save_related_module($currentModule, $forCRMRecord, $update_mod, $id);
		}
	}
}

header("Location: index.php?action=$action&module=$currentModule&record=".$forCRMRecord."&parenttab=".$parenttab);

?>
