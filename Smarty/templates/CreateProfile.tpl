{*<!--
/*********************************************************************************
  ** The contents of this file are subject to the vtiger CRM Public License Version 1.0
   * ("License"); You may not use this file except in compliance with the License
   * The Original Code is:  vtiger CRM Open Source
   * The Initial Developer of the Original Code is vtiger.
   * Portions created by vtiger are Copyright (C) vtiger.
   * All Rights Reserved.
  *
 ********************************************************************************/
-->*}
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
		<tr>
		{include file='SettingsMenu.tpl'}
				<td width="75%" valign="top">
						<form action="index.php" method="post" name="profileform" id="form">
						<input type="hidden" name="module" value="Users">
						<input type="hidden" name="mode" value="{$MODE}">
						<input type="hidden" name="action" value="CreateProfile1">
						<input type="hidden" name="parenttab" value="Settings">
						<input type="hidden" name="parent_profile" value="{$PARENT_PROFILE}">
						<input type="hidden" name="radio_button" value="{$RADIO_BUTTON}">
						<input type="hidden" name="profile_name" value="{$PROFILE_NAME}">
						<table width="95%" border="0" cellpadding="0" cellspacing="0"  align="center">
							<tr>
										<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
													<span class="lvtHeaderText">
															<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_PROFILES}</b></span>
																<hr noshade="noshade" size="1" />
										</td>
							</tr>
							<tr>
										<td  valign="top" class="leadTable">
													<table width="100%" border="0" cellpadding="5" cellspacing="0">
														<tr>
																<td width="5%" style="border-bottom:1px dashed #CCCCCC;">
																		<img src="{$IMAGE_PATH}profile.gif" align="absmiddle">
																</td>
																<td style="border-bottom:1px dashed #CCCCCC;"> 
																		<span class="genHeaderGrayBig">{$CMOD.LBL_BASIC_PROFILE_DETAILS}</span><br>
																		<span	class="genHeaderSmall">{$CMOD.LBL_STEP_1_3}</span>

																</td>
														</tr>
													</table>
													<table width="95%" border="0" cellpadding="5" cellspacing="0" align="center">
															<tr><td colspan="2">&nbsp;</td></tr>
															<tr>
																	<td align="right" width="25%" style="padding-right:10px;">
																			<b style="color:#FF0000;font-size:16px;">{$APP.LBL_REQUIRED_SYMBOL}</b>&nbsp;<b>{$CMOD.LBL_NEW_PROFILE_NAME} : </b></td>
																	<td width="75%" align="left" style="padding-left:10px;">
																			<input type="text" name="profile_name" id="pobox" value="{$PROFILENAME}" class="txtBox" /></td>
															</tr>
															<tr><td colspan="2">&nbsp;</td></tr>
															<tr>
																	<td align="right" style="padding-right:10px;" valign="top"><b>{$CMOD.LBL_DESCRIPTION} : </b></td>
																	<td align="left" style="padding-left:10px;"><textarea name="profile_description" class="txtBox">{$PROFILEDESC}</textarea></td>
															</tr>
															<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;" height="75">&nbsp;</td></tr>
															<tr>
																	<td colspan="2" align="right">
																					<input type="button" value=" &lsaquo; {$APP.LBL_BACK} " name="back" class="classBtnDisable" disabled />&nbsp;&nbsp;
																					<input type="button" value=" {$APP.LNK_LIST_NEXT} &rsaquo; " class="classBtn" name="Next" onClick="rolevalidate();"/>&nbsp;&nbsp;
																					<input type="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " title="{$APP.LBL_CANCEL_BUTTON_TITLE}" name="Cancel" onClick="window.history.back()"; class="classBtn"/>
																	</td>
															</tr>
															
												</table>
										</td>
									</tr>
				</table></form>
		</td>
	</tr>
</table>

	{include file='SettingsSubMenu.tpl'}
<script>
function rolevalidate()
{ldelim}
    var profilename = document.getElementById('pobox').value;
    profilename = profilename.replace(/ /gi,'',profilename);
    if(profilename != '')
	dup_validation(profilename);
    else
    {ldelim}
        alert('Enter The Profile name');
        document.getElementById('pobox').focus();
    {rdelim}
{rdelim}


function dup_validation(profilename)
{ldelim}
	new Ajax.Request(
		'index.php',
		{ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
			method: 'post',
			postBody: 'module=Users&action=UsersAjax&file=CreateProfile1&ajax=true&dup_check=true&profile_name='+profilename,
			onComplete: function(response) {ldelim}
					if(response.responseText == 'SUCESS')
						document.profileform.submit();
					else
						alert(response.responseText);
				{rdelim}
		{rdelim}
	);
{rdelim}
</script>
