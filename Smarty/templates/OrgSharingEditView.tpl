<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_ORG_SHARING_PRIVILEGES}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr><td height=20 colspan=3>&nbsp;</td></tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<table border="0" cellpadding="" cellspacing="0" width="75%">
	<tbody>
	<form action="index.php" method="post" name="def_org_share" id="form">
	<input type="hidden" name="module" value="Users">
	<input type="hidden" name="action" value="SaveOrgSharing">
	<tr>
	<td class="genHeaderSmall" height="25" valign="middle">Global Access Privileges</td>
	<td align="right"><input class="small" title="Save" accessKey="C" type="submit" name="Save" value="{$CMOD.LBL_SAVE_PERMISSIONS}"></td>
	</tr>
	<tr><td colspan="2" height="20">&nbsp;</td></tr>
	<tr>
	<td colspan="2" style="padding: 0px 0px 0px 1px;" bgcolor="#ffffff">

	<table class="globTab" cellpadding="5" cellspacing="0">
	<tbody>
	{foreach item=elements from=$ORGINFO}	
	<tr>
	<th width="30%">{$elements.0}</th>
	<td width="70%">{$elements.2}</td>
	<tr>
	{/foreach}
	</tbody>
	</table>
	</td>
	</tr>
	<tr><td colspan="2" height="20">&nbsp;</td></tr>
	<tr>
	<td align="center" colspan=2><input class="small" title="Cancel" accessKey="C" type="button" name="Cancel" value="Cancel" onClick="window.history.back();"></td>
	</tr>
	</form>
	</tbody>
	</table>

</td>
<td width="1%" style="border-right:1px dotted #CCCCCC;">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<script>

function checkAccessPermission(share_value)
{ldelim}
	if (share_value == "3")
	{ldelim}
		alert("Potentials, HelpDesk, Quotes, SalesOrder & Invoice Access must be set to Private when the Account Access is set to Private");
		getObj('2_per').options[3].selected=true
			getObj('13_per').options[3].selected=true
			getObj('20_per').options[3].selected=true
			getObj('22_per').options[3].selected=true
			getObj('23_per').options[3].selected=true

	{rdelim}
{rdelim}
</script>

{include file='SettingsSubMenu.tpl'}

