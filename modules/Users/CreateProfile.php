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
require_once('include/utils/utils.php');

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty = new vtigerCRM_Smarty;

if(isset($_REQUEST['mode']) && $_REQUEST['mode'] != '')
	$smarty->assign("MODE",$_REQUEST['mode']);
if(isset($_REQUEST['profileid']) && $_REQUEST['profileid'] != '')
{
	global $adb;	
	$sql = "select * from profile where profileid=".$_REQUEST['profileid'];
	$profileResult = $adb->query($sql);
	$profile_name = $adb->query_result($profileResult,0,"profilename");
	$profile_description = $adb->query_result($profileResult,0,"description");
	$smarty->assign("PROFILE_NAME",$profile_name);
	$smarty->assign("PROFILE_DESCRIPTION",$profile_description);
}

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);
                    
$smarty->display("CreateProfile.tpl");
