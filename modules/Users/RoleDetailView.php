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
require_once('modules/Users/UserInfoUtil.php');

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

$sql = "select * from role where roleid=".$roleid;
$roleResult = $adb->query($sql);

$standCustFld = getStdOutput($roleResult,$roleid);

//Standard PickList Fields
function getStdOutput($roleResult, $roleid)
{
	global $adb;
	//retreiving the associated Profileid
	$sql1 = "select profile.* from role2profile inner join profile on profile.profileid=role2profile.profileid where roleid=".$roleid;
	$result1 = $adb->query($sql1);
	$profilename = $adb->query_result($result1,0,'profilename');
	$rolename = $adb->query_result($roleResult,0,"name");

	global $adb;
	//echo get_form_header("Profiles", "", false );
	$standCustFld= '';
        $standCustFld .= '<input type="hidden" name="module" value="Users">';
        $standCustFld .= '<input type="hidden" name="mode" value="edit">';
        $standCustFld .= '<input type="hidden" name="roleid" value="'.$roleid.'">';
        $standCustFld .= '<input type="hidden" name="action" value="createrole">';

	$standCustFld .= '<br><input title="Edit" accessKey="C" class="button" onclick="this.form.action.value=\'createrole\'" type="submit" name="Edit" value="Edit Role">&nbsp;&nbsp;';
	//Check for Current User
	global $current_user;
	$current_role = fetchUserRole($current_user->id);
	if($roleid != 1 && $roleid != 2 && $roleid != $current_role)
	{
        	$standCustFld .= '<input title="Delete" accessKey="D" class="button" onclick="this.form.action.value=\'RoleDeleteStep1\'"  type="submit" name="Delete" value="Delete Role">';
	}
        
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
	$standCustFld .=   '<td class="dataLabel" nowrap height="21">Associated Profile Name: </td>';
	$standCustFld .=   '<td class="dataField">'.$profilename.'</td>';
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
