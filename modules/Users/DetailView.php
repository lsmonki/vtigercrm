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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/DetailView.php,v 1.21 2005/04/19 14:44:02 ray Exp $
 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('modules/Users/User.php');
require_once('include/utils/utils.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/database/PearDatabase.php');

global $current_user;
global $theme;
global $default_language;
global $adb;

global $app_strings;
global $mod_strings;

$focus = new User();

if(!empty($_REQUEST['record'])) {
        $focus->retrieve($_REQUEST['record']);
}
else
{
 //       header("Location: index.php?module=Users&action=ListView");

    echo "
        <script type='text/javascript'>
            window.location = 'index.php?module=Users&action=ListView';
        </script>
        ";
}

if( $focus->user_name == "" )
{  
   
    echo "
            <table>
                <tr>
                    <td>
                        <b>User does not exist.</b>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href='index.php?module=Users&action=ListView'>List Users</a>
                    </td>
                </tr>
            </table>
        ";
    exit;  
}


if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
}

if(isset($_REQUEST['reset_preferences'])){
	print_r($current_user->user_preferences);
	$current_user->resetPreferences();
}

global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$role = fetchUserRole($focus->id);
$rolename =  getRoleName($role);
$currencyid=fetchCurrency($focus->id);
$currency=getCurrencyName($currencyid);
//the user might belong to multiple groups
if($focus->id != 1)
{
 $groupids = fetchUserGroupids($focus->id);
}
$log->info("User detail view");

$xtpl=new XTemplate ('modules/Users/DetailView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$xtpl->assign("THEME", $theme);
$xtpl->assign("IMAGE_PATH", $image_path);$xtpl->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$xtpl->assign("ID", $focus->id);
$xtpl->assign("USER_NAME", $focus->user_name);
$xtpl->assign("FIRST_NAME", $focus->first_name);
$xtpl->assign("LAST_NAME", $focus->last_name);
$xtpl->assign("STATUS", $focus->status);
$xtpl->assign("YAHOO_ID", $focus->yahoo_id);
$xtpl->assign("DATE_FORMAT", $focus->date_format);
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $xtpl->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id)
		&& isset($default_user_name)
		&& $default_user_name == $focus->user_name
		&& isset($lock_default_user_name)
		&& $lock_default_user_name == true	) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>";
	$xtpl->assign('EDIT_BUTTON',$buttons);
}
elseif (is_admin($current_user) || $_REQUEST['record'] == $current_user->id) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>";
	$xtpl->assign('EDIT_BUTTON',$buttons);
	
 //global $AUTHCFG;
 	//if (strtoupper($AUTHCFG['authType']) == 'SQL') {
		$buttons = "<input title='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_KEY']."' class='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=Users&action=ChangePassword&form=DetailView\",\"test\",\"width=320,height=230,resizable=1,scrollbars=1\");' type='button' name='password' value='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_LABEL']."'>";
		$xtpl->assign('CHANGE_PW_BUTTON',$buttons);
	//}
	
	$buttons = "<input title='".$mod_strings['LBL_LOGIN_HISTORY_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_LOGIN_HISTORY_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='ShowHistory'; this.form.return_id.value='$focus->id'; this.form.action.value='ShowHistory'\" type='submit' name='LoginHistory' value=' ".$mod_strings['LBL_LOGIN_HISTORY_BUTTON_LABEL']." '>";	
	$xtpl->assign('LOGIN_HISTORY_BUTTON',$buttons);
	$buttons = "<input title='".$mod_strings['LBL_LIST_MAILSERVER_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_LIST_MAILSERVER_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='ListMailAccount'; this.form.return_id.value='$focus->id'; this.form.module.value='Settings' ;this.form.action.value='ListMailAccount'\" type='submit' name='ListMailServerAccount' value=' ".$mod_strings['LBL_LIST_MAILSERVER_BUTTON_LABEL']." '>";
	$xtpl->assign('LIST_MAILSERVER_BUTTON',$buttons);
	$buttons = "<input title='".$mod_strings['LBL_CHANGE_HOMEPAGE_TITLE']."' class='button' align='center' onclick=\"this.form.return_module.value='Users';  this.form.return_action.value='DetailView';  this.form.action.value='EditHomeOrder';  this.form.record.value='$focus->id';\"  type='submit' name='EditHomeOrder'  value='  ".$mod_strings['LBL_CHANGE_HOMEPAGE_LABEL']."  '>";
	$xtpl->assign('CHANGE_HOMEPAGE_BUTTON',$buttons);

	
}
/* Forum Display/Hide Button
if($_REQUEST['forumDisplay'] == "true" || $displayForums == "true"){
	$buttons .= "<input title='".$app_strings['LBL_FORUM_HIDE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_FORUM_HIDE_BUTTON_KEY']."' class='button' onclick=\"this.form.module.value='Users'; this.form.forumDisplay.value='false'; this.form.action.value='DetailView'\" type='submit' name='Display' value=' ".$app_strings['LBL_FORUM_HIDE_BUTTON_LABEL']." '></td>\n";
}
else
{
	$buttons .= "<input title='".$app_strings['LBL_FORUM_SHOW_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_FORUM_SHOW_BUTTON_KEY']."' class='button' onclick=\"this.form.module.value='Users'; this.form.forumDisplay.value='true'; this.form.action.value='DetailView'\" type='submit' name='Display' value=' ".$app_strings['LBL_FORUM_SHOW_BUTTON_LABEL']." '></td>\n";
}
*/
if (is_admin($current_user)) 
{
	$buttons = "<input title='".$app_strings['LBL_DUPLICATE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DUPLICATE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value=true; this.form.return_id.value='".$_REQUEST['record']."';this.form.action.value='EditView'\" type='submit' name='Duplicate' value=' ".$app_strings['LBL_DUPLICATE_BUTTON_LABEL']."'   >";
	$xtpl->assign('DUPLICATE_BUTTON',$buttons);

	//done so that only the admin user can see the customize tab button
	if($_REQUEST['record'] == $current_user->id)
	{
		$buttons = "<input title='".$app_strings['LBL_TABCUSTOMISE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_TABCUSTOMISE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='TabCustomise'; this.form.action.value='TabCustomise'\" type='submit' name='Customise' value=' ".$app_strings['LBL_TABCUSTOMISE_BUTTON_LABEL']." '>";
		$xtpl->assign('TABCUSTOMIZE_BUTTON',$buttons);
	}
	if($_REQUEST['record'] != $current_user->id)
	{
		$buttons = "<input title='".$app_strings['LBL_DELETE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DELETE_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='UserDeleteStep1'\" type='submit' name='Delete' value='  ".$app_strings['LBL_DELETE_BUTTON_LABEL']."  '>";
		$xtpl->assign('DELETE_BUTTON',$buttons);
	}

        //$buttons .= "<input title='".$app_strings['LBL_ROLES_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_ROLES_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='TabCustomise'; this.form.action.value='ListPermissions'\" type='submit' name='ListPermissions' value=' ".$app_strings['LBL_ROLES_BUTTON_LABEL']." '></td>\n";
	if($_SESSION['authenticated_user_roleid'] == 'administrator')
	{
		$buttons = "<input title='".$app_strings['LBL_LISTROLES_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_LISTROLES_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='TabCustomise'; this.form.action.value='listroles'; this.form.record.value= '". $current_user->id ."'\" type='submit' name='ListRoles' value=' ".$app_strings['LBL_LISTROLES_BUTTON_LABEL']." '>";
		$xtpl->assign('LISTROLES_BUTTON',$buttons);
	}

}

$xtpl->parse("main");
$xtpl->out("main");

if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id) && $focus->is_admin == 'on') {
	$xtpl->assign("IS_ADMIN", "checked");
	$xtpl->parse("user_settings");
	$xtpl->out("user_settings");
}

$xtpl->assign("DESCRIPTION", nl2br($focus->description));
if(is_admin($current_user))
{
	$xtpl->assign("ROLEASSIGNED","<a href=index.php?module=Users&action=RoleDetailView&roleid=".$role .">" .$rolename ."</a>");
	$xtpl->assign("CURRENCY_NAME",$currency);
}


if(is_admin($current_user))
{
	$query ="select groupname from groups where groupid in (".fetchUserGroupids($current_user->id).")";
	$result = $adb->query($query);
	$num_rows = $adb->num_rows($result);
	for($i=0;$i < $num_rows;$i++)
	{
		$groupname = $adb->query_result($result,$i,'groupname');
		$grouplists[$i] ="<a href='index.php?module=Users&action=UserInfoUtil&groupname=".$groupname."'>".$groupname."</a>";
	}
	if($grouplists != '')
	{	
		$group_lists = implode(",",$grouplists);
	}
	$xtpl->assign("GROUPASSIGNED",$group_lists);
}
else
{
	$xtpl->assign("GROUPASSIGNED",$group);
}
$xtpl->assign("COLORASSIGNED", "<div style='background-color:".$focus->cal_color.";'>".$focus->cal_color."</div>");


$xtpl->assign("ACTIVITY_VIEW", $focus->activity_view);
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
$xtpl->assign("ADDRESS_STREET", $focus->address_street);
$xtpl->assign("ADDRESS_CITY", $focus->address_city);
$xtpl->assign("ADDRESS_STATE", $focus->address_state);
$xtpl->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$xtpl->assign("ADDRESS_COUNTRY", $focus->address_country);
$xtpl->assign("SIGNATURE", nl2br($focus->signature));
$xtpl->parse("user_info");
$xtpl->out("user_info");


echo "</td></tr>\n";

?>
