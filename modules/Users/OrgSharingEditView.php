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
require_once('include/utils/utils.php');
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo '<form action="index.php" method="post" name="def_org_share" id="form">';
echo get_module_title("Security", "Default Organisation Sharing Privileges", true);

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Users/OrgSharingEditView.html');

$defSharingPermissionData = getDefaultSharingEditAction();
$output = '';
$output .= '<input type="hidden" name="module" value="Users">';
$output .= '<input type="hidden" name="action" value="SaveOrgSharing">';
$output .= '<br><input title="Save" accessKey="C" class="button" type="submit" name="Save" value="'.$mod_strings['LBL_SAVE_PERMISSIONS'].'"><br><br>';
$output .= '<TABLE width="60%" border=0 cellPadding=5 cellSpacing=1 class="formOuterBorder">';
$output .= '<tr>';
$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>'.$mod_strings['LBL_ORG_SHARING_PRIVILEGES'].'</b></td>';
$output .= '<td class="moduleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Access Privilege</b></td>';
$output .=  '</tr>';

$row=1;
foreach($defSharingPermissionData as $tab_id => $def_perr)
{
//	if($tab_id != 8 && $tab_id != 14 && $tab_id != 15 && $tab_id != 18 && $tab_id != 19 && $tab_id != 16 && $tab_id != 22)
//	{
	
	
		$entity_name = getTabname($tab_id);
		if($tab_id == 6)
		{
			$cont_name = getTabname(4);
			$entity_name .= ' & '.$cont_name;
		}

		//$entity_perr= getDefOrgShareActionName($deff_perr)
		$defActionArr=getModuleSharingActionArray($tab_id);

		if ($row%2==0)
			$output .=   '<tr class="evenListRow">';
		else
			$output .=   '<tr class="oddListRow">';

		$output .=   '<TD width="40%" height="21" noWrap style="padding:0px 3px 0px 3px;" >'.$entity_name.'</TD>';
		$output .=  '<TD width="60%" height="21" noWrap style="padding:0px 3px 0px 3px;">';

		if($tab_id != 6)
		{
			$output .= '<select class="select" name="'.$tab_id.'_per">';
		}
		else
		{
			$output .= '<select class="select" name="'.$tab_id.'_per" onchange="checkAccessPermission(this.value)">';
		}
		
		foreach($defActionArr as $shareActId=>$shareActName)
		{
			$selected='';
			if($shareActId == $def_perr)
			{
				$selected='selected';
			}
			$output .= '<option value="'.$shareActId.'" '.$selected. '>'.$shareActName.'</option>';
				
		}


		$output .= '</select>';
		$output .= '</div></TD>';
		$output .=  '</tr>';

		$row++;
//	}
}


$output .=  '</TABLE></form><br>';

$xtpl->assign("DEFAULT_SHARING", $output);
$xtpl->assign("MOD", $mod_strings);
$xtpl->parse("main");
$xtpl->out("main");
?>
