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
 * $Header:  vtiger_crm/sugarcrm/modules/Users/Login.php,v 1.3 2004/09/15 12:06:00 jack Exp $
 * Description: TODO:  To be written.
 ********************************************************************************/
$theme_path="themes/".$theme."/";
require_once($theme_path.'layout_utils.php');

global $app_language;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

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
	else 
	{
		$login_user_name = $default_user_name;
	}
	$_session['login_user_name'] = $login_user_name;
}

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

<table width="50%" cellpadding="0" cellspacing="0" border="0" align="center"><tbody><tr><td>
<P>&nbsp;</P>
<?php echo $app_strings['NTC_LOGIN_MESSAGE']; ?>
<table cellpadding="2" cellspacing="0" border="0">
<form action="index.php" method="post" name="DetailView" id="form">
<tr>
	<td style="padding-top:10;">
		<table cellpadding="0" cellspacing="5" border="0">
			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="action" value="Authenticate">
			<input type="hidden" name="return_module" value="Users">
			<input type="hidden" name="return_action" value="Login">
<?php 
if(isset($login_error) && $login_error != "")
{
?>
		<tr>	
			<td><?php echo $current_module_strings['LBL_ERROR'] ?></td>
			<td><font color="Red"><?php echo $login_error ?></font></td>
		</tr>
<?php
}
?>
		<tr>	
			<td><?php echo $current_module_strings['LBL_USER_NAME'] ?></td>
			<td><input type="text" name="user_name"  value="<?php echo $login_user_name ?>"></td>
		</tr>
		<tr>	
			<td><?php echo $current_module_strings['LBL_PASSWORD'] ?></td>
			<td><input type="password" name="user_password" value="<?php echo $login_password ?>"></td>
		</tr>	
		<tr>
			<td></td>
			<td><input title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accessKey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" class="button" type="submit" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  "></td>
		</tr>	
		</table>
	</td>
	</form>
</tr>
</table> 
</td></tr></tbody></table>
