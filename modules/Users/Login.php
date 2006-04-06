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
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Users/Login.php,v 1.6 2005/01/08 13:15:03 jack Exp $
 * Description: TODO:  To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
$theme_path="themes/".$theme."/";
$image_path="include/images/";
require_once($theme_path.'layout_utils.php');

global $app_language;
//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;
$current_module_strings = return_module_language($current_language, 'Users');

 define("IN_LOGIN", true);

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
	elseif (isset($_REQUEST['ck_login_id_vtiger'])) {
		$login_user_name = get_assigned_user_name($_REQUEST['ck_login_id_vtiger']);
	}
	else
	{
		$login_user_name = $default_user_name;
	}
	$_session['login_user_name'] = $login_user_name;
}

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


<table border=0 cellspacing=0 cellpadding=0 width=100%>
<tr>
	<td align=center>
		<br><br>
		<br><br>
		
		<table border="0" cellspacing="0" cellpadding="0" width="720" class="loginTopBg">
		<tr>
			<td align=left><img src="include/images/loginVtigerCRM.gif" alt="vtiger CRM" title="vtiger CRM"></td>
			<td align=right><img src="include/images/loginAlpha.gif" alt="Alpha5" title="Alpha5"></td>
		</tr>
		</table>
		
		<table border=0 cellspacing=0 cellpadding=0 width=720 class="loginTopShade">
		<tr>
			<td><img src="include/images/loginTopShade.gif"></td>
		</tr>
		</table>
		
		<table border=0 cellspacing=0 cellpadding=0 width=720 class="loginBg" >
		<tr>
			<td valign=top width=60% height="250" class="loginBillBoard">
				<div align=left style="padding:20px">
				<span style="font-size:20px;color:white">Welcome to vtiger CRM 5 - Alpha5</span><br><br>
				
				vtiger CRM 5, is now updated with business looks and is more user-friendly and sets the standard for all open-source commercial grade business applications. <br><br>
				
				<b>New in Alpha 5</b> <br>
				<li>New UI with improved usability</li>
		                <li>Exposing API docs</li>
                		<li>Security Document</li>
		                <li>Performance Optimizations</li>
			        <li>ImageGraph Usage instead of
				Jpgraph with PHP5 for Dashboards</li>	 		
		                <li>Migration from 4.2.3 to Alpha5</li>	 		
				
				</div>
			
			</td>
			<td valign=top>
			<br>
			<!-- sign in -->
				<table border=0 cellspacing=0 cellpadding=0 width=90% bgcolor=white>
				<tr>
					<td>
						<table border=0 cellspacing=0 cellpadding=0 width=100%><tr><td align=left><img src="include/images/loginSITopLeft.gif"></td><td align=right><img src="include/images/loginSITopRight.gif"></td></tr></table>
					

						<table border=0 cellspacing=0 cellpadding=5 align=center width=90%>
<form action="index.php" method="post" name="DetailView" id="form">

			<input type="hidden" name="module" value="Users">
			<input type="hidden" name="action" value="Authenticate">
			<input type="hidden" name="return_module" value="Users">
			<input type="hidden" name="return_action" value="Login">
<?php
if( isset($_SESSION['validation'])){
?>
		<tr>
		<td><font color="Red"> <?php echo $current_module_strings['VLD_ERROR']; ?> </font></td>
		</tr>
<?php
}
else if(isset($login_error) && $login_error != "")
{
?>
		<tr>
		<td><b><font color="Brown">
		</tr>
		 <?php echo $login_error ?>
                 </font></b></td>
                 </tr>
<?php
}

if (isset($_REQUEST['ck_login_language_vtiger'])) {
	$display_language = $_REQUEST['ck_login_language_vtiger'];
}
else {
	$display_language = $default_language;
}

if (isset($_REQUEST['ck_login_theme_vtiger'])) {
	$display_theme = $_REQUEST['ck_login_theme_vtiger'];
}
else {
	$display_theme = $default_theme;
}

?>
	<tr>
	<td colspan="2" class="loginSignin" style="border-bottom: 1px solid rgb(153, 153, 153); padding: 10px;" align="left">
		Sign in
	</td>
	</tr>
	<tr>
	<td class=small align=right>
	<?php echo $current_module_strings['LBL_USER_NAME'] ?>
		</td>
		<td class=small>
		<input class=textbox type="text"  name="user_name"  class=textbox value="<?php echo $login_user_name ?>">
		</td>
	</tr>
	<tr>
	<td class=small align=right>
	<?php echo $current_module_strings['LBL_PASSWORD'] ?>
	</b></td><td class=small>
	<input class=textbox type="password" size='20' name="user_password" class=textbox value="<?php echo $login_password ?>">
	</td>
	</tr>
	<tr>
	<td class=small align=right style="background-color:#f5f5f5">
	<?php echo $current_module_strings['LBL_THEME'] ?>
	</b></td><td class=small style="background-color:#f5f5f5">
		<select class='small' name='login_theme' style="width:120px;">
		<?php echo get_select_options_with_id(get_themes(), $display_theme) ?>
		</select>
	</td>
	</tr>
	<tr>
	<td class=small align=right style="background-color:#f5f5f5">
	<?php echo $current_module_strings['LBL_LANGUAGE'] ?>
	</b>
	</td>
	<td class=small style="background-color:#f5f5f5">
	
	<select class='small' name='login_language' style="width:120px;">
	<?php echo get_select_options_with_id(get_languages(), $display_language) ?>
	</select>
	</td>
	</tr>
	<tr>
	<td></td>
	<td>
	<input class=small title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" class="button" type="image" src="include/images/loginBtnSignin.gif" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  " style="width:100; height:25;">
	</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>
	</tr>
	</table>
		
					<table border=0 cellspacing=0 cellpadding=0 width=100%><tr><td align=left><img src="include/images/loginSIBottomLeft.gif"></td><td align=right><img src="include/images/loginSIBottomRight.gif"></td><tr></table>
	</td>
	</form>
</tr>
</table>
				
            </td>
          </tr>
          </table>
<table border=0 cellspacing=0 cellpadding=0 width=720 class="loginBottomBg">
		<tr>
			<td align=left><img src="include/images/loginBottomBg.gif"></td>
		</tr>
		</table>
	  
<table border=0 cellspacing=0 cellpadding=0 width=720 >
		<tr>
			<td align=left><img src="include/images/loginBottomURL.gif" alt="vtiger CRM" title="vtiger CRM"></td>
		</tr>
		</table>

	</td>
</table></td>
</tr>
</table>

