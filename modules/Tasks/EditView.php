<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header:  vtiger_crm/modules/Tasks/EditView.php,v 1.1 2004/08/17 15:06:23 gjk Exp $
 * Description: TODO:  To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Tasks/Task.php');
require_once('modules/Tasks/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;
global $current_user;

$focus = new Task();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 


//setting default flag value so due date and time not required
if (!isset($focus->id)) $focus->date_due_flag = 'on';

//needed when creating a new case with default values passed in 
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name'])) {
	$focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
}
elseif (!isset($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Task detail view");

$xtpl=new XTemplate ('modules/Tasks/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);
$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);	
$xtpl->assign("PARENT_ID", $focus->parent_id);	
$xtpl->assign("CONTACT_NAME", $focus->contact_name);	
$xtpl->assign("CONTACT_PHONE", $focus->contact_phone);	
$xtpl->assign("CONTACT_EMAIL", $focus->contact_email);	
$xtpl->assign("CONTACT_ID", $focus->contact_id);	
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

if (isset($focus->parent_type) && $focus->parent_type != "") {
	$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' tabindex='1' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=".$app_list_strings['record_type_module'][$focus->parent_type]."&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";	
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
if ($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));

if ($focus->date_due_flag == 'on') {
	$xtpl->assign("DATE_DUE_NONE", "checked");
	$xtpl->assign("READONLY", "readonly");
}
	
$xtpl->assign("STATUS", $focus->status);
if ($focus->date_due == '0000-00-00') $xtpl->assign("DATE_DUE", ''); 
else $xtpl->assign("DATE_DUE", $focus->date_due);
if ($focus->time_due == '00:00:00') $xtpl->assign("TIME_DUE", ''); 
else $xtpl->assign("TIME_DUE", substr($focus->time_due,0,5));
$xtpl->assign("DESCRIPTION", $focus->description);

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id; 
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(), $focus->assigned_user_id));
$xtpl->assign("PRIORITY_OPTIONS", get_select_options_with_id($app_list_strings['task_priority_dom'], $focus->priority));
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));

$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['task_status_dom'], $focus->status));

$xtpl->parse("main");

$xtpl->out("main");

?>