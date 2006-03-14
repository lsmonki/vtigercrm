<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<form action="index.php" method="post" name="new" id="form">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="mode" value="{$MODE}">
<input type="hidden" name="action" value="CreateProfile1">
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
<td colspan="2" class="calDayHourCell">Step 1 of 3 : Basic details of Profile </td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
</tr>
<tr>
<td align="right" width="50%" style="padding-right:10px;">{$CMOD.LBL_NEW_PROFILE_NAME}</td>

<td width="50%" align="left" style="padding-left:10px;"><input type="text" name="profile_name" id="pobox" value="{$PROFILE_NAME}" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"/></td>
</tr>
<tr>
<td align="right" style="padding-right:10px;">{$CMOD.LBL_DESCRIPTION}</td>
<td align="left" style="padding-left:10px;"><textarea name="profile_description" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">{$PROFILE_DESCRIPTION}</textarea></td>
</tr>
<tr><td colspan="2" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
<tr>

<td colspan="2" align="right">
<input type="button" value=" &lsaquo; Back " name="back" disabled />&nbsp;&nbsp;
<input type="submit" value=" Next &rsaquo; " accessKey="N" class="button" name="Next"/>&nbsp;&nbsp;
<input type="button" value=" Cancel " name="Cancel" onClick="window.history.back()";/>

</td>
</tr>
<tr><td colspan="2" style="border-top:1px solid #CCCCCC;">&nbsp;</td></tr>
</td>

</tr>
</table>
	
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

