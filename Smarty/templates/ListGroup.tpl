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

<table width="95%" border="0" cellpadding="0" cellspacing="0"  align="center">
<form action="index.php" method="post" name="new" id="form">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action" value="createnewgroup">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="parenttab" value="Settings">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$CMOD.LBL_GROUP_NAME}</b></span>
<hr noshade="noshade" size="1" /><br>
</td>
</tr>
<tr>
<td valign="top" class="leadTable" style="padding:20px; ">
<table width="100%" cellpadding="5" cellspacing="0" >
	<tr>
		<td style="border-bottom:1px dashed #CCCCCC;" width="50%">
			<img src="{$IMAGE_PATH}groups.gif" align="absmiddle">&nbsp;<span class="genHeaderGrayBig">{$CMOD.LBL_GROUP_NAME}</span>
		</td>
		<td style="border-bottom:1px dashed #CCCCCC;" width="50%" align="right">
			<input title="{$CMOD.LBL_NEW_GROUP}" class="classBtn" type="submit" name="New" value="{$CMOD.LBL_NEW_GROUP}"/>
		</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
		<td colspan="2" >
			<table width="100%" border="0" cellpadding="5" cellspacing="1" class="small" style="background-color: rgb(204, 204, 204);">
                          <tbody>
                            <tr>
                              <td class="lvtCol" width="35%">{$LIST_HEADER.1}</td>
                              <td class="lvtCol" width="50%">{$LIST_HEADER.2}</td>
							  <td class="lvtCol" width="15%">{$LIST_HEADER.0}</td>
                            </tr>
							{section name=entries loop=$LIST_ENTRIES}
                            <tr  class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
                              <td ><a href="index.php?module=Users&action=GroupDetailView&parenttab=Settings&groupId={$LIST_ENTRIES[entries].groupid}">{$LIST_ENTRIES[entries].groupname}</a></td>
                              <td >{$LIST_ENTRIES[entries].description}</td>
							  <td align="center" >
							  	<a href="index.php?module=Users&action=createnewgroup&returnaction=listgroups&parenttab=Settings&mode=edit&groupId={$LIST_ENTRIES[entries].groupid}">
								<img src="{$IMAGE_PATH}editfield.gif" alt="{$APP.LNK_EDIT}" title="{$APP.LNK_EDIT}" border="0" align="absmiddle"></a>&nbsp;|	
								<a href="#" onClick="deletegroup('{$LIST_ENTRIES[entries].groupid}','{$LIST_ENTRIES[entries].groupname}')";><img src="{$IMAGE_PATH}delete.gif" alt="{$LNK_DELETE}" title="{$APP.LNK_DELETE}" border="0" align="absmiddle"></a>
							</td>
                            </tr>
							{/section}	
                          </tbody>
                        </table>
			</td>
	</tr>
</table>

</td>

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

<!-- 

<td class="detailedViewHeader" width="15%"><b>{$LIST_HEADER.0}</b></td>
	<td class="detailedViewHeader" width="35%"><b>{$LIST_HEADER.1}</b></td>
	<td class="detailedViewHeader" width="50%"><b>{$LIST_HEADER.2}</b></td>
	</tr>
	
	{section name=entries loop=$LIST_ENTRIES}
		<tr class="{cycle values="dvtCellInfo,dvtCellLabel"}">
		<td nowrap>&nbsp;
		<img src="{$IMAGE_PATH}del.gif" alt="{$APP.LNK_DELETE}" title="{$APP.LNK_DELETE}" border="0" height="15" width="15" onClick="deletegroup('{$LIST_ENTRIES[entries].groupid}','{$LIST_ENTRIES[entries].groupname}')";>
		<a href="index.php?module=Users&action=createnewgroup&returnaction=listgroups&mode=edit&groupId={$LIST_ENTRIES[entries].groupid}"><img src="{$IMAGE_PATH}edit.gif" alt="{$APP.LNK_EDIT}" title="{$APP.LNK_EDIT}" border="0"></a>
		&nbsp;</td>
		<td nowrap><a href="index.php?module=Users&action=GroupDetailView&groupId={$LIST_ENTRIES[entries].groupid}">{$LIST_ENTRIES[entries].groupname}</a></td>
		<td nowrap>{$LIST_ENTRIES[entries].description}&nbsp;</td>
		<tr>
	{/section}	
 -->
