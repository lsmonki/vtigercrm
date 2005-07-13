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


$filename=$_REQUEST['file_name'];
$filetype=$_REQUEST['file_type'];
$filesize=$_REQUEST['file_size'];
$logo_value=$_REQUEST['logo_value'];

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_SETTINGS'].' : '. $mod_strings['LBL_COMPANY_INFO'], true);
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Settings/EditCompanyDetails.html');
//$xtpl=new XTemplate ('modules/Settings/add2db');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

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
$organization_logoname = $adb->query_result($result,0,'logoname');


$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","index");

$xtpl->assign("FILE_NAME",$filename);
$xtpl->assign("FILE_TYPE",$filetype);
$xtpl->assign("FILE_SIZE",$filesize);
$xtpl->assign("LOGO_VALUE",$logo_value);

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
if (isset($organization_logoname))
	$xtpl->assign("ORGANIZATIONLOGONAME",$organization_logoname);


$xtpl->parse("main");
$xtpl->out("main");


?>
