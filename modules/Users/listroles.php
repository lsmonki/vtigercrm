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

echo get_module_title("Users",' Roles', true);
echo '<BR>';
//echo get_form_header("Standard Fields", "", false );

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
	//echo get_form_header("Profiles", "", false );
	$standCustFld= '';
	$standCustFld .= '<table width="25%" cellpadding="2" cellspacing="0" border="0">';
        $standCustFld .= '<form action="index.php" method="post" name="new" id="form">';
        $standCustFld .= '<input type="hidden" name="module" value="Users">';
        $standCustFld .= '<input type="hidden" name="action" value="createrole">';
        $standCustFld .= '<tr><br>';
        $standCustFld .= '<td><input title="New" accessKey="C" class="button" type="submit" name="New" value="New Role"></td>';
        $standCustFld .= '</tr></form></table>';
        $standCustFld .= '<BR>'; 
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
	$standCustFld .=   '<td class="moduleListTitle" height="21"><p style="margin-left: 10"></td>';
	$standCustFld .=   '<td class="moduleListTitle" height="21"><p style="margin-left: 10">Profile Name</td>';
	$standCustFld .=  '</tr>';
	
	for($i=0; $i<$noofrows; $i++)
	{
		if ($i%2==0)
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
		$standCustFld .= '<td width="34%" height="21"><a href="index.php?module=Users&action=createrole&mode=edit&roleid='.$role_id.'">edit</a> | <a href="#">del</a></td>';	
		$standCustFld .= '<td width="34%" height="21"><p style="margin-left: 10;"><a href="index.php?module=Users&action=RoleDetailView&roleid='.$role_id.'">'.$role_name.'</a></td></tr>';
		
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
