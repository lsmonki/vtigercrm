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
require_once('XTemplate/xtpl.php');
require_once('include/utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title("Security", "Default Organisation Sharing Privileges", true);
echo '<br>';

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Users/OrgSharingEditView.html');

$defSharingPermissionData = getDefaultSharingAction();
$output = '<BR>';
$output .= '<form action="index.php" method="post" name="new" id="form">';
$output .= '<input type="hidden" name="module" value="Users">';
$output .= '<input type="hidden" name="action" value="SaveOrgSharing">';
$output .= '<TABLE width="70%" border=0 cellPadding=0 cellSpacing=1 class="formOuterBorder">';
$output .= '<tr>';
$output .= '<td colspan="2" class="formSecHeader">Organisation Sharing  Privileges &nbsp;  <input title="Save" accessKey="C" class="button" type="submit" name="Save" value="Save Permissions"></td>';
$output .=  '</tr>';

foreach($defSharingPermissionData as $tab_id => $def_perr)
{
	$selected_a = '';
	$selected_b = '';
	$selected_c = '';
	$selected_d = '';
	$entity_name = getTabname($tab_id);
	if($def_perr == 0)
	{
		$entity_perr = 'Public: Read Only';
		$selected_a = 'selected';
		
	}
	elseif($def_perr == 1)
	{
		$entity_perr = 'Public: Read, Create/Edit ';
		$selected_b = 'selected';
	}	
	elseif($def_perr == 2)
	{
		$entity_perr = 'Public: Read, Create/Edit, Delete ';
		$selected_c = 'selected';
	}
	elseif($def_perr == 3)
	{
		$entity_perr = 'Private';
		$selected_d = 'selected';
	}
	$output .=   '<tr>';
	$output .=   '<TD  class="dataLabel" width="50%" noWrap ><div align="left">'.$entity_name.'</div></TD>';
	$output .=  '<TD  class="dataLabel" width="50%" noWrap ><div align="left">';

	$output .= '<select class="select" name="'.$tab_id.'_per">';
	$output .= '<option value="0" '.$selected_a. '>Public: Read Only</option>';
	$output .= '<option value="1" '.$selected_b.'>Public: Read, Create/Edit</option>';
	$output .= '<option value="2" '.$selected_c.'>Public: Read, Create/Edit, Delete</option>';
	$output .= '<option value="3" '.$selected_d.'>Private</option>';
	$output .= '</select>';
	$output .= '</div></TD>';
	$output .=  '</tr>';
}


$output .=  '</TABLE></form><br>';

$xtpl->assign("DEFAULT_SHARING", $output);
$xtpl->assign("MOD", $mod_strings);
$xtpl->parse("main");
$xtpl->out("main");
?>
