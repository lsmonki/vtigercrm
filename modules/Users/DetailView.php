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

require_once('Smarty_setup.php');
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

$smarty = new vtigerCRM_Smarty;

$smarty->assign("UMOD", $mod_strings);
global $current_language;
$smod_strings = return_module_language($current_language, 'Settings');
$smarty->assign("MOD", $smod_strings);

$smarty->assign("APP", $app_strings);

$smarty->assign("THEME", $theme);
$smarty->assign("IMAGE_PATH", $image_path);$smarty->assign("PRINT_URL", "phprint.php?jt=".session_id().$GLOBALS['request_string']);
$smarty->assign("ID", $focus->id);
$smarty->assign("USER_NAME", $focus->user_name);
$smarty->assign("FIRST_NAME", $focus->first_name);
$smarty->assign("LAST_NAME", $focus->last_name);
$smarty->assign("STATUS", $focus->status);
$smarty->assign("YAHOO_ID", $focus->yahoo_id);
$smarty->assign("DATE_FORMAT", $focus->date_format);
if(isset($focus->imagename) && $focus->imagename!='')
{
	$imagestring="<div id='track1' style='margin: 4px 0pt 0pt 10px; width: 200px; background-image: url(themes/images/scaler_slider_track.gif); background-repeat: repeat-x; background-position: left center; height: 18px;'>
	<div class='selected' id='handle1' style='width: 18px; height: 18px; position: relative; left: 145px;cursor:pointer;'><img src='themes/images/scaler_slider.gif'></div>
	</div><script language='JavaScript' type='text/javascript' src='include/js/prototype.js'></script>
<script language='JavaScript' type='text/javascript' src='include/js/slider.js'></script>

	<div class='scale-image' style='padding: 10px; float: left; width: 83.415px;'><img src='test/user/".$focus->imagename."' width='100%'</div>
	<p><script type='text/javascript' src='include/js/scale_demo.js'></script></p>";
	$smarty->assign("USER_IMAGE",$imagestring);
}
				
if (isset($focus->yahoo_id) && $focus->yahoo_id !== "") $smarty->assign("YAHOO_MESSENGER", "<a href='http://edit.yahoo.com/config/send_webmesg?.target=".$focus->yahoo_id."'><img border=0 src='http://opi.yahoo.com/online?u=".$focus->yahoo_id."'&m=g&t=2'></a>");
if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id)
		&& isset($default_user_name)
		&& $default_user_name == $focus->user_name
		&& isset($lock_default_user_name)
		&& $lock_default_user_name == true	) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>";
	$smarty->assign('EDIT_BUTTON',$buttons);
}
elseif (is_admin($current_user) || $_REQUEST['record'] == $current_user->id) {
	$buttons = "<input title='".$app_strings['LBL_EDIT_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_EDIT_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='EditView'\" type='submit' name='Edit' value='  ".$app_strings['LBL_EDIT_BUTTON_LABEL']."  '>";
	$smarty->assign('EDIT_BUTTON',$buttons);
	
 //global $AUTHCFG;
 	//if (strtoupper($AUTHCFG['authType']) == 'SQL') {
		$buttons = "<input title='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_KEY']."' class='small' LANGUAGE=javascript onclick='return window.open(\"index.php?module=Users&action=ChangePassword&form=DetailView\",\"test\",\"width=320,height=230,resizable=1,scrollbars=1\");' type='button' name='password' value='".$mod_strings['LBL_CHANGE_PASSWORD_BUTTON_LABEL']."'>";
		$smarty->assign('CHANGE_PW_BUTTON',$buttons);
	//}
	
	$buttons = "<input title='".$mod_strings['LBL_LOGIN_HISTORY_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_LOGIN_HISTORY_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='ShowHistory'; this.form.return_id.value='$focus->id'; this.form.action.value='ShowHistory'\" type='submit' name='LoginHistory' value=' ".$mod_strings['LBL_LOGIN_HISTORY_BUTTON_LABEL']." '>";	
	$smarty->assign('LOGIN_HISTORY_BUTTON',$buttons);
	$buttons = "<input title='".$mod_strings['LBL_LIST_MAILSERVER_BUTTON_TITLE']."' accessKey='".$mod_strings['LBL_LIST_MAILSERVER_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='ListMailAccount'; this.form.return_id.value='$focus->id'; this.form.module.value='Settings' ;this.form.action.value='ListMailAccount'\" type='submit' name='ListMailServerAccount' value=' ".$mod_strings['LBL_LIST_MAILSERVER_BUTTON_LABEL']." '>";
	$smarty->assign('LIST_MAILSERVER_BUTTON',$buttons);
	$buttons = "<input title='".$mod_strings['LBL_CHANGE_HOMEPAGE_TITLE']."' class='small' align='center' onclick=\"this.form.return_module.value='Users';  this.form.return_action.value='DetailView';  this.form.action.value='EditHomeOrder';  this.form.record.value='$focus->id';\"  type='submit' name='EditHomeOrder'  value='  ".$mod_strings['LBL_CHANGE_HOMEPAGE_LABEL']."  '>";
	$smarty->assign('CHANGE_HOMEPAGE_BUTTON',$buttons);

	
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
	$buttons = "<input title='".$app_strings['LBL_DUPLICATE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DUPLICATE_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.isDuplicate.value=true; this.form.return_id.value='".$_REQUEST['record']."';this.form.action.value='EditView'\" type='submit' name='Duplicate' value=' ".$app_strings['LBL_DUPLICATE_BUTTON_LABEL']."'   >";
	$smarty->assign('DUPLICATE_BUTTON',$buttons);
	
	//done so that only the admin user can see the customize tab button
	if($_REQUEST['record'] == $current_user->id)
	{
		$buttons = "<input title='".$app_strings['LBL_TABCUSTOMISE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_TABCUSTOMISE_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='TabCustomise'; this.form.action.value='TabCustomise'\" type='submit' name='Customise' value=' ".$app_strings['LBL_TABCUSTOMISE_BUTTON_LABEL']." '>";
		$smarty->assign('TABCUSTOMIZE_BUTTON',$buttons);
	}
	if($_REQUEST['record'] != $current_user->id)
	{
		$buttons = "<input title='".$app_strings['LBL_DELETE_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_DELETE_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='DetailView'; this.form.return_id.value='$focus->id'; this.form.action.value='UserDeleteStep1'\" type='submit' name='Delete' value='  ".$app_strings['LBL_DELETE_BUTTON_LABEL']."  '>";
		$smarty->assign('DELETE_BUTTON',$buttons);
	}

        //$buttons .= "<input title='".$app_strings['LBL_ROLES_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_ROLES_BUTTON_KEY']."' class='button' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='TabCustomise'; this.form.action.value='ListPermissions'\" type='submit' name='ListPermissions' value=' ".$app_strings['LBL_ROLES_BUTTON_LABEL']." '></td>\n";
	if($_SESSION['authenticated_user_roleid'] == 'administrator')
	{
		$buttons = "<input title='".$app_strings['LBL_LISTROLES_BUTTON_TITLE']."' accessKey='".$app_strings['LBL_LISTROLES_BUTTON_KEY']."' class='small' onclick=\"this.form.return_module.value='Users'; this.form.return_action.value='TabCustomise'; this.form.action.value='listroles'; this.form.record.value= '". $current_user->id ."'\" type='submit' name='ListRoles' value=' ".$app_strings['LBL_LISTROLES_BUTTON_LABEL']." '>";
		$smarty->assign('LISTROLES_BUTTON',$buttons);
	}

}


if ((is_admin($current_user) || $_REQUEST['record'] == $current_user->id) && $focus->is_admin == 'on') {
	$smarty->assign("IS_ADMIN", "checked");
}

$smarty->assign("DESCRIPTION", nl2br($focus->description));
if(is_admin($current_user))
{
	$smarty->assign("ROLEASSIGNED","<a href=index.php?module=Users&action=RoleDetailView&roleid=".$role .">" .$rolename ."</a>");
}
else
{
	$smarty->assign("ROLEASSIGNED",$rolename);
}

	$smarty->assign("CURRENCY_NAME",$currency);

//Getting the Group Lists
$query ="select groupid,groupname from groups where groupid in (".fetchUserGroupids($focus->id).")";
$result = $adb->query($query);
$num_rows = $adb->num_rows($result);



//Assigning the group lists
if(is_admin($current_user))
{
	for($i=0;$i < $num_rows;$i++)
	{
		$groupid = $adb->query_result($result,$i,'groupid');
		$groupname = $adb->query_result($result,$i,'groupname');
		$grouplists[$i] ="<a href='index.php?module=Users&action=GroupDetailView&groupId=".$groupid."'>".$groupname."</a>";
	}
	if($grouplists != '')
	{	
		$group_lists = implode(",",$grouplists);
	}	
	$smarty->assign("GROUPASSIGNED",$group_lists);
}
else
{
	for($i=0;$i < $num_rows;$i++)
	{
		$groupname = $adb->query_result($result,$i,'groupname');
		$grouplists[$i] =$groupname;
	}
	if($grouplists != '')
	{	
		$group_lists = implode(",",$grouplists);
	}
	$smarty->assign("GROUPASSIGNED",$group_lists);
}
$smarty->assign("COLORASSIGNED", "<div style='background-color:".$focus->cal_color.";'>".$focus->cal_color."</div>");


$smarty->assign("ACTIVITY_VIEW", $focus->activity_view);
$smarty->assign("TITLE", $focus->title);
$smarty->assign("DEPARTMENT", $focus->department);
$smarty->assign("REPORTS_TO_ID", $focus->reports_to_id);
$smarty->assign("REPORTS_TO_NAME", $focus->reports_to_name);
$smarty->assign("PHONE_HOME", $focus->phone_home);
$smarty->assign("PHONE_MOBILE", $focus->phone_mobile);
$smarty->assign("PHONE_WORK", $focus->phone_work);
$smarty->assign("PHONE_OTHER", $focus->phone_other);
$smarty->assign("PHONE_FAX", $focus->phone_fax);
$smarty->assign("EMAIL1", $focus->email1);
$smarty->assign("EMAIL2", $focus->email2);
$smarty->assign("ADDRESS_STREET", $focus->address_street);
$smarty->assign("ADDRESS_CITY", $focus->address_city);
$smarty->assign("ADDRESS_STATE", $focus->address_state);
$smarty->assign("ADDRESS_POSTALCODE", $focus->address_postalcode);
$smarty->assign("ADDRESS_COUNTRY", $focus->address_country);
$smarty->assign("SIGNATURE", nl2br($focus->signature));
$smarty->assign("MODULE", 'Settings');

$smarty->display("UserDetailView.tpl");

echo "</td></tr>\n";

?>
