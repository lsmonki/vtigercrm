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
 * $Header:  vtiger_crm/sugarcrm/modules/Users/Login.php,v 1.5 2004/11/02 10:22:19 jack Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$theme_path="themes/".$theme."/";
require_once($theme_path.'layout_utils.php');

global $app_language;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

 define("IN_LOGIN", true);

 define('IN_PHPBB', true);
 include($phpbb_root_path . 'extension.inc');
 include($phpbb_root_path . 'common.'.$phpEx);

 //
 // Set page ID for session management
 //
 $userdata = session_pagestart($user_ip, PAGE_LOGIN);
 init_userprefs($userdata);
 //
 // End session management
 //


// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_user_name"]))
{
	if (isset($_REQUEST['default_user_name']))
		$login_user_name = $_REQUEST['default_user_name'];
	else
		$login_user_name = $_SESSION['login_user_name'];
}
else
{
	if (isset($_REQUEST['default_user_name']))
	{
		$login_user_name = $_REQUEST['default_user_name'];
	}
	elseif (isset($_REQUEST['ck_login_id'])) {
		$login_user_name = get_assigned_user_name($_REQUEST['ck_login_id']);
	}
	else
	{
		$login_user_name = $default_user_name;
	}
	$_session['login_user_name'] = $login_user_name;
}
//echo base64_encode('Please replace the SugarCRM logos.');

$current_module_strings['VLD_ERROR'] = base64_decode('UGxlYXNlIHJlcGxhY2UgdGhlIFN1Z2FyQ1JNIGxvZ29zLg==');

// Retrieve username and password from the session if possible.
if(isset($_SESSION["login_password"]))
{
	$login_password = $_SESSION['login_password'];
}
else
{
	$login_password = $default_password;
	$_session['login_password'] = $login_password;
}

if(isset($_SESSION["login_error"]))
{
	$login_error = $_SESSION['login_error'];
}




echo get_module_title($current_module_strings['LBL_MODULE_NAME'], $current_module_strings['LBL_LOGIN'], true);
?>
<script type="text/javascript" language="JavaScript">
<!-- Begin
function set_focus() {
	if (document.DetailView.user_name.value != '') {
		document.DetailView.user_password.focus();
		document.DetailView.user_password.select();
	}
	else document.DetailView.user_name.focus();
}
//  End -->
</script>

<table cellpadding="0" align="center" width="100%" cellspacing="0" border="0">
<tbody><tr>
<td width="25%">&nbsp;</td>
<td>
<P>&nbsp;</P>
<table cellpadding="2" width="100%" cellspacing="0" border="0">
<form action="index.php" method="post" name="DetailView" id="form">
<tr>
<td width="5%">&nbsp;</td>
<td width="95%">
<?php echo $app_strings['NTC_LOGIN_MESSAGE']; ?>
		<table cellpadding="0" cellspacing="5" border="0">
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="action" value="Authenticate">
			<input type="hidden" name="return_module" value="Users">
			<input type="hidden" name="return_action" value="Login">
<?php
if( isset($_SESSION['validation'])){
?>
		<tr>
			<td><?php echo $current_module_strings['LBL_ERROR'];?></td>
			<td><font color="Red"><?php echo $current_module_strings['VLD_ERROR']; ?></font></td>
		</tr>
<?php
}
else if(isset($login_error) && $login_error != "")
{
?>
		<tr>
			<td><?php echo $current_module_strings['LBL_ERROR'] ?></td>
			<td><font color="Red"><?php echo $login_error ?></font></td>
		</tr>
<?php
}



if (isset($_REQUEST['ck_login_language'])) {
	$display_language = $_REQUEST['ck_login_language'];
}
else {
	$display_language = $default_language;
}

if (isset($_REQUEST['ck_login_theme'])) {
	$display_theme = $_REQUEST['ck_login_theme'];
}
else {
	$display_theme = $default_theme;
}

?>
		<tr>
			<td><?php echo $current_module_strings['LBL_USER_NAME'] ?></td>
			<td><input type="text" size='20' name="user_name"  value="<?php echo $login_user_name ?>"></td>
		</tr>
		<tr>
			<td><?php echo $current_module_strings['LBL_PASSWORD'] ?></td>
			<td><input type="password" size='20' name="user_password" value="<?php echo $login_password ?>"></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accessKey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" class="button" type="submit" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  "><br><br></td>
		</tr>
		<tr>
			<td><?php echo $current_module_strings['LBL_THEME'] ?></td>
			<td><select name='login_theme'><?php echo get_select_options_with_id(get_themes(), $display_theme) ?></select></td>
		</tr>
		<tr>
			<td><?php echo $current_module_strings['LBL_LANGUAGE'] ?></td>
			<td><select name='login_language'><?php echo get_select_options_with_id(get_languages(), $display_language) ?></select></td>
		</tr>
		<tr><td>&nbsp;</td></tr>
		</table>
	</td>
	</form>
</tr>
</table>
</td>
<td width="60%">&nbsp;</td>
</tr></tbody></table>
