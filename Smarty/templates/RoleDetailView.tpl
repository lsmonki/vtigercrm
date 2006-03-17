<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<form id="form" name="new" action="index.php" method="post">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="createrole">
<input type="hidden" name="parenttab" value="Settings">
<input type="hidden" name="returnaction" value="RoleDetailView">
<input type="hidden" name="roleid" value="{$ROLEID}">
<input type="hidden" name="mode" value="edit">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_CREATE_NEW_GROUP}</b></span>
<hr noshade="noshade" size="1"/>
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">

	<table align="center" border="0" cellpadding="5" cellspacing="0" width="75%">
	<tbody><tr>
	<td colspan="2" style="border-bottom: 1px dashed rgb(204, 204, 204);">&nbsp;</td>
	</tr>
	<tr>
	<td style="border-bottom: 1px dashed rgb(204, 204, 204); padding-right: 10px;" align="left" width="30%"><b>{$ROLE_NAME}</b></td>
	<td style="border-bottom: 1px dashed rgb(204, 204, 204);" align="right" width="70%">
	<input title="Edit" accessKey="C" class="small" onclick="this.form.action.value=\'createrole\'" type="submit" name="Edit" value="Edit Role">
	</td>
	</tr>
	<tr>
	<td style="padding-right: 10px;" align="right">&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	<tr>
	<td colspan="2" style="border-bottom: 1px solid rgb(204, 204, 204); padding-right: 10px;" align="right" valign="top">
	<div style="overflow: auto; position: relative; left: 10px; top: 0px; width: 100%; height: 225px; text-align: left;">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
	
	<tr>
	<td align="right" valign="top" width="30%"><b>Associated Users :</b></td>
	<td align="left" valign="top" width="70%">
	<ul style="list-style-type: none;">
	{foreach item=elements from=$ROLEINFO.userinfo}
	<li><a href="index.php?module=Users&action=DetailView&record={$elements.0}">{$elements.1}</a></li>
	{/foreach}	
	</ul>
	</td>
	</tr>
	
	<tr>
	<td align="right" valign="top" width="30%"><b>Associated profiles :</b></td>
	<td align="left" valign="top" width="70%">
	<ul style="list-style-type: none;">
	{foreach item=elements from=$ROLEINFO.profileinfo}
	<li><a href="index.php?module=Users&action=profilePrivileges&profileid={$elements.0}">{$elements.1}</a></li>
	{/foreach}	
	</ul>
	</td>
	</tr>
	
	</tbody>
	</table>
	</div>
	</td>

	</tr>
	<tr>
	<td colspan="2" style="border-top: 1px solid rgb(204, 204, 204);" align="center">
	<input title="Cancel" accessKey="C" class="small" onclick="window.history.back()" type="button" name="Cancel" value=" &lsaquo; Back">	
	</td>
	</tr>
	</tbody></table>

<td colspan="2" style="border-top:1px solid #CCCCCC;">&nbsp;</td>
</tr>
</table>
</form>
</td>

</tr>
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
	{include file='SettingsSubMenu.tpl'}

