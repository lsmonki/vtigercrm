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
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/UserInfoUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$smarty = new vtigerCRM_Smarty;

$roleid= $_REQUEST['roleid'];

//Standard PickList Fields
function getStdOutput($roleid)
{
	//Retreiving the related profiles
	$roleProfileArr=getRoleRelatedProfiles($roleid);
	//Retreving the related users
	$roleUserArr=getRoleUsers($roleid);

	//Constructing the Profile list
	$profileinfo = Array();
	foreach($roleProfileArr as $profileId=>$profileName)
	{
		$profileinfo[]=$profileId;
		$profileinfo[]=$profileName;
		$profileList .= '<a href="index.php?module=Users&action=profilePrivileges&profileid='.$profileId.'">'.$profileName.'</a>';
	}
	$profileinfo=array_chunk($profileinfo,2);
	
	//Constructing the Users List
	$userinfo = Array();
	foreach($roleUserArr as $userId=>$userName)
	{
		$userinfo[]= $userId;
		$userinfo[]= $userName;
		$userList .= '<a href="index.php?module=Users&action=DetailView&record='.$userId.'">'.$userName.'</a>';
	}
	$userinfo=array_chunk($userinfo,2);
	
	//Check for Current User
	global $current_user;
	$current_role = fetchUserRole($current_user->id);
	$return_data = Array('profileinfo'=>$profileinfo,'userinfo'=>$userinfo);
	return $return_data;
}


//Retreiving the Role Info
$roleInfoArr=getRoleInformation($roleid);
$rolename=$roleInfoArr[$roleid][0];
$smarty->assign("ROLE_NAME",$rolename);
$smarty->assign("ROLEID",$roleid);
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("ROLEINFO",getStdOutput($roleid));


$smarty->display("RoleDetailView.tpl");

?>
