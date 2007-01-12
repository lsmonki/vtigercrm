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

$dest_mod = $_REQUEST['destination_module'];

if($singlepane_view == 'true')
	$action = "DetailView";
else
	$action = "CallRelatedList";

//save the relationship when we select Product from Lead RelatedList
if($dest_mod == 'Products')
{
	$accountid = $_REQUEST['parid'];
	$productid = $_REQUEST['entityid'];
	if($accountid != '' && $productid != '')
		$adb->query("insert into vtiger_seproductsrel values($accountid,$productid,'".$dest_mod."')");
		
	$record = $accountid;
}


$module = "Accounts";
if($_REQUEST['return_module'] != '') $module = $_REQUEST['return_module'];

header("Location: index.php?action=$action&module=$module&record=".$record);


?>
