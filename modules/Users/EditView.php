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
 * $Header:  vtiger_crm/modules/Users/EditView.php,v 1.1 2004/08/17 15:06:40 gjk Exp $
 * Description:  TODO: To be written.
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('modules/Users/Forms.php');

global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus = new User();

if(isset($_REQUEST['record']) && isset($_REQUEST['record'])) {
	if (!is_admin($current_user) && $_REQUEST['record'] != $current_user->id) die ("Unauthorized access to user administration.");
    $focus->retrieve($_REQUEST['record']);
}
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->user_name = "";
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$log->info("User edit view");
$xtpl=new XTemplate ('modules/Users/EditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

if (isset($_REQUEST['error_string'])) $xtpl->assign("ERROR_STRING", "<font class='error'>Error: ".$_REQUEST['error_string']."</font>");
if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id());
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("TITLE", $focus->title);
$xtpl->assign("DEPARTMENT", $focus->department);
$xtpl->assign("REPORTS_TO_ID", $focus->reports_to_id);
$xtpl->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$xtpl->assign("PHONE_HOME", $focus->phone_home);
$xtpl->assign("PHONE_MOBILE", $focus->phone_mobile);
$xtpl->assign("PHONE_WORK", $focus->phone_work);
$xtpl->assign("PHONE_OTHER", $focus->phone_other);
$xtpl->assign("PHONE_FAX", $focus->phone_fax);
$xtpl->assign("EMAIL1", $focus->email1);
$xtpl->assign("EMAIL2", $focus->email2);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $xtpl->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);
$xtpl->assign("DESCRIPTION", $focus->description);

$xtpl->assign("THEME_OPTIONS", get_theme_options($focus->theme));
$all_languages = array_merge(array(''=>'--Default--'), get_languages());
$xtpl->assign("LANGUAGE_OPTIONS", get_select_options_with_id($all_languages, $focus->language));

if (is_admin($current_user)) {
	$status  = "<td width='20%' class='dataLabel'><FONT class='required'>".$app_strings['LBL_REQUIRED_SYMBOL']."</FONT>".$mod_strings['LBL_STATUS']."</td>\n";
	$status .= "<td width='30%'><select name='status' tabindex='1'";
	if (isset($default_user_name) 
		&& $default_user_name != "" 
		&& $default_user_name == $focus->user_name
		&& isset($lock_default_user_name) 
		&& $lock_default_user_name == true ) {
		$status .= " disabled ";
	}
	$status .= ">";
	$status .= get_select_options($app_list_strings['user_status_dom'], $focus->status);
	$status .= "</select></td>\n";
	$xtpl->assign("USER_STATUS_OPTIONS", $status);
}

if (isset($default_user_name) 
	&& $default_user_name != "" 
	&& $default_user_name == $focus->user_name
	&& isset($lock_default_user_name) 
	&& $lock_default_user_name == true ) {
	$status .= " disabled ";
	$xtpl->assign("DISABLED", "disabled");
}

if (is_admin($current_user) && $focus->is_admin == 'on') $xtpl->assign("IS_ADMIN", "checked");
elseif (is_admin($current_user) && $focus->is_admin != 'on') ;
elseif (!is_admin($current_user) && $focus->is_admin == 'on') $xtpl->assign("IS_ADMIN", "disabled checked");
else $xtpl->assign("IS_ADMIN", "disabled");

$xtpl->parse("main");
$xtpl->out("main");

?>