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

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<form action="index.php" method="post" name="new" id="form">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="mode" value="create">
<input type="hidden" name="action" value="CreateProfile">
<input type="hidden" name="parenttab" value="Settings">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br />
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_PROFILES}</b></span>
<hr noshade="noshade" size="1" /><br>
</td>
</tr>
<tr>
<td width="75%" style="padding:20px;" valign="top" class="leadTable" >
	<table width="100%" cellpadding="3" cellspacing="0" >
		<tr>
				<td colspan="2" align="left" style="border-bottom:1px dashed #CCCCCC;">
						<img src="{$IMAGE_PATH}profile.gif" align="absmiddle">&nbsp;
						<span class="genHeaderGray">{$MOD.LBL_PROFILES}</span>
				</td>
				<td align="right" style="border-bottom:1px dashed #CCCCCC;"><input title="{$CMOD.LBL_NEW_PROFILE}" class="classBtn" type="submit" name="New" value="{$CMOD.LBL_NEW_PROFILE}"/></td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr><td colspan="3">
		<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
		<tr>
				<td class="lvtCol" width="35%"><b>{$LIST_HEADER.1}</b></td>
				<td class="lvtCol" width="50%"><b>{$LIST_HEADER.2}</b></td>
				<td class="lvtCol" width="15%"><b>{$LIST_HEADER.0}</b></td>
		</tr>
		{section name=entries loop=$LIST_ENTRIES}
		<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
				<td nowrap><a href="index.php?module=Users&action=profilePrivileges&mode=view&parenttab=Settings&profileid={$LIST_ENTRIES[entries].profileid}">{$LIST_ENTRIES[entries].profilename}</a></td>
				<td nowrap>{$LIST_ENTRIES[entries].description}&nbsp;</td>
		<td nowrap align="center">
						<a href="index.php?module=Users&action=profilePrivileges&return_action=ListProfiles&parenttab=Settings&mode=edit&profileid={$LIST_ENTRIES[entries].profileid}"><img src="{$IMAGE_PATH}editfield.gif" alt="Edit" title="Edit" border="0" align="absmiddle"></a>
						{if $LIST_ENTRIES[entries].del_permission eq 'yes'}
							&nbsp;|&nbsp;
						<a href="#"><img src="{$IMAGE_PATH}delete.gif" border="0" height="15" width="15" onclick="DeleteProfile('{$LIST_ENTRIES[entries].profileid}')" align="absmiddle"></a>
						{else}
						{/if}
			</td>
		</tr>
		{/section}
		</table>
			</td></tr>	
		</table>
</td>
<td width="1%">&nbsp;</td>
</tr>
</form>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<script>
function DeleteProfile(profileid)
{ldelim}
	$("status").style.display="inline";
        new Ajax.Request(
                'index.php',
                {ldelim}queue: {ldelim}position: 'end', scope: 'command'{rdelim},
                        method: 'post',
                        postBody:'module=Users&action=UsersAjax&file=ProfileDeleteStep1&profileid='+profileid,
                        onComplete: function(response) {ldelim}
                                $("status").style.display="none";
				$("tempdiv").innerHTML=response.responseText;
                        {rdelim}
                {rdelim}
        );
{rdelim}
</script>
{include file='SettingsSubMenu.tpl'}

