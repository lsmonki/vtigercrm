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
require_once('include/database/PearDatabase.php');
require_once('HelpDeskUtil.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;


if(isset($_REQUEST['id']))    $id = $_REQUEST['id'];
else     $id = $_REQUEST['record'];

$idlist=$_REQUEST['idlist'];

//Retreiving the current user id
global $current_user;
$modified_user_id = $current_user->id;

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Change Ticket");

echo "<br>";

$tkList = '';
	$tkList .= '<table width="100%" cellpadding="2" cellspacing="0" border="0">';

        if (($i%2)==0)
                $tkList .= '<tr height=20 class=evenListRow>';
        else
                $tkList .= '<tr height=20 class=oddListRow>';

	$tkList .= '<td><input title="'.$app_strings['LBL_SAVE_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_SAVE_BUTTON_KEY'].'" class="button" onclick="this.form.action.value="ConvertToEntities";this.form.module.value="HelpDesk" type="submit" name="button" value="'.$app_strings['LBL_SAVE_BUTTON_LABEL'].'  " >';
	$tkList .= '<input title="'.$app_strings['LBL_CANCEL_BUTTON_TITLE'].'" accessKey="'.$app_strings['LBL_CANCEL_BUTTON_KEY'].'" class="button" onclick="this.form.action.value="index"; this.form.module.value="HelpDesk"; this.form.record.value="{RETURN_ID}" type="submit" name="button" value="'.$app_strings['LBL_CANCEL_BUTTON_LABEL'].' "></td></tr>';


$xtpl=new XTemplate ('modules/HelpDesk/Convert.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("ID", $id);
$xtpl->assign("CURRENT_USER_ID", $modified_user_id);
$xtpl->assign("RETURN_ACTION","DetailView");
$xtpl->assign("RETURN_MODULE","Leads");
$xtpl->assign("RETURN_ID",$id);
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['case_status_dom'], $focus->status));

if($_REQUEST['change_owner'])
	$xtpl->assign("TICKETCHANGE",'Ticket : Change Owner');
else
	$xtpl->assign("TICKETCHANGE",'Ticket : Change Status');

$change='';
if($_REQUEST['change_owner'])
{
	$change .= '<input type="hidden" name="change_owner" value="true"/><td width="20%" valign="top" class="dataLabel">'.$mod_strings['LBL_ASSIGNED_TO'].'<select tabindex="5" name="assigned_user_id" {ASSIGNED_USER_OPTIONS}</select> </td>';
	$xtpl->assign("ASSIGNED_USER_OPTIONS",getComboValues("user_name","users","user_name","1",'--None--'));
}
if($_REQUEST['change_status'])
{
	$change .= '<input type="hidden" name="change_status" value="true"/><td width="20%" valign="top" class="dataLabel">'.$mod_strings['LBL_STATUS'].'</td>';
	$xtpl->assign("STATUSOPTIONS",getComboValues("status","ticketstatus","status","1",'Open'));
}
$xtpl->assign("CHANGE",$change);
$xtpl->assign("IDLIST",$idlist);
$xtpl->parse("main");
$xtpl->out("main");
//header("index.php?module=HelpDesk&action=index");
?>
