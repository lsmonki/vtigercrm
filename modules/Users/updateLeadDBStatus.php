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

$idlist= $_POST['idlist'];
$leadstatusval = $_POST['leadval'];
$idval=$_REQUEST['user_id'];

$storearray = explode(";",$idlist);

if(isset($_REQUEST['user_id']) && $_REQUEST['user_id']!='')
{
	foreach($storearray as $id)
	{
		$sql = "update crmentity set smownerid='" .$idval ."'where crmid='" .$id."'";
		$result = $adb->query($sql);
	}
}
elseif(isset($_REQUEST['leadval']) && $_REQUEST['leadval']!='')
{
	foreach($storearray as $id)
	{
		$sql = "update leaddetails set leadstatus='" .$leadstatusval ."'where leadid='" .$id."'";
		$result = $adb->query($sql);
	}
}
header("Location: index.php?module=Leads&action=index");
?>

