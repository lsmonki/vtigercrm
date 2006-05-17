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
<td width="75%" valign="top" class="showPanelBg">
		<form action="index.php" method="post" name="EditView" id="form">
		<input type='hidden' name='module' value='Users'>
<input type='hidden' name='action' value='EditView'>
<input type='hidden' name='return_action' value='ListView'>
<input type='hidden' name='return_module' value='Users'>
<input type='hidden' name='parenttab' value='Settings'>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
			<tr>
					<td colspan="2" style="padding:5px;">
							<span class="lvtHeaderText">
							<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a>
									 > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_USERS}</b></span>
									<hr noshade="noshade" size="1" />
					</td>
			  </tr>
			  <tr>
					<td style="padding:5px;"> 
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td colspan="2" align="left"><img src="{$IMAGE_PATH}user.gif" align="absmiddle">&nbsp;<span class="genHeaderGray">{$MOD.LBL_USER_MANAGEMENT}</span></td>
									<td align="right" style="padding:5px; ">
											<input title="{$CMOD.LBL_NEW_USER_BUTTON_TITLE}" accessyKey="{$CMOD.LBL_NEW_USER_BUTTON_KEY}" class='classBtn' type='submit' name='button' value="{$CMOD.LBL_NEW_USER_BUTTON_LABEL}" >
								 	 </td>
								</tr>
								<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
							</table>
					</td>
					<td>&nbsp;</td>
			</tr>
			<tr><td colspan=2>
					<div id="ListViewContents">
						{include file="UserListViewContents.tpl"}
					</div>
					
			</td>
			</tr>
		</table>
	</form>
</td>
</tr>
</table>

</td>
</tr>
</table>
<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;vertical-align:center;left:887px;top:0px;height:17px;">Processing Request...</div>
{literal}
<script>
function getListViewEntries_js(module,url)
{
		show("status");
		var ajaxObj = new Ajax(ajaxSaveResponse);
		var urlstring ="module=Users&action=UsersAjax&file=ListView&ajax=true&"+url;
	    ajaxObj.process("index.php?",urlstring);

}
function ajaxSaveResponse(response)
{
	hide("status");
	document.getElementById("ListViewContents").innerHTML= response.responseText;
}
</script>
{/literal}

	{include file='SettingsSubMenu.tpl'}

