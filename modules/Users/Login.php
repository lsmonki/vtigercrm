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

<table border="0" cellspacing="0" cellpadding="0" width="100%">
	<tr>
		<td align="center"><br><br><br><br>
				<table border="0" cellspacing="0" cellpadding="0" width="720" class="loginTopBg">
						<tr>
								<td align=left><img src="include/images/loginVtigerCRM.gif" alt="vtiger CRM" title="vtiger CRM"></td>
								<td align=right><img src="include/images/loginAlpha.gif" alt="Beta" title="Beta"></td>
						</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="0" width="720" class="loginTopShade">
						<tr>
							<td><img src="include/images/loginTopShade.gif"></td>
						</tr>
				</table>
				<table border="0" cellspacing="0" cellpadding="0" width="720 "class="loginBg small" >
						<tr>
							<td valign="top" width="60%" height="250" class="loginBillBoard small">
									<div align=left style="padding:20px">
													<span style="font-size:20px;color:white">Welcome to vtiger CRM 5 Beta</span><br><br>
													<b>New in Beta</b> <br>
													<li>Integrated webmail Client</li>
													<li>Campaign Management</li>
													<li>Better Performance</li>
													<li>Sexier Dashboards,Reports,Calendar</li>	 		
													<li>Migration Support</li><br>	
															How is 'vtiger-experience' ? <br>	
													<li><a href='http://blogs.vtiger.com' vtiger_tabindex="6">vtiger blogs </a></li>
													<li><a href='http://www.bloglines.com' vtiger_tabindex="7">bloglines </a></li>
													<li><a href='http://technorati.com/' vtiger_tabindex="8">technorati</a></li>
													<li><a href='http://digg.com/' vtiger_tabindex="9">digg</a></li>
													<li><a href='http://www.blogger.com' vtiger_tabindex="10">blogger</a></li>
										</div>
							</td>
							<td valign="top"><br>
								<!-- sign in -->
								<table border="0" cellspacing="0" cellpadding="0" width="90%" bgcolor="white" class="small">
										<tr>
												<td>
														<table border="0" cellspacing="0" cellpadding="0" width="100%" class="small">
																<tr>
																		<td align=left><img src="include/images/loginSITopLeft.gif"></td>
																		<td align=right><img src="include/images/loginSITopRight.gif"></td>
																</tr>
														</table>
														<form action="index.php" method="post" name="DetailView" id="form">
														<input type="hidden" name="module" value="Users">
														<input type="hidden" name="action" value="Authenticate">
														<input type="hidden" name="return_module" value="Users">
														<input type="hidden" name="return_action" value="Login">
														<table border="0" cellspacing="0" cellpadding="5" align="center" width="90%" class="small">
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
																				<td class="small" align="right">
																						<?php echo $current_module_strings['LBL_USER_NAME'] ?>
																				</td>
																				<td class="small">
																						<input class="textbox" type="text"  name="user_name"   value="<?php echo $login_user_name ?>" vtiger_tabindex="1">
																				</td>
																	</tr>
																	<tr>
																				<td class="small" align="right">
																							<?php echo $current_module_strings['LBL_PASSWORD'] ?>
																				</td>
																				<td class="small">
																							<input class="textbox" type="password" size='20' name="user_password"  value="<?php echo $login_password ?>" vtiger_tabindex="2">
																				</td>
																	</tr>
																	<tr>
																				<td class="small" align="right" style="background-color:#f5f5f5">
																						<?php echo $current_module_strings['LBL_THEME'] ?>
																				</td>
																				<td class="small" style="background-color:#f5f5f5">
																							<select class='small' name='login_theme' style="width:120px;" vtiger_tabindex="3">
																									<?php echo get_select_options_with_id(get_themes(), $display_theme) ?>
																							</select>
																				</td>
																	</tr>
																	<tr>
																				<td class="small" align="right" style="background-color:#f5f5f5">
																							<?php echo $current_module_strings['LBL_LANGUAGE'] ?>
																				</td>
																				<td class="small" style="background-color:#f5f5f5">
																							<select class='small' name='login_language' style="width:120px;" vtiger_tabindex="4">
																										<?php echo get_select_options_with_id(get_languages(), $display_language) ?>
																								</select>
																				  </td>
																	 </tr>
																	<tr>
																			<td></td>
																			<td>
																				<input class=small title="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>" accesskey="<?php echo $current_module_strings['LBL_LOGIN_BUTTON_TITLE'] ?>"  type="image" src="include/images/loginBtnSignin.gif" name="Login" value="  <?php echo $current_module_strings['LBL_LOGIN_BUTTON_LABEL'] ?>  "  vtiger_tabindex="5">
																				
																			 </td>
																	  </tr>
															</table>
														</form>	
														<table border="0" cellspacing="0" cellpadding="0" width="100%">
															<tr>
																<td align=left><img src="include/images/loginSIBottomLeft.gif"></td>
																<td align=right><img src="include/images/loginSIBottomRight.gif"></td>
															</tr>
														</table>
												</td>
										</tr>
								</table>
							</td>
					</tr>
				</table>
					<table border="0" cellspacing="0" cellpadding="0" width="720" class="loginBottomBg">
						<tr>
									<td align="left"><img src="include/images/loginBottomBg.gif"></td>
						</tr>
					</table>
					<table border="0" cellspacing="0" cellpadding="0" width="720" >
							<tr>
									<td align="left"><img src="include/images/loginBottomURL.gif" alt="vtiger CRM" title="vtiger CRM"></td>
						    </tr>
					</table>
			</td>
		</tr>
</table>


