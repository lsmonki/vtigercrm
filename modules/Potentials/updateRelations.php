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

if($singlepane_view == 'true')
	$action = "DetailView";
else
	$action = "CallRelatedList";

if(isset($_REQUEST['idlist']) && $_REQUEST['idlist'] != '')
{
	//split the string and store in an array
	$storearray = explode (";",trim($idlist,";"));
	foreach($storearray as $id)
	{
		if($id != '')
		{
			//When we select contact from potential related list
			if($_REQUEST['destination_module'] == 'Contacts')
			{
				$sql = "insert into vtiger_contpotentialrel values (?,?)";
				$adb->pquery($sql, array($id, $_REQUEST["parentid"]));
			}
			//when we select product from potential related list
			if($_REQUEST['destination_module'] == 'Products')
			{
				$sql = "insert into vtiger_seproductsrel values (?,?,?)";
				$adb->pquery($sql, array($_REQUEST["parentid"], $id,'Potentials'));
			}
			if($dest_mod == 'Documents')
			{
				$sql = "insert into vtiger_senotesrel values (?,?)";
				$adb->pquery($sql, array($_REQUEST['parentid'], $id));
			}
		}
	}

	header("Location: index.php?action=$action&module=Potentials&record=".$_REQUEST["parentid"]);
}
elseif(isset($_REQUEST['entityid']) && $_REQUEST['entityid'] != '')
{
	//When we select contact from potential related list
	if($_REQUEST['destination_module'] == 'Contacts')
	{
		$sql = "insert into vtiger_contpotentialrel values (?,?)";
		$adb->pquery($sql, array($_REQUEST["entityid"], $_REQUEST["parid"]));
	}
	//when we select product from potential related list
	if($_REQUEST['destination_module'] == 'Products')
	{
		$sql = "insert into vtiger_seproductsrel values (?,?,?)";
		$adb->pquery($sql, array($_REQUEST["parid"], $_REQUEST["entityid"], 'Potentials'));
	}
	if($dest_mod == 'Documents')
	{
		$sql = "insert into vtiger_senotsrel values (?,?)";
		$adb->pquery($sql, array($_REQUEST["parid"], $_REQUEST["entityid"]));
	}
	header("Location: index.php?action=$action&module=Potentials&record=".$_REQUEST["parid"]);
}

?>
