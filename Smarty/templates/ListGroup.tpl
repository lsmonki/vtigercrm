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
<input type="hidden" name="mode" value="create">
<input type="hidden" name="parenttab" value="Settings">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_GROUP_NAME}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<table width="100%" cellpadding="3" cellspacing="0" >
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td colspan="2" align="left">&nbsp;</td>
	<td align="right"><input title="New" accessKey="C" class="button" type="submit" name="New" value="New Group"/></td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	
	<tr>
	<td class="detailedViewHeader" width="15%"><b>{$LIST_HEADER.0}</b></td>
	<td class="detailedViewHeader" width="35%"><b>{$LIST_HEADER.1}</b></td>
	<td class="detailedViewHeader" width="50%"><b>{$LIST_HEADER.2}</b></td>
	</tr>
	
	{section name=entries loop=$LIST_ENTRIES}
		<tr class="{cycle values="dvtCellInfo,dvtCellLabel"}">
		<td nowrap>&nbsp;
		<img src="{$IMAGE_PATH}del.gif" border="0" height="15" width="15" onClick="deletegroup('{$LIST_ENTRIES[entries].groupid}','{$LIST_ENTRIES[entries].groupname}')";>
		<a href="index.php?module=Users&action=createnewgroup&returnaction=listgroups&mode=editgroupId={$LIST_ENTRIES[entries].groupid}"><img src="{$IMAGE_PATH}edit.gif" alt="Edit" title="Edit" border="0"></a>
		&nbsp;</td>
		<td nowrap><a href="index.php?module=Users&action=GroupDetailView&groupId={$LIST_ENTRIES[entries].groupid}">{$LIST_ENTRIES[entries].groupname}</a></td>
		<td nowrap>{$LIST_ENTRIES[entries].description}&nbsp;</td>
		<tr>
	{/section}	
			
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
function deletegroup(id,groupname)
{ldelim}
		if(confirm("Are you sure you want to delete the group "+groupname+" ?"))
			document.location.href="index.php?module=Users&action=DeleteGroup&groupId="+id;	
		else
			return false;
{rdelim}
</script>
	{include file='SettingsSubMenu.tpl'}

