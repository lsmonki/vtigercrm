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

echo get_module_title("Users", $_REQUEST['fld_module'].': Profiles', true);
echo '<BR>';
//echo get_form_header("Standard Fields", "", false );

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$fld_module = $_REQUEST["fld_module"];
//Retreiving the fields array

$xtpl=new XTemplate ('modules/Users/ListFldProfiles.html');

$sql = "select * from profile";
$profileListResult = $adb->query($sql);
$noofrows = $adb->num_rows($profileListResult);

$standCustFld = getStdOutput($profileListResult, $noofrows, $mod_strings, $fld_module);

//Standard PickList Fields
function getStdOutput($profileListResult, $noofrows, $mod_strings, $fld_module)
{
	global $adb;
	echo get_form_header("Profiles", "", false );
	$standCustFld= '';
	$standCustFld .= '<table border="0" cellpadding="0" cellspacing="0" class="FormBorder" width="80%">';
	$standCustFld .=  '<tr class="ModuleListTitle" height=20>';
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
		$profile_name = $adb->query_result($profileListResult,$i,"profilename");
		$profile_id = $adb->query_result($profileListResult,$i,"profileid");
		
		$standCustFld .= '<td width="34%" height="21"><p style="margin-left: 10;"><a href="index.php?module=Users&action=ListFieldPermissions&fld_module='.$fld_module.'&profileid='.$profile_id.'">'.$profile_name.'</a></td></tr>';
		
	}
	$standCustFld .='</table>';
	//echo $standCustFld;	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("FIELDPROFILES", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");

?>
