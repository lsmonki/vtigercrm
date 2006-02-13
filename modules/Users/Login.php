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

 define('IN_PHPBB', true);
// include($phpbb_root_path . 'extension.inc');
// include($phpbb_root_path . 'common.'.$phpEx);

 //
 // Set page ID for session management
 //
 //$userdata = session_pagestart($user_ip, PAGE_LOGIN);
 //init_userprefs($userdata);
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

//echo get_module_title($current_module_strings['LBL_MODULE_NAME'], $current_module_strings['LBL_LOGIN'], true);
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
<table width="100%" height="88%" border="0">
<tr><td align="center" valign="middle">
<table cellpadding="0" align="center" width="100%" cellspacing="0" border="0">
<tbody><tr>

<td colspan="3">
<table cellpadding="0" width="75%" cellspacing="0" border="0" align="center"> <!-- style="border: 1px solid #5BBFFA" -->
<tr>
<form action="index.php" method="post" name="DetailView" id="form">
<td bgcolor="#C9EBFF" align="left" width="11" height="15"><?php echo "<img src='".$image_path."login_left.gif'>";?> </td>
<td bgcolor="#C9EBFF"> </td>
<td bgcolor="#C9EBFF" align="right"><?php echo "<img src='".$image_path."login_right.gif'>";?> </td></tr>
<tr>
<td bgcolor="#C9EBFF"> </td>
<td valign="top" bgcolor="#C9EBFF">

<?php echo "<img src='".$image_path."vtiger-crm.gif'>";?>
<?php //echo $app_strings['NTC_LOGIN_MESSAGE']; ?></td>
<td bgcolor="#C9EBFF"> </td>

</tr>
<tr>
<td bgcolor="#C9EBFF"> </td>
<td bgcolor="#C9EBFF">

<table width="100%" align="left" border="0" cellspacing="4" cellpadding="0" align="center">
<tr>
<td width="35%" valign ="top">
	<table width="100%" align="left" valign ="top" cellspacing="0" cellpadding="10" align="center">
        <tr>
        <td style="border-bottom: 1px dotted #000"><h4 style="margin: 0;pdiing:0;"><font face="Verdana, Arial, Helvetica, sans-serif">Key Modules</strong></font></h4></td></tr>
	<tr><td>:: Sales Force Automation</td></tr>
	<tr><td>:: Account & Contact Management</td></tr>
	<tr><td>:: Products Management</td></tr>
	<tr><td>:: Quotations</td></tr>
	<tr><td>:: Order Management</td></tr>
	<tr><td>:: Invoices</td></tr>
	<tr><td>:: Trouble Tickets</td></tr>
	<tr><td>:: Knowledge Base</td></tr>
	<tr><td>:: Reports & Dashboards</td></tr>
	<tr><td>:: <a href="http://www.vtigercrm.com/" target="_blank">More ...</td></tr>
        </table></td>
	<td bgcolor="#FFFFFF" width="1"><?php echo "<img src='".$image_path."spacer.gif'>";?></td>
<td width="35%" valign ="top">
<table width="100%" align="left" valign ="top" cellspacing="0" cellpadding="10" align="center">
<tr><td style="border-bottom: 1px dotted #000"><h4 style="margin: 0;pdiing:0;"><font face="Verdana, Arial, Helvetica, sans-serif"><strong>vtiger CRM Add-ons</strong></font></h4></td></tr>
<tr><td><font face="Verdana, Arial, Helvetica, sans-serif">:: <a href="http://www.vtiger.com/products/crm/customer-support.html" target="_blank">vtiger Customer Portal</font></td></tr>
<tr><td><font face="Verdana, Arial, Helvetica, sans-serif">:: <a href="http://www.vtiger.com/products/crm/microsoft-outlook-integration.html" target="_blank">vtiger Outlook Plug-in</font></td></tr>
<tr><td><font face="Verdana, Arial, Helvetica, sans-serif">:: <a href="http://www.vtiger.com/products/crm/microsoft-office-integration.html" target="_blank">vtiger Office Plug-in</font></td></tr>
<tr><td><font face="Verdana, Arial, Helvetica, sans-serif">:: <a href="http://www.vtiger.com/products/crm/thunderbird-integration.html" target="_blank">vtiger Thunderbird Extension</font></td></tr>
</table>
</td>
<td bgcolor="#FFFFFF" width="1"><?php echo "<img src='".$image_path."spacer.gif'>";?></td>
<td width="35%" valign ="top"><table align="center" valign ="top" cellpadding="10" cellspacing="0" border="0" width="100%">
			<tr><td style="border-bottom: 1px dotted #000"><h4 style="margin: 0;pdiing:0;"><font face="Verdana, Arial, Helvetica, sans-serif"><strong>User Log-in</strong></font></td></h4></tr>

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
<?php //echo "<img src='".$image_path."login.gif'>";?>

    </td>
</td>
	<td><b>
	<?php echo $current_module_strings['LBL_USER_NAME'] ?>
		</b><br>
		<input type="text" size='20' name="user_name"  value="<?php echo $login_user_name ?>">
		</td>
	</tr>
	<tr>
	<?php //echo $app_strings['NTC_WELCOME_MESSAGE']; ?>
	</td>
	<td><b>
	<?php echo $current_module_strings['LBL_PASSWORD'] ?>
	</b><br>
	<input type="password" size='20' name="user_password" value="<?php echo $login_password ?>">
	</td>
	</tr>
	<tr>
	<?php //echo $app_strings['NTC_DESCRIPTION']; ?>
	</td>
	<td><b>
	<?php echo $current_module_strings['LBL_THEME'] ?>
	</b><br>
		<select name='login_theme' style="width:100;">
		<?php echo get_select_options_with_id(get_themes(), $display_theme) ?>
		</select>
	</td>
	</tr>
	<tr>
	<td><b>
	<?php echo $current_module_strings['LBL_LANGUAGE'] ?>
	</b><br>
	<select name='login_language' style="width:100;">
	<?php echo get_select_options_with_id(get_languages(), $display_language) ?>
	</select>
	</td>
	</tr>
	<tr>
	<td align="right"><b>
	<input title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" class="button" type="submit" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  " style="width:100; height:25;">
	</b> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; </td>
	</tr>
	<tr><td>&nbsp</td></tr>
	</table>
	</td>
	</form>
	</td>
</table></td>
<td bgcolor="#C9EBFF"> </td>
</tr>
<tr>

<td align="left" valign="bottom" width="11" height="15"><?php echo "<img src='".$image_path."login_botleft.gif'>";?></td>
<td bgcolor="#C9EBFF"> </td>
<td bgcolor="#C9EBFF" align="right" valign="bottom"><?php echo "<img src='".$image_path."login_botright.gif'>";?></td>
</tr>

</table>
</tr>
<tr><td width="15%">&nbsp;</td>
<td align="center"><font face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;Best viewed in IE 5.0+, Netscape 7.0+,Opera 7.01+ & Mozilla 1.5+ with 1024x768 resolution</font></td>
<td width="15%">&nbsp;</td></tr>
</tbody></table></td></tr></table>



