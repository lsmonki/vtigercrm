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
 * $Header:  vtiger_crm/modules/Calls/EditView.php,v 1.1 2004/08/17 15:03:41 gjk Exp $
 * Description: TODO:  To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Calls/Call.php');
require_once('modules/Calls/Forms.php');

global $app_strings;
global $app_list_strings;
global $current_language;
global $current_user;

$focus = new Call();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
} 

//setting default date and time
if (is_null($focus->date_start)) $focus->date_start = date('Y-m-d');
if (is_null($focus->time_start)) $focus->time_start = date('H:i');
if (is_null($focus->duration_hours)) $focus->duration_hours = "0";
if (is_null($focus->duration_minutes)) $focus->duration_minutes = "15";

//needed when creating a new call with default values passed in 
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
	$focus->contact_name = $_REQUEST['contact_name'];
	if(get_magic_quotes_gpc() == 1)
	{
		$focus->contact_name = stripslashes($focus->contact_name);
	}
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
	$focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name'])) {
	$focus->parent_name = $_REQUEST['parent_name'];
	if(get_magic_quotes_gpc() == 1)
	{
		$focus->parent_name = stripslashes($focus->parent_name);
	}
}
if (isset($_REQUEST['parent_id'])) {
	$focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
	$focus->parent_type = $_REQUEST['parent_type'];
	if(get_magic_quotes_gpc() == 1)
	{
		$focus->parent_type = stripslashes($focus->parent_type);
	}
}
elseif (is_null($focus->parent_type)) {
	$focus->parent_type = $app_list_strings['record_type_default_key'];
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("Call detail view");

$xtpl=new XTemplate ('modules/Calls/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (!isset($focus->id)) $xtpl->assign("USER_ID", $current_user->id);
if (!isset($focus->id) && isset($_REQUEST['contact_id'])) $xtpl->assign("CONTACT_ID", $_REQUEST['contact_id']);	

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("PARENT_NAME", $focus->parent_name);	
$xtpl->assign("PARENT_RECORD_TYPE", $focus->parent_type);	
$xtpl->assign("PARENT_ID", $focus->parent_id);	
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name);
else $xtpl->assign("NAME", "");

$xtpl->assign("DATE_START", $focus->date_start);
$xtpl->assign("TIME_START", substr($focus->time_start,0,5));
$xtpl->assign("DESCRIPTION", $focus->description);

if ($focus->assigned_user_id == '' && (!isset($focus->id) || $focus->id=0)) $focus->assigned_user_id = $current_user->id; 
$xtpl->assign("ASSIGNED_USER_OPTIONS", get_select_options_with_id(get_user_array(), $focus->assigned_user_id));
$xtpl->assign("DURATION_HOURS", $focus->duration_hours);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));
$xtpl->assign("STATUS_OPTIONS", get_select_options_with_id($app_list_strings['call_status_dom'],$focus->status));
$xtpl->assign("DURATION_MINUTES_OPTIONS", get_select_options($focus->minutes_values,$focus->duration_minutes));
if (isset($focus->parent_type) && $focus->parent_type != "") {
	$change_parent_button = "<input title='".$app_strings['LBL_CHANGE_BUTTON_TITLE']."' tabindex='2' accessKey='".$app_strings['LBL_CHANGE_BUTTON_KEY']."' type='button' class='button' value='".$app_strings['LBL_CHANGE_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=".$app_list_strings['record_type_module'][$focus->parent_type]."&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";	
	$xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}

$xtpl->parse("main");

$xtpl->out("main");

?>