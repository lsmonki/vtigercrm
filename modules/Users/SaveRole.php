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

$rolename = $_REQUEST['roleName'];
$profileId= $_REQUEST['profileId'];
//Inserting values into Role Table
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit')
{
	$roleid = $_REQUEST['roleid'];	
	$sql1 = "update role set name='".$rolename."' where roleid=".$roleid;
	$adb->query($sql1);
	$sql3 = "update role2profile set profileid='".$profileId."' where roleid=".$roleid;
	$adb->query($sql3);	
}
else
{
	$sql1 = "insert into role values('','".$rolename."','')";
	$adb->query($sql1);

	//Retreiving the profileid
	$sql2 = "select max(roleid) as current_id from role";
	$result2 = $adb->query($sql2);
	$current_role_id = $adb->query_result($result2,0,'current_id');
	//Inserting the mapping role2profile table
	$sql3 = "insert into role2profile values(".$current_role_id." ,".$profileId.")";
	$adb->query($sql3);
}

$loc = "Location: index.php?action=listroles&module=Users";
header($loc);
?>
