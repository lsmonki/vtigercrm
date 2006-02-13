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
echo get_module_title("Users",' Profiles', true);

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Users/ListProfiles.html');

$sql = "select * from profile";
$profileListResult = $adb->query($sql);
$noofrows = $adb->num_rows($profileListResult);

$standCustFld = getStdOutput($profileListResult, $noofrows, $mod_strings);

//Standard PickList Fields
function getStdOutput($profileListResult, $noofrows, $mod_strings)
{
	global $adb;
	//echo get_form_header("Profiles", "", false );
	$standCustFld= '';
	$standCustFld .= '<input type="hidden" name="module" value="Users">';
	$standCustFld .= '<input type="hidden" name="action" value="CreateProfile">';
	$standCustFld .= '<br><input title="New" accessKey="C" class="button" type="submit" name="New" value="New Profile">';
	$standCustFld .= '<br><BR>'; 
	$standCustFld .= '<table border="0" cellpadding="5" cellspacing="1" class="FormBorder" width="40%">';
	$standCustFld .=  '<tr height=20>';
	$standCustFld .=   '<td class="ModuleListTitle" height="20" style="padding:0px 3px 0px 3px;"><div align="center"><b>Operation</b></div></td>';
	$standCustFld .=   '<td class="ModuleListTitle" height="20" style="padding:0px 3px 0px 3px;"><b>Profile Name</b></td>';
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
		$profile_name = $adb->query_result($profileListResult,$i,"profilename");
		$profile_id = $adb->query_result($profileListResult,$i,"profileid");
		$standCustFld .= '<td width="18%" height="21" style="padding:0px 3px 0px 3px;"><div align="center"><a href="index.php?module=Users&action=ProfileEditView&profileid='.$profile_id.'">edit</a>';
		global $current_user;
                $current_profile = fetchUserProfileId($current_user->id);
                if($profile_id != 1 && $profile_id != 2 && $profile_id != 3 && $profile_id != 4 && $profile_id != $current_profile)
                {

			$standCustFld .= ' | <a href="index.php?module=Users&action=ProfileDeleteStep1&profileid='.$profile_id.'">del</a>';	
		}
		$standCustFld .= '</div></td>';
		$standCustFld .= '<td wheight="21" style="padding:0px 3px 0px 3px;"><a href="index.php?module=Users&action=ProfileDetailView&profileid='.$profile_id.'">'.$profile_name.'</a></td></tr>';
		
	}
	$standCustFld .='</table>';
	//echo $standCustFld;	
	return $standCustFld;
}
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("PROFILES", $standCustFld);


$xtpl->parse("main");
$xtpl->out("main");

?>
