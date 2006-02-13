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

require_once("include/database/PearDatabase.php");

$server=$_REQUEST['server'];
$server_username=$_REQUEST['server_username'];
$server_password=$_REQUEST['server_password'];
$server_type = $_REQUEST['server_type'];

$sql="select * from systems where server_type = '".$server_type."'";
$id=$adb->query_result($adb->query($sql),0,"id");

if($id=='')
{
	$id = $adb->getUniqueID("systems");
	$sql="insert into systems values(" .$id .",'".$server."','".$server_username."','".$server_password."','".$server_type."')";
}
else
	$sql="update systems set server = '".$server."', server_username = '".$server_username."', server_password = '".$server_password."', server_type = '".$server_type."' where id = ".$id;

$adb->query($sql);

header("Location: index.php?module=Settings&action=index");
?>
