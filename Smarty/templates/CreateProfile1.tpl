<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<form action="index.php" method="post" name="new" id="form">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="profile_name" value="{$PROFILE_NAME}">
<input type="hidden" name="profile_description" value="{$PROFILE_DESCRIPTION}">
<input type="hidden" name="mode" value="{$MODE}">
<input type="hidden" name="action" value="profilePrivileges">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_PROFILES}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<table width="75%" border="0" cellpadding="5" cellspacing="0" align="center">
	<tr>
	<td colspan="2" class="calDayHourCell">Step 2 of 3 : Basic details of Profile </td>
	
	</tr>
	<tr>
	<td>&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td align="right" width="5%" style="padding-right:10px;"><input name="radiobutton" checked type="radio" value="baseprofile" /></td>
	<td width="95%" align="left" style="padding-left:10px;">I would like to setup a base profile and edit privileges <b>(Recommened)</b></td>
	</tr>
	<tr>
	<td align="right" width="30%" style="padding-right:10px;">&nbsp;</td>
	<td width="70%" align="left" style="padding-left:10px;">Base Profile:
	<select name="parentprofile">
	{foreach item=combo from=$PROFILE_LISTS}
		<option value="{$combo.1}">{$combo.0}</option>	
	{/foreach}
	</select>
	</td></tr>
	
	<tr><td align="center" colspan="2"><b>OR</b></td></tr>
	<tr>
	<td align="right" width="5%" style="padding-right:10px;"><input name="radiobutton" type="radio" value="newprofile" /></td>
	<td width="95%" align="left" style="padding-left:10px;">I will choose the privileges from scratch <b>(Advanced Users)</b></td>
	</tr>
	<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>

	<tr>
	<td colspan="2" align="right">
	<input type="button" value=" &lsaquo; Back " name="back" onclick="window.history.back()" />&nbsp;&nbsp;
	<input type="Submit" value=" Next &rsaquo; " accessKey="N" class="button" name="Next"/>&nbsp;&nbsp;
	<input type="button" value=" Cancel " name="Cancel"/>
	</td>
	</tr>
	<tr><td colspan="2" style="border-top:1px solid #CCCCCC;">&nbsp;</td></tr>
	</table

	
</td>
<td width="1%" style="border-right:1px dotted #CCCCCC;">&nbsp;</td>
</tr>
</form>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
	{include file='SettingsSubMenu.tpl'}

