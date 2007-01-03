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
$rolename = addslashes($_REQUEST['roleName']);
$mode = $_REQUEST['mode'];
if(isset($_REQUEST['dup_check']) && $_REQUEST['dup_check']!='')
{
	if($mode != 'edit')
	{
		$query = 'select rolename from vtiger_role where rolename="'.$rolename.'"';
	}
	else
	{
		$roleid=$_REQUEST['roleid'];
		$query = 'select rolename from vtiger_role where rolename="'.$rolename.'" and roleid !="'.$roleid.'"';

	}
	$result = $adb->query($query);
	if($adb->num_rows($result) > 0)
	{
		echo 'Role name already exists';
		die;
	}else
	{
		echo 'SUCESS';
		die;
	}

}
$parentRoleId=$_REQUEST['parent'];
//Inserting values into Role Table
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'edit')
{
	$roleId = $_REQUEST['roleid'];
	$selected_col_string = 	$_REQUEST['selectedColumnsString'];
	$profile_array = explode(';',$selected_col_string);
	updateRole($roleId,$rolename,$profile_array);
		
}
elseif(isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'create')
{
	$selected_col_string = 	$_REQUEST['selectedColumnsString'];
	$profile_array = explode(';',$selected_col_string);
	//Inserting into vtiger_role Table
	$roleId = createRole($rolename,$parentRoleId,$profile_array);
	 	
}

$loc = "Location: index.php?action=listroles&module=Settings&parenttab=Settings";
header($loc);
?>
