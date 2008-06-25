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

$profilename=$_REQUEST['profile_name'];

if(isset($_REQUEST['dup_check']) && $_REQUEST['dup_check']!='')
{
        $query = 'select profilename from vtiger_profile where profilename=?';
        $result = $adb->pquery($query, array($profilename));

        if($adb->num_rows($result) > 0)
        {
                echo 'A Profile in the specified name "'.$profilename.'" already exists';
                die;
        }else
        {
                echo 'SUCESS';
                die;
        }

}


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty = new vtigerCRM_Smarty;

if(isset($_REQUEST['parent_profile']) && $_REQUEST['parent_profile'] != '')
	$smarty->assign("PARENT_PROFILE",$_REQUEST['parent_profile']);
if(isset($_REQUEST['radio_button']) && $_REQUEST['radio_button'] != '')
	$smarty->assign("RADIO_BUTTON",$_REQUEST['radio_button']);
if(isset($_REQUEST['profile_name']) && $_REQUEST['profile_name'] != '')
	$smarty->assign("PROFILE_NAME",$_REQUEST['profile_name']);
if(isset($_REQUEST['profile_description']) && $_REQUEST['profile_description'] != '')
	$smarty->assign("PROFILE_DESCRIPTION",$_REQUEST['profile_description']);
if(isset($_REQUEST['mode']) && $_REQUEST['mode'] != '')
	$smarty->assign("MODE",$_REQUEST['mode']);

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("IMAGE_PATH",$image_path);
$smarty->assign("APP", $app_strings);
$smarty->assign("CMOD", $mod_strings);

$sql = "select * from vtiger_profile";
$result = $adb->pquery($sql, array());
$profilelist = array();
$temprow = $adb->fetch_array($result);
do
{
	$name=$temprow["profilename"];
	$profileid=$temprow["profileid"];
	$profilelist[] = array($name,$profileid); 
}while($temprow = $adb->fetch_array($result));
$smarty->assign("PROFILE_LISTS", $profilelist);
                    
$smarty->display("CreateProfile.tpl");
