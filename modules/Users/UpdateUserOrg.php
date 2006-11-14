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

require_once('include/utils/utils.php');
require_once('user_privileges/default_module_view.php');
require_once('Smarty_setup.php');
global $app_strings;
global $mod_strings;
global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $current_language;
global $current_user;

$smarty = new vtigerCRM_Smarty;

$smarty->assign("UMOD", $mod_strings);
$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);

global $log;
$log->debug("Inside UpdateUserOrg");

//Assign the organization details to the html output
$smarty->assign("MULTISELECT_COMBO_BOX_ITEM_SEPARATOR_STRING", "<br>&nbsp;");
$smarty->assign("MODULE", 'Users');
$smarty->assign("CURRENT_USERID", $current_user->id);
if(is_admin($current_user))
    $smarty->assign("IS_ADMIN", true);
else
    $smarty->assign("IS_ADMIN", false);

if( isset($_REQUEST['recordid']) && $_REQUEST['recordid'] != '')
    $smarty->assign("ID", $_REQUEST['recordid']);

if( isset($_SESSION['all_user_organizations']) && $_SESSION['all_user_organizations'] != '')
    $smarty->assign("ALL_USER_ORGANIZATIONS", $_SESSION['all_user_organizations']);

if( isset($_SESSION['edit_user_organizations']) && $_SESSION['edit_user_organizations'] != '')
    $smarty->assign("EDIT_USER_ORGANIZATIONS", $_SESSION['edit_user_organizations']);

if( isset($_SESSION['edit_user_orgunits']) && $_SESSION['edit_user_orgunits'] != '')
    $smarty->assign("EDIT_USER_ORGUNITS", $_SESSION['edit_user_orgunits']);

if( isset($_SESSION['edit_user_primary_organization']) && $_SESSION['edit_user_primary_organization'] != '')
    $smarty->assign("EDIT_USER_PRIMARY_ORGANIZATION", $_SESSION['edit_user_primary_organization']);

if( isset($_SESSION['edit_user_assigned_organization']) && $_SESSION['edit_user_assigned_organization'] != '')
    $smarty->assign("EDIT_USER_ASSIGNED_ORGANIZATIONS", $_SESSION['edit_user_assigned_organization']);

if( isset($_SESSION['edit_user_primary_orgunits']) && $_SESSION['edit_user_primary_orgunits'] != '')
    $smarty->assign("EDIT_USER_PRIMARY_ORGUNITS", $_SESSION['edit_user_primary_orgunits']);

//redisplay the organization part
$smarty->display('UserDetailOrg.tpl');

?>

