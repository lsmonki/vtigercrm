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
require_once('include/utils.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title("Users", 'Profile Information', true);
echo '<BR>';
//echo get_form_header("Standard Fields", "", false );

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$profileid = $_REQUEST["profileid"];
//Retreiving the tabs permisson array
$tab_perr_array = getTabsPermission($profileid);
$act_perr_arry = getTabsActionPermission($profileid);
$act_utility_arry = getTabsUtilityActionPermission($profileid);


$xtpl=new XTemplate ('modules/Users/ProfileDetailView.html');


$standCustFld = getStdOutput($tab_perr_array, $act_perr_arry, $act_utility_arry,$profileid);

//Standard PickList Fields
function getStdOutput($tab_perr_array, $act_perr_arry, $act_utility_arry,$profileid)
{
	global $adb;
	$standCustFld= '';
	$standCustFld .= '<BR>';
	$standCustFld .= '<table width="25%" cellpadding="2" cellspacing="0" border="0">';
	$standCustFld .= '<form action="index.php" method="post" name="new" id="form">';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="profileid" value="'.$profileid.'">';
	$standCustFld .= '<input type="hidden" name="action" value="ProfileEditView">';
	$standCustFld .= '<tr><br>';
	$standCustFld .= '<td><input title="Edit" accessKey="C" class="button" onclick="this.form.action.value=\'ProfileEditView\'" type="submit" name="Edit" value="Edit Profile">&nbsp;&nbsp;';
	//$standCustFld .= '<input title="Delete" accessKey="D" class="button" onclick="this.form.action.value=\'DeleteProfile\'"  type="submit" name="Delete" value="Delete Profile"></td>';
	$standCustFld .= '</tr></form></table>';
	$standCustFld .= '<BR>';
	$standCustFld .=  get_form_header("Profile Standard Access Information", "", false );
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=   '<td class="moduleListTitle" height="21"><p style="margin-left: 10">Entity</td>';
	$standCustFld .=  '<td class="moduleListTitle">Allow</td>';
	$standCustFld .=  '<td class="moduleListTitle">Create/Edit</td>';
	$standCustFld .=  '<td class="moduleListTitle">Delete</td>';
	$standCustFld .=  '<td class="moduleListTitle">View</td>';
	$standCustFld .=  '</tr>';

	$i = 0;	
	foreach($act_perr_arry as $tabid=>$action_array)
	{
		$entity_name = getTabname($tabid);
		//Tab Permission
		$tab_allow_per_id = $tab_perr_array[$tabid];
		$tab_allow_per = getDisplayValue($tab_allow_per_id);
		//Create/Edit Permission
		$tab_create_per_id = $action_array['1'];
		$tab_create_per = getDisplayValue($tab_create_per_id);
		//Delete Permission
		$tab_delete_per_id = $action_array['2'];
		$tab_delete_per = getDisplayValue($tab_delete_per_id);
		//View Permission
		$tab_view_per_id = $action_array['4'];
		$tab_view_per = getDisplayValue($tab_view_per_id);

		if ($i%2==0)
		{
			$trowclass = 'evenListRow';
		}
		else
		{	
			$trowclass = 'oddListRow';
		}

		$standCustFld .= '<tr class="'.$trowclass.'">';
		
		$standCustFld .= '<td height="21"><p style="margin-left: 10;">'.$entity_name.'</td>';
		$standCustFld .= '<td >'.$tab_allow_per.'</td>';
		$standCustFld .= '<td >'.$tab_create_per.'</td>';
		$standCustFld .= '<td >'.$tab_delete_per.'</td>';
		$standCustFld .= '<td >'.$tab_view_per.'</td>';
			
		$standCustFld .= '</tr>';
		$i++;
	}
	$standCustFld .='</table>';
	//echo $standCustFld;

	$standCustFld .= '<BR>';
	$standCustFld .=  get_form_header("Profile Utility Access Information", "", false );
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=   '<td class="moduleListTitle" height="21"><p style="margin-left: 10">Entity</td>';
	$standCustFld .=  '<td class="moduleListTitle">Import</td>';
	$standCustFld .=  '<td class="moduleListTitle">Export</td>';
	$standCustFld .=  '</tr>';

	$i = 0;	
	foreach($act_utility_arry as $tabid=>$action_array)
	{
		$entity_name = getTabname($tabid);
		//Import Permission
		$tab_import_per_id = $action_array['5'];
		$tab_import_per = getDisplayValue($tab_import_per_id);
		//Export Permission
		$tab_export_per_id = $action_array['6'];
		$tab_export_per = getDisplayValue($tab_export_per_id);
		
		if ($i%2==0)
		{
			$trowclass = 'evenListRow';
		}
		else
		{	
			$trowclass = 'oddListRow';
		}

		$standCustFld .= '<tr class="'.$trowclass.'">';
		
		$standCustFld .= '<td height="21"><p style="margin-left: 10;">'.$entity_name.'</td>';
		$standCustFld .= '<td >'.$tab_import_per.'</td>';
		$standCustFld .= '<td >'.$tab_export_per.'</td>';
			
		$standCustFld .= '</tr>';
		$i++;
	}
	$standCustFld .='</table>';		
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("STANDARDPERMISSIONS", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");

function getDisplayValue($id)
{
	if($id == '')
	{
		$value = '';
	}
	elseif($id == 0)
	{
		$value = 'Yes';
	}
	elseif($id == 1)
	{
		$value = 'No';
	}
	else
	{
		$value = '';
	}
	return $value;
		
}

?>
