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



<table border="0" cellspacing="0" cellpadding="0" width=100%>
<tr>
	<td align=center><br><br><br><br>
	
		<!-- Login Starts -->
		<table border="0" cellspacing="0" cellpadding="0" width=700 style="border:2px solid rgb(119,119,119)">
		<tr>
			<td align=left>
			
			
			<table border="0" cellspacing="0" cellpadding='0' width='100%' background="themes/images/loginTopHeaderBg.gif">
			<tr>
				<td align=left><img src="themes/images/loginTopHeaderName.gif"></td>
				<td align=right><!--img src="themes/images/loginTopVersion.gif"--></td>
			</tr>
			</table>
			<table border="0" cellspacing="0" cellpadding='10' width='100%'>
			<tr>
				<td align=left valign=top width=50% class=small style="padding:10px">
					<!-- Promo Text and Image -->
					<table border=0> 
					<tr>
					<td>
					<img src="themes/images/loginPromoText.gif" alt="vtiger CRM 5 - 100% Open Source CRM" title="vtiger CRM 5 - 100% Open Source CRM">
					</td>
					</tr>
					<tr>
					<td class=small style="padding-left:10px; color:#737373">
- AJAX-based user interface<br>
- Complete customer life cycle management<br>
- Collaboration through e-mail, portal, and live chat<br>
- Customization &  fine-grained security management<br>
- Ready to use reports & dashboards<br>
					</td>
					</tr>
					</table>
					
				</td>
				<td align=center valign=top width=50%>
					<?php
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
					<!-- Sign in box -->
					<form action="index.php" method="post" name="DetailView" id="form">
					<input type="hidden" name="module" value="Users">
					<input type="hidden" name="action" value="Authenticate">
					<input type="hidden" name="return_module" value="Users">
					<input type="hidden" name="return_action" value="Login">
					<table border="0" cellspacing="0" cellpadding="0" width=100%>
					<tr><td align=left><img src="themes/images/loginSignIn.gif" alt="Sign In" title="Sign In" border="0"></td></tr>
					<tr>
						<td background="themes/images/loginSignInShade.gif" style="background-repeat: repeat-x;" align=center valign=top class=small>
						<br>
							<table border=0 cellspacing=0 cellpadding=5 width=80% class="small">
							<tr><td width=30% class=small align=right><?php echo $current_module_strings['LBL_USER_NAME'] ?></td><td width=70% class=small><input class="small" style="width:100%" type="text"  name="user_name"   value="<?php echo $login_user_name ?>" tabindex="1"></td></tr>
							<tr><td class=small align=right><?php echo $current_module_strings['LBL_PASSWORD'] ?></td><td class=small><input class="small" style="width:100%" type="password" size='20' name="user_password"  value="<?php echo $login_password ?>" tabindex="2"></td></tr>
							<tr>
								<td colspan=2 style="padding:0px">
									<table border=0 cellspacing=0 cellpadding=5 width=100%>
									<tr>
									<td width=30% style="background-color:#efefef;" class=small align=right>	<?php echo $current_module_strings['LBL_THEME'] ?></td>
									<td width=70% style="background-color:#efefef;"  class=small>
									<select class='small' name='login_theme' style="width:100%" tabindex="3">	
										<?php echo get_select_options_with_id(get_themes(), $display_theme) ?>										     </select>
									</tr>		
									<tr>
									<td style="background-color:#efefef;" class=small align=right><?php echo $current_module_strings['LBL_LANGUAGE'] ?></td>
									<td style="background-color:#efefef;"  class=small>
									<select class='small' name='login_language' style="width:100%" vtiger_tabindex="4">
																													<?php echo get_select_options_with_id(get_languages(), $display_language) ?>
								        </select>
									</tr>
									</table>
								</td>
							</tr>
							<tr><td colspan=2>&nbsp;</td></tr>
							<?php
							if( isset($_SESSION['validation'])){
							?>
							<tr>
								<td colspan="2"><font color="Red"> <?php echo $current_module_strings['VLD_ERROR']; ?> </font></td>
							</tr>
							<?php
							}
							else if(isset($login_error) && $login_error != "")
							{
							?>
							<tr>
								<td colspan="2"><b class="small"><font color="Brown">
								<?php echo $login_error ?>
								</font>
								</b>
								</td>
							</tr>
							<?php
							}
							?>
							<tr>
								<td colspan=2 style="padding:0px" align=center>
								<input class=small title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>"  type="image" src="themes/images/loginBtnSignIn.gif" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  "  tabindex="5">	
								</td>
							</tr>
							</table>
						<br>
						</td>
					</tr>
					</table>
					
				</td>
			</tr>
			</table>
			
			</td>
		</tr>
		</table>
	
			<!-- Shadow -->
			<table border=0 cellspacing=0 cellpadding=0 width=700>
			<tr>
				<td><img src="themes/images/loginBottomShadowLeft.gif"></td>
				<td width=100% background="themes/images/loginBottomShadowBg.gif"><img src="themes/images/loginBottomShadowBg.gif"></td>
				<td><img src="themes/images/loginBottomShadowRight.gif"></td>
			</tr>
			</table>
	
	
	
	</td>
</tr>
</table>
