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
require_once('data/Tracker.php');
require_once('include/utils.php');
require_once('database/DatabaseConnection.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;

if(isset($_REQUEST['record'])) {
    $id = $_REQUEST['record'];
}

//Retreive lead details from database
$sql = "SELECT first_name, last_name, company, assigned_user_id from leads where id ='$id'";
$result = mysql_query($sql);
$row = mysql_fetch_array($result);

$firstname = $row["first_name"];
$lastname = $row["last_name"];
$company = $row["company"];
$potentialname = $row["company"] ."-";
$userid = $row["assigned_user_id"];

//Retreiving the current user id
global $current_user;
$modified_user_id = $current_user->id;


global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Convert Lead view");

$xtpl=new XTemplate ('modules/Leads/ConvertLead.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("FIRST_NAME",$firstname);
$xtpl->assign("LAST_NAME",$lastname);
$xtpl->assign("ID", $id);
$xtpl->assign("CURRENT_USER_ID", $modified_user_id);
$xtpl->assign("RETURN_ACTION","DetailView");
$xtpl->assign("RETURN_MODULE","Leads");
$xtpl->assign("RETURN_ID",$id);
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(), $userid));
$xtpl->assign("ACCOUNT_NAME",$company);
$xtpl->assign("CREATE_POTENTIAL","yes");
$xtpl->assign("POTENTIAL_NAME", $potentialname);

$xtpl->parse("main");
$xtpl->out("main");
?>
