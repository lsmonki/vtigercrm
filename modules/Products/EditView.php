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
require_once('database/DatabaseConnection.php');
require_once('XTemplate/xtpl.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Products/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if(isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if(isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if(isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$result = get_group_options();
$nameArray = mysql_fetch_array($result);
$GROUP_SELECT_OPTION = '<select name="assigned_group_name">';
                   do
                   {
                    $groupname=$nameArray["name"];
                    $GROUP_SELECT_OPTION .= '<option value=';
                    $GROUP_SELECT_OPTION .=  $groupname;
                    $GROUP_SELECT_OPTION .=  '>';
                    $GROUP_SELECT_OPTION .= $nameArray["name"];
                    $GROUP_SELECT_OPTION .= '</option>';
                   }while($nameArray = mysql_fetch_array($result));
                   $GROUP_SELECT_OPTION .='<option value=none>none</option>';
                   $GROUP_SELECT_OPTION .= ' </select>';

$xtpl->assign("ASSIGNED_USER_GROUP_OPTIONS",$GROUP_SELECT_OPTION);

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id; 
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(TRUE, "Active", $focus->assigned_user_id), $focus->assigned_user_id));
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['case_status_dom'], $focus->status));

$xtpl->parse("main");

$xtpl->out("main");

?>
