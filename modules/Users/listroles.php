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
echo get_module_title("Users",'Roles', true);

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');


$xtpl=new XTemplate ('modules/Users/listroles.html');

$sql = "select * from role";
$roleListResult = $adb->query($sql);
$noofrows = $adb->num_rows($roleListResult);

$standCustFld = getStdOutput($roleListResult, $noofrows, $mod_strings);

//Standard PickList Fields
function getStdOutput($roleListResult, $noofrows, $mod_strings)
{
	global $adb;
	global $app_strings;
	//echo get_form_header("Profiles", "", false );
	$standCustFld= '';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="action" value="createrole">';
	$standCustFld .= '<br><input title="New" accessKey="C" class="button" type="submit" name="New" value="'.$mod_strings['LBL_TITLE_ROLE_NAME'].'">';
	$standCustFld .= '<br><BR>'; 
	$standCustFld .= '<table border="0" cellpadding="5" cellspacing="1" class="FormBorder" width="30%">';
	$standCustFld .=  '<tr>';
	$standCustFld .=   '<td class="ModuleListTitle" width="18%" height="21" style="padding:0px 3px 0px 3px;"><div><b>Operation</b></div></td>';
	$standCustFld .=   '<td class="ModuleListTitle" height="21" style="padding:0px 3px 0px 3px;"><b>'.$mod_strings['LBL_ROLE_NAME'].'</b></td>';
	$standCustFld .=  '</tr>';
	
	$row=1;
	for($i=0; $i<$noofrows; $i++,$row++)
	{
		if ($row%2==0)
		{
			$trowclass = 'evenListRow';
		}
		else
		{	
			$trowclass = 'oddListRow';
		}

		$standCustFld .= '<tr class="'.$trowclass.'">';
		$role_name = $adb->query_result($roleListResult,$i,"name");
		$role_id = $adb->query_result($roleListResult,$i,"roleid");
		$standCustFld .= '<td width="18%" height="21" style="padding:0px 3px 0px 3px;"><div>';
		$standCustFld .= '<a href="index.php?module=Users&action=createrole&mode=edit&roleid='.$role_id.'">'.$app_strings['LNK_EDIT'].'</a>';
		global $current_user;
        	$current_role = fetchUserRole($current_user->id);
        	if($role_id != 1 && $role_id != 2 && $role_id != $current_role)
        	{
		$standCustFld .=' | <a href="index.php?module=Users&action=RoleDeleteStep1&roleid='.$role_id.'">'.$app_strings['LNK_DELETE'].'</a>';
		}
		$standCustFld .= '</div></td>';	
		$standCustFld .= '<td height="21" style="padding:0px 3px 0px 3px;"><a href="index.php?module=Users&action=RoleDetailView&roleid='.$role_id.'">'.$role_name.'</a></td></tr>';
		
	}
	$standCustFld .='</table>';
	//echo $standCustFld;	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("ROLES", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");

?>
