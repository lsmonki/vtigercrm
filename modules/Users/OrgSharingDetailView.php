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

$xtpl=new XTemplate ('modules/Users/OrgSharingDetailView.html');

$defSharingPermissionData = getDefaultSharingAction();
$output = '<BR>';
$output .= '<form action="index.php" method="post" name="new" id="form">';
$output .= '<input type="hidden" name="module" value="Users">';
$output .= '<input type="hidden" name="action" value="OrgSharingEditView">';
$output .= '<TABLE width="70%" border=0 cellPadding=0 cellSpacing=1 class="formOuterBorder">';
$output .= '<tr>';
$output .= '<td colspan="2" class="formSecHeader">Organisation Sharing  Privileges &nbsp;  <input title="Edit" accessKey="C" class="button" type="submit" name="Edit" value="Edit Permissions"></td>';
$output .=  '</tr>';

foreach($defSharingPermissionData as $tab_id => $def_perr)
{
	
	$entity_name = getTabname($tab_id);
	if($def_perr == 0)
	{
		$entity_perr = 'Public: Read Only';
	}
	elseif($def_perr == 1)
	{
		$entity_perr = 'Public: Read, Create/Edit ';
	}	
	elseif($def_perr == 2)
	{
		$entity_perr = 'Public: Read, Create/Edit, Delete ';
	}
	elseif($def_perr == 3)
	{
		$entity_perr = 'Private';
	}
	$output .=   '<tr>';
	$output .=   '<TD  class="dataLabel" width="50%" noWrap ><div align="left">'.$entity_name.'</div></TD>';
	$output .=  '<TD  class="dataLabel" width="50%" noWrap ><div align="left">'.$entity_perr.'</div></TD>';
	$output .=  '</tr>';
}


$output .=  '</TABLE></form><br>';

$xtpl->assign("DEFAULT_SHARING", $output);
$xtpl->assign("MOD", $mod_strings);
$xtpl->parse("main");
$xtpl->out("main");
?>
