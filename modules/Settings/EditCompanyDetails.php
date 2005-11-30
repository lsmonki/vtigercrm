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

$filetype=$_REQUEST['file_type'];
$filesize=$_REQUEST['file_size'];
$logo_value=$_REQUEST['logo_value'];

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_SETTINGS'].' : '. $mod_strings['LBL_COMPANY_INFO'], true);
echo '<br><br>';
//error handling
$flag = $_REQUEST['flag'];
if($flag ==1)echo "<font color='red'><B> Logo has to be an Image of type jpeg/png</B></font>"; 
	
if($flag == 3)echo "<B><font color='red'>Sorry, the uploaded file exceeds the maximum filesize limit. Please try a file smaller than 800000 bytes</font></B> <br>";

if($flag == 4 )echo "<b>Problems in file upload. Please try again! </b><br>";

if($flag == 2)echo "<font color='red'><B>Error Message<ul><li><font color='red'>Invalid file OR</font><li><font color='red'>File has no data</font></ul></B></font> <br>";

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
if ($organization_logoname == '') 
	$organization_logoname = $_REQUEST['prev_name'];
if (isset($organization_logoname)) 
	$xtpl->assign("ORGANIZATIONLOGONAME",$organization_logoname);


$xtpl->parse("main");
$xtpl->out("main");


?>
