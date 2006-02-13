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
global $mod_strings;
global $app_strings;
global $app_list_strings;


echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_SETTINGS'].' : '. $mod_strings['LBL_COMPANY_INFO'], true);
echo '<br><br>';
global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate('modules/Settings/OrganizationConfig.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);


	 $xtpl->assign("EDITCOMPANYDETAILS","<td align=center><input title=\"$app_strings[LBL_EDIT_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_EDIT_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='EditCompanyDetails'\" type=\"submit\" name=\"Edit\" value=\"$app_strings[LBL_EDIT_BUTTON_LABEL]\">");
	 
	 $xtpl->assign("CANCELCOMPANYDETAILS","<input title=\"$app_strings[LBL_CANCEL_BUTTON_TITLE]\" accessKey=\"$app_strings[LBL_CANCEL_BUTTON_KEY]\" class=\"button\"; onclick=\"this.form.action.value='index'\" type=\"submit\" name=\"Cancel\" value=\"$app_strings[LBL_CANCEL_BUTTON_LABEL]\"></td>");

$sql="select * from organizationdetails";
$result = $adb->query($sql);
$organization_name = $adb->query_result($result,0,'organizationame');
$organization_address= $adb->query_result($result,0,'address');
$organization_city = $adb->query_result($result,0,'city');
$organization_state = $adb->query_result($result,0,'state');
$organization_code = $adb->query_result($result,0,'code');
$organization_country = $adb->query_result($result,0,'country');
$organization_phone = $adb->query_result($result,0,'phone');
$organization_fax = $adb->query_result($result,0,'fax');
$organization_website = $adb->query_result($result,0,'website');
$organization_logo = $adb->query_result($result,0,'logo');
$organization_logoname = $adb->query_result($result,0,'logoname');

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","index");


if (isset($organization_name))
	$xtpl->assign("ORGANIZATIONNAME",$organization_name);
if (isset($organization_address))
	$xtpl->assign("ORGANIZATIONADDRESS",$organization_address);
if (isset($organization_city))
	$xtpl->assign("ORGANIZATIONCITY",$organization_city);
if (isset($organization_state))
	$xtpl->assign("ORGANIZATIONSTATE",$organization_state);
if (isset($organization_code))
	$xtpl->assign("ORGANIZATIONCODE",$organization_code);
if (isset($organization_country))
        $xtpl->assign("ORGANIZATIONCOUNTRY",$organization_country);
if (isset($organization_phone))
	$xtpl->assign("ORGANIZATIONPHONE",$organization_phone);
if (isset($organization_fax))
	$xtpl->assign("ORGANIZATIONFAX",$organization_fax);
if (isset($organization_website))
	$xtpl->assign("ORGANIZATIONWEBSITE",$organization_website);
if (isset($organization_logo))
	$xtpl->assign("ORGANIZATIONLOGO",$organization_logo);

$path = "test/logo";
$dir_handle = @opendir($path) or die("Unable to open directory $path");

while ($file = readdir($dir_handle))
{
        $filetyp =str_replace(".",'',strtolower(substr($file, -4)));
   if($organization_logoname==$file)
   {    
        if ($filetyp == 'jpeg' OR $filetyp == 'jpg' OR $filetyp == 'png')
        {
		if($file!="." && $file!="..")
		{
			
 		     $organization_logopath= $path;
		     $logo_name=$file;
		}
			
        }
   }	
}


if (isset($organization_logopath))
	$xtpl->assign("ORGANIZATIONLOGOPATH",$path);
if (isset($organization_logoname))
	$xtpl->assign("ORGANIZATIONLOGONAME",$logo_name);
closedir($dir_handle);

$xtpl->parse("main");
$xtpl->out("main");

?>
