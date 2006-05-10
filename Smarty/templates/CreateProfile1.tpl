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
					<form action="index.php" method="post" name="new" id="form">
					<input type="hidden" name="module" value="Users">
					<input type="hidden" name="profile_name" value="{$PROFILE_NAME}">
					<input type="hidden" name="profile_description" value="{$PROFILE_DESCRIPTION}">
					<input type="hidden" name="mode" value="{$MODE}">
					<input type="hidden" name="action" value="profilePrivileges">
					<input type="hidden" name="parenttab" value="Settings">
					<input type="hidden" name="parent_profile" value="{$PARENT_PROFILE}">
					<input type="hidden" name="radio_button" value="{$RADIO_BUTTON}">
					<table width="95%" border="0" cellpadding="0" cellspacing="0" align="center">
							<tr>
									<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
											<span class="lvtHeaderText">
											<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a>
													 > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_PROFILES}</b></span>
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
																	<span class="genHeaderGrayBig">{$CMOD.LBL_SELECT_BASE_PROFILE}</span><br>
																	<span	class="genHeaderSmall">{$CMOD.LBL_STEP_2_3}</span>
															</td>
													</tr>
											</table>
											<table width="95%" border="0" cellpadding="5" cellspacing="0" align="center">
													<tr><td colspan="2">&nbsp;</td></tr>
													<tr>
															<td align="right" width="10%" style="padding-right:10px;">
															{if  $RADIO_BUTTON neq 'newprofile'}
															<input name="radiobutton" checked type="radio" value="baseprofile" />
															{else}
															<input name="radiobutton" type="radio"  value="baseprofile" />
															{/if}
															</td>
															<td width="90%" align="left" style="padding-left:10px;">{$CMOD.LBL_BASE_PROFILE_MESG}</td>
													</tr>
													<tr>
															<td align="right"  style="padding-right:10px;">&nbsp;</td>
															<td align="left" style="padding-left:10px;">{$CMOD.LBL_BASE_PROFILE}
																	<select name="parentprofile" class="importBox">
																		{foreach item=combo from=$PROFILE_LISTS}
																				{if $PARENT_PROFILE eq $combo.1}
																				<option  selected value="{$combo.1}">{$combo.0}</option>	
																				{else}
																				<option value="{$combo.1}">{$combo.0}</option>	
																				{/if}
																		{/foreach}
																	</select>
															</td>
													</tr>
													<tr><td colspan="2">&nbsp;</td></tr>
													<tr><td align="center" colspan="2"><b>(&nbsp;{$CMOD.LBL_OR}&nbsp;)</b></td></tr>
													<tr><td colspan="2">&nbsp;</td></tr>
													<tr>
															<td align="right" style="padding-right:10px;">
															{if  $RADIO_BUTTON eq 'newprofile'}
															<input name="radiobutton" checked type="radio" value="newprofile" />
															{else}
															<input name="radiobutton" type="radio" value="newprofile" />
															{/if}
															</td>
															<td  align="left" style="padding-left:10px;">{$CMOD.LBL_BASE_PROFILE_MESG_ADV}</td>
													</tr>
													<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;" height="75">&nbsp;</td></tr>
													<tr>
															<td colspan="2" align="right">
																	<input type="Submit" value=" &lsaquo; {$APP.LBL_BACK} " name="back" onclick="this.form.action.value='CreateProfile';"  class="classBtn"/>&nbsp;&nbsp;
																	<input type="Submit" value=" {$APP.LNK_LIST_NEXT} &rsaquo; " name="Next" class="classBtn"/>&nbsp;&nbsp;
																	<input type="button" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " title="{$APP.LBL_CANCEL_BUTTON_TITLE}" name="Cancel" class="classBtn"/>
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

