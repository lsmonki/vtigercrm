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

echo '<form action="index.php" method="post" name="new" id="form">';
echo get_module_title("Security", "Default Organisation Sharing Privileges", true);

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Users/OrgSharingDetailView.html');

$defSharingPermissionData = getDefaultSharingAction();
$output .= '<input type="hidden" name="module" value="Users">';
$output .= '<input type="hidden" name="action" value="OrgSharingEditView">';
$output .= '<br><input title="Edit" accessKey="C" class="button" type="submit" name="Edit" value="'.$mod_strings['LBL_EDIT_PERMISSIONS'].'"><br><br>';
$output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="FormBorder">';
$output .= '<tr>';
$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>'.$mod_strings['LBL_ORG_SHARING_PRIVILEGES'].'</b></td>';
$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Access Privilege</b></td>';
$output .=  '</tr>';

$row=1;
foreach($defSharingPermissionData as $tab_id => $def_perr)
{

	$entity_name = getTabname($tab_id);
	if($entity_name != "Notes" && $entity_name != "Products" && $entity_name != "Faq" && $entity_name != "Vendor" && $entity_name != "PriceBook" && $entity_name != 'Events' && $entity_name != 'SalesOrder')
	{	
		if($def_perr == 0)
		{
			$entity_perr = $mod_strings['LBL_READ_ONLY'];
		}
		elseif($def_perr == 1)
		{
			$entity_perr = $mod_strings['LBL_EDIT_CREATE_ONLY'];
		}	
		elseif($def_perr == 2)
		{
			$entity_perr = $mod_strings['LBL_READ_CREATE_EDIT_DEL'];
		}
		elseif($def_perr == 3)
		{
			$entity_perr = $mod_strings['LBL_PRIVATE'];
		}

		if ($row%2==0)
			$output .=   '<tr class="evenListRow">';
		else
			$output .=   '<tr class="oddListRow">';

		$output .=   '<TD width="40%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$entity_name.'</TD>';
		$output .=  '<TD width="60%" height="21" noWrap style="padding:0px 3px 0px 3px;">'.$entity_perr.'</TD>';
		$output .=  '</tr>';

		$row++;
	}
}


$output .=  '</TABLE></form><br>';

$xtpl->assign("DEFAULT_SHARING", $output);
$xtpl->assign("MOD", $mod_strings);
$xtpl->parse("main");
$xtpl->out("main");
?>
