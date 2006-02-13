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

echo '<form action="index.php" method="post" name="new" id="form">';
echo get_module_title("Users", 'Profile Information', true);

global $adb;
global $theme;
global $theme_path;
global $image_path;
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
	$standCustFld = '';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="profileid" value="'.$profileid.'">';
	$standCustFld .= '<input type="hidden" name="action" value="ProfileEditView">';

	//Check for Current Profile
	global $current_user;
	$current_profile = fetchUserProfileId($current_user->id);
	
	$standCustFld .= '<br><input title="Edit" accessKey="C" class="button" onclick="this.form.action.value=\'ProfileEditView\'" type="submit" name="Edit" value="Edit Profile">&nbsp;&nbsp;';

	if($profileid != 1 && $profileid != 2 && $profileid != 3 && $profileid != 4 && $profileid != $current_profile)

        {
	
		$standCustFld .= '<input title="Delete" accessKey="D" class="button" onclick="this.form.action.value=\'ProfileDeleteStep1\'"  type="submit" name="Delete" value="Delete Profile">';
	}

	$standCustFld .= '<BR><br>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Standard Access Information", "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="60%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=  '<td width="30%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Entity</td>';
	$standCustFld .=  '<td width="15%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Allow</div></td>';
	$standCustFld .=  '<td width="15%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Create/Edit</div></td>';
	$standCustFld .=  '<td width="15%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Delete</div></td>';
	$standCustFld .=  '<td width="15%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">View</div></td>';
	$standCustFld .=  '</tr>';

	$i = 1;	
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
		
		$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$entity_name.'</td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_allow_per.'</div></td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_create_per.'</div></td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_delete_per.'</div></td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_view_per.'</div></td>';
			
		$standCustFld .= '</tr>';
		$i++;
	}
	$standCustFld .='</table>';
	//echo $standCustFld;

	$standCustFld .= '<BR>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" width="60%"><tr><td>';
	$standCustFld .=  get_form_header("Profile Utility Access Information", "", false );
	$standCustFld .= '</td></tr></table>';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="60%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=  '<td width="40%" class="moduleListTitle" style="padding:0px 3px 0px 3px;">Entity</td>';
	$standCustFld .=  '<td width="30%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Import</div></td>';
	$standCustFld .=  '<td width="30%" class="moduleListTitle" style="padding:0px 3px 0px 3px;"><div align="center">Export</div></td>';
	$standCustFld .=  '</tr>';

	$i = 1;	
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
		
		$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;">'.$entity_name.'</td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_import_per.'</div></td>';
		$standCustFld .= '<td style="padding:0px 3px 0px 3px;"><div align="center">'.$tab_export_per.'</div></td>';
			
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
	global $image_path;
	
	if($id == '')
	{
		$value = '&nbsp;';
	}
	elseif($id == 0)
	{
		$value = '<img src="'.$image_path.'yes.gif">';
	}
	elseif($id == 1)
	{
		$value = '<img src="'.$image_path.'no.gif">';
	}
	else
	{
		$value = '&nbsp;';
	}
	return $value;
		
}

?>
