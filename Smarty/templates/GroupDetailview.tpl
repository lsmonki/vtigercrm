<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<form action="index.php" method="post" name="new" id="form">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="createnewgroup">
<input type="hidden" name="groupId" value="{$GROUPID}">
<input type="hidden" name="mode" value="edit">
<input type="hidden" name="parenttab" value="Settings">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_GROUP_MEMBERS_LIST}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<table align="center" border="0" cellpadding="5" cellspacing="0" width="75%">
	<tbody><tr><td colspan="2" style="border-bottom: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
	<tr>
	<td style="padding-right: 10px;" align="center"></td>
	<td align="left">
	<input title="Back" accessKey="C" class="button" onclick="window.history.back();" type="button" name="New" value=" <  Back " >
	<input value="   Edit   " title="Edit" accessKey="E" class="button" type="submit" name="Edit" >&nbsp;&nbsp;
	<input value=" Delete " title="Delete" accessKey="D" class="button" type="button" name="Delete" onClick="deletegroup('{$GROUPID}','{$GROUP_NAME}')";>&nbsp;&nbsp;
	</td>
	</tr>
	<tr><td colspan="2" style="border-top: 1px dashed rgb(204, 204, 204);">&nbsp;</td></tr>
	<tr>
	<td style="padding-right: 10px;" align="right" width="30%"><b>Group Name : </b></td>
	<td align="left" width="70%">{$GROUPINFO.0.groupname}</td>
	</tr>
	<tr>
	
	<td style="padding-right: 10px;" align="right"><b>Description : </b></td>
	<td>{$GROUPINFO.0.description}</td>
	</tr>
	<tr>
	<td style="padding-right: 10px;" align="right">&nbsp;</td>
	<td>&nbsp;</td>
	</tr>
	
	<tr>
	
	<td style="border-bottom: 1px solid rgb(204, 204, 204); padding-right: 10px;" align="right" valign="top"><b>Member List : </b></td>
	<td style="border-bottom: 1px solid rgb(204, 204, 204); text-align: left;">
	<div style="overflow: auto; position: relative; left: 10px; top: 0px; width: 100%; height: 225px; text-align: left;">
	
	{foreach key=type item=details from=$GROUPINFO.1} 
		{if $details.0 neq ''}		
		<li><b style="margin: 0pt; padding: 0pt; font-weight: bold;">{$type}</b>
		<ul style="list-style-type: none;">
		{foreach item=element from=$details}
			<li><a href="index.php?module=Users&action={$element.memberaction}&{$element.actionparameter}={$element.memberid}">{$element.membername}</a></li>
		{/foreach}
		</ul>
		</li>
		{/if}
	{/foreach}	
	
	</div>
	</td>
	</tr>
	</tbody></table>
	
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
function deletegroup(id,groupname)
{ldelim}
		if(confirm("Are you sure you want to delete the group "+groupname+" ?"))
			document.location.href="index.php?module=Users&action=DeleteGroup&groupId="+id;	
		else
			return false;
{rdelim}
</script>
	{include file='SettingsSubMenu.tpl'}

