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
 * $Header:  vtiger_crm/sugarcrm/modules/Users/EditView.php,v 1.8 2004/12/21 20:01:38 jack Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
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
if (isset($_REQUEST['return_module']))
{
        $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
        $RETURN_MODULE=$_REQUEST['return_module'];
}
if (isset($_REQUEST['return_action']))
{
        $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
        $RETURN_ACTION = $_REQUEST['return_action'];
}
if (isset($_REQUEST['return_id']))
{
        $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
        $RETURN_ID = $_REQUEST['return_id'];
}

#$xtpl->assign("JAVASCRIPT", get_set_focus_js().get_validate_record_js());
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
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
	$status .= get_select_options_with_id($app_list_strings['user_status_dom'], $focus->status);
	$status .= "</select></td>\n";
	$xtpl->assign("USER_STATUS_OPTIONS", $status);


        
        $ROLE_SELECT_OPTION = '<select name="user_role">';
               $sql = "select name from role";
                  $result = mysql_query($sql);
                  $temprow = mysql_fetch_array($result);
                   do
                   {
                    $rolename=$temprow["name"];
                    $ROLE_SELECT_OPTION .= '<option value=';
                    $ROLE_SELECT_OPTION .=  $rolename;
                    $ROLE_SELECT_OPTION .=  '>';
                    $ROLE_SELECT_OPTION .= $temprow["name"];
                    $ROLE_SELECT_OPTION .= '</option>';
                   }while($temprow = mysql_fetch_array($result));
                                  
                   $ROLE_SELECT_OPTION .= ' </select>';
                   
                   $xtpl->assign("USER_ROLE", $ROLE_SELECT_OPTION);



                   
        $GROUP_SELECT_OPTION = '<select name="group_name">';
               $sql = "select name from groups";
                  $result = mysql_query($sql);
                  $temprow = mysql_fetch_array($result);
                   do
                   {
                    $groupname=$temprow["name"];
                    $GROUP_SELECT_OPTION .= '<option value=';
                    $GROUP_SELECT_OPTION .=  $groupname;
                    $GROUP_SELECT_OPTION .=  '>';
                    $GROUP_SELECT_OPTION .= $temprow["name"];
                    $GROUP_SELECT_OPTION .= '</option>';
                   }while($temprow = mysql_fetch_array($result));
                                  
                   $GROUP_SELECT_OPTION .= ' </select>';
                   
                   $xtpl->assign("GROUP_NAME", $GROUP_SELECT_OPTION);

}

if (isset($default_user_name)
	&& $default_user_name != ""
	&& $default_user_name == $focus->user_name
	&& isset($lock_default_user_name)
	&& $lock_default_user_name == true ) {
	$status .= " disabled ";
	$xtpl->assign("DISABLED", "disabled");
}

if ($_REQUEST['Edit'] == ' Edit ')
$xtpl->assign("READONLY", "readonly");

if (is_admin($current_user) && $focus->is_admin == 'on') $xtpl->assign("IS_ADMIN", "checked");
elseif (is_admin($current_user) && $focus->is_admin != 'on') ;
elseif (!is_admin($current_user) && $focus->is_admin == 'on') $xtpl->assign("IS_ADMIN", "disabled checked");
else $xtpl->assign("IS_ADMIN", "disabled");

$xtpl->parse("main");
$xtpl->out("main");

echo "<br>";
if(is_admin($current_user) && ! isset($focus->id))
{
        include ('modules/Calendar/user_new.php');
}
else
{
        #require_once('modules/Calendar/Authenticate.php');
        #$a = $current_user->uid;
        #echo $a;
        #include ('modules/Calendar/user_new.php?id=$a');

}
echo "<table width=\"100%\" cellpadding=\"2\" cellspacing=\"0\" border=\"0\"><tr>\n";
echo "    <td align=\"left\"></td>\n";
echo "      <td align=\"left\">\n";
echo "             <table cellpadding=\"0\" cellspacing=\"5\" border=\"0\">";
echo "            <tr>";
echo "                 <td><input title=\"$app_strings[LBL_SAVE_BUTTON_TITLE]\" tabindex=\'5\' accessKey=\"$app_strings[LBL_SAVE_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='Save'; return verify_data(EditView)\" type=\"submit\" name=\"button\" value=\"  $app_strings[LBL_SAVE_BUTTON_LABEL]  \" ></td>\n";
echo "              <td><input title=\"$app_strings[LBL_CANCEL_BUTTON_TITLE]\" tabindex='5' accessKey=\"$app_strings[LBL_CANCEL_BUTTON_KEY]\" class=\"button\" onclick=\"this.form.action.value='$RETURN_ACTION'; this.form.module.value='$RETURN_MODULE'; this.form.record.value='$RETURN_ID'\" type=\"submit\" name=\"button\" value=\"  $app_strings[LBL_CANCEL_BUTTON_LABEL]  \"></td>\n";
echo "          </tr></table>\n";
echo "     </td>\n";
echo "    <td align=\"left\"></td>\n";
echo " </tr></table>\n";

echo "</form>";
echo get_set_focus_js();
echo get_validate_record_js();

?>
