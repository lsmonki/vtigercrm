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
require_once('XTemplate/xtpl.php');
require_once('themes/'.$theme.'/layout_utils.php');
require_once('include/utils/UserInfoUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

echo '<form action="index.php" method="post" name="new" id="form">';
echo get_module_title("Users",' Role Information', true);

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$roleid= $_REQUEST['roleid'];

$xtpl=new XTemplate ('modules/Users/RoleDetailView.html');

$standCustFld = getStdOutput($roleid);

//Standard PickList Fields
function getStdOutput($roleid)
{
	global $adb;
	//Retreiving the Role Info
	$roleInfoArr=getRoleInformation($roleid);
	//Retreiving the related profiles
	$roleProfileArr=getRoleRelatedProfiles($roleid);
	//Retreving the related users
	$roleUserArr=getRoleUsers($roleid);

	//Constructing the Profile list
	$profileList='';
	$i=0;
	foreach($roleProfileArr as $profileId=>$profileName)
	{
		if($i != 0)
		{
			$profileList .= ', ';
		}
		$profileList .= '<a href="index.php?module=Users&action=profilePrivileges&profileid='.$profileId.'">'.$profileName.'</a>';
		$i++;	
	}
	//Constructing the Users List

	$userList .='';
	$j=0;
	foreach($roleUserArr as $userId=>$userName)
	{
		if($j != 0)
		{
			$userList .= ',';
		}
		$userList .= '<a href="index.php?module=Users&action=DetailView&record='.$userId.'">'.$userName.'</a>';
		$j++;	
	}
	
	
		
	$rolename=$roleInfoArr[$roleid][0];

	//echo get_form_header("Profiles", "", false );
	$standCustFld= '';
        $standCustFld .= '<input type="hidden" name="module" value="Users">';
        $standCustFld .= '<input type="hidden" name="mode" value="edit">';
        $standCustFld .= '<input type="hidden" name="returnaction" value="RoleDetailView">';
        $standCustFld .= '<input type="hidden" name="roleid" value="'.$roleid.'">';
        $standCustFld .= '<input type="hidden" name="action" value="createrole">';

	$standCustFld .= '<br><input title="Edit" accessKey="C" class="button" onclick="this.form.action.value=\'createrole\'" type="submit" name="Edit" value="Edit Role">&nbsp;&nbsp;';
	//Check for Current User
	global $current_user;
	$current_role = fetchUserRole($current_user->id);
	if($roleid != 1 && $roleid != 2 && $roleid != $current_role)
	{
        	$standCustFld .= '<input title="Delete" accessKey="D" class="button" onclick="this.form.action.value=\'RoleDeleteStep1\'"  type="submit" name="Delete" value="Delete Role">&nbsp;&nbsp';
	}
	$standCustFld .= '<input title="Cancel" accessKey="C" class="button" onclick="window.history.back()" type="button" name="Cancel" value="Cancel">&nbsp;&nbsp;';
        $standCustFld .= '<br><br>';
	$standCustFld .= '<table border="0" cellpadding="5" cellspacing="1" width="50%" class="formOuterBorder">';
	$standCustFld .=  '<tr colspan="2">';
	$standCustFld .=   '<td class="formSecHeader" colspan="2">Role Information</td>';
	$standCustFld .=  '</tr>';	
	$standCustFld .=  '<tr>';
	$standCustFld .=   '<td width="40%" nowrap class="dataLabel" height="21">Role Name: </td>';
	$standCustFld .=   '<td class="dataField">'.$rolename.'</td>';
	$standCustFld .=  '</tr>';
	$standCustFld .=  '<tr>';
	$standCustFld .=   '<td class="dataLabel" nowrap height="21">Associated Profiles: </td>';
	$standCustFld .=   '<td class="dataField">'.$profileList.'</td>';
	$standCustFld .=  '</tr>';
	$standCustFld .=  '<tr>';
	$standCustFld .=   '<td class="dataLabel" nowrap height="21">Associated Users: </td>';
	$standCustFld .=   '<td class="dataField">'.$userList.'</td>';
	$standCustFld .=  '</tr>';
	$standCustFld .='</table>';
	$standCustFld .= '</form>';
	//echo $standCustFld;	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("ROLEINFO", $standCustFld);

$xtpl->parse("main");
$xtpl->out("main");
?>
