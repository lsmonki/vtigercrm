<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">
<table width="100%"  border="0" cellspacing="0" cellpadding="0">

<tr><td class="detailedViewHeader" align="left"><b>{$MOD.LBL_USER_MANAGEMENT}</b></td></tr>
<tr><td class="padTab" align="left">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
<form name='EditView' method='POST' action='index.php'>
<td align="left" width="50%"><input title='New User [Alt+N]' accessyKey='N' class='button' type='submit' name='button' value='New User' ></td>
<td>
{if $USER_IMAGES neq ''}
<script language="JavaScript" type="text/javascript" src="include/js/xfade2.js"></script>
<style type="text/css">@import url(modules/Users/fade.css);</style>
<div id="outerimageContainer">
<a href="#" onClick="document.getElementById('outerimageContainer').style.display='none'";>[X] Close</a>
    <div id="imageContainer">
{foreach item=imagename from=$USER_IMAGES}
    <img style="display: block; opacity: 0.24;" src="test/user/{$imagename}" alt="{$imagename}" height="150" width="210">
{/foreach}	
	</div>
</div>
{/if}
</td>
<td align="center" width="25%">{$RECORD_COUNTS}</td>
<td nowrap>{$NAVIGATION}</td>
</tr>
<tr><td colspan="4">&nbsp;</td></tr>
<tr><td colspan="4"><div id="scrollTab">
<table width="100%"  border="0" cellspacing="0" cellpadding="5">
<input type='hidden' name='module' value='Users'>
<input type='hidden' name='action' value='EditView'>
<input type='hidden' name='return_action' value='ListView'>
<input type='hidden' name='return_module' value='Users'>
<input type='hidden' name='parenttab' value='Settings'>
<tr>
{foreach item=header from=$LIST_HEADER}
	<th class="detailedViewHeader">{$header}</th>
{/foreach}
</tr>
{section name=entries loop=$LIST_ENTRIES}
	<tr class="{cycle values="dvtCellInfo,dvtCellLabel"}">
	{foreach item=listvalues from=$LIST_ENTRIES[entries]}
		<td nowrap>{$listvalues}</td>
	{/foreach}
	</tr>
{/section}
</table>

</div></td>
</form>
<td align="center" valign="top" class="padTab">
<table width="75%"  border="0" cellspacing="0" cellpadding="0">
	<tr><td align="left" class="detailedViewHeader" colspan="2"><b>{$CMOD.LBL_STATISTICS}</b></td></tr>
	<tr><td class="dvtCellLabel" align="right">{$CMOD.LBL_TOTAL}</td>
	<td class="dvtCellInfo" align="left">{$USER_COUNT.user} {$CMOD.LBL_USERS}</td>	
	</tr>	
	<tr><td class="dvtCellLabel" align="right">{$CMOD.LBL_ADMIN}</td>
	<td class="dvtCellInfo" align="left">{$USER_COUNT.admin} {$CMOD.LBL_USERS}</td>	
	</tr>	
	<tr><td class="dvtCellLabel" align="right">{$CMOD.LBL_OTHERS}</td>
	<td class="dvtCellInfo" align="left">{$USER_COUNT.nonadmin} {$CMOD.LBL_USERS}</td>	
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

</td>
</tr>
</table>
<div id="tempdiv" style="display:block;position:absolute;left:350px;top:200px;"></div>
<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;vertical-align:center;left:887px;top:0px;height:17px;">Processing Request...</div>
<script>
function ajaxSaveResponse(response)
{ldelim}
	hide("status");
	document.getElementById("tempdiv").innerHTML=response.responseText;
{rdelim}

function DeleteProfile(userid)
{ldelim}
	show("status");
	var ajaxObj = new Ajax(ajaxSaveResponse);
	var urlstring = "module=Users&action=UsersAjax&file=UserDeleteStep1&record="+userid;
	ajaxObj.process("index.php?",urlstring);
{rdelim}
</script>

	{include file='SettingsSubMenu.tpl'}

