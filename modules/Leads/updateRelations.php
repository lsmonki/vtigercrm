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
$rel_table = 'vtiger_campaignleadrel';

if($singlepane_view == 'true')
	$action = "DetailView";
else
	$action = "CallRelatedList";

//save the relationship when we select Product from Lead RelatedList
if($dest_mod == 'Products')
{
	$leadid = $_REQUEST['parid'];
	$productid = $_REQUEST['entityid'];
	if($leadid != '' && $productid != '')
		$adb->query("insert into vtiger_seproductsrel values($leadid,$productid,'".$dest_mod."')");
	
	$record = $leadid;
}

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

	$record = $_REQUEST["parentid"];
}
elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{	
	$sql = "insert into ".$rel_table." values(".$_REQUEST["entityid"].",".$_REQUEST["parid"].")";
	$adb->query($sql);

	$record = $_REQUEST["parid"];
}

$module = "Leads";
if($_REQUEST['return_module'] != '') $module = $_REQUEST['return_module'];

header("Location: index.php?action=$action&module=$module&record=".$record);



?>
