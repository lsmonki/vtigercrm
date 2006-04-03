<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
{literal}
<script>
function ajaxSaveResponse(response)
{
	document.getElementById("announcement").innerHTML=response.responseText;
	hide("an_busy");
}
function Announcement()
{
	show("an_busy");
	var ajaxObj = new Ajax(ajaxSaveResponse);
	var announcement=document.getElementById("announcement").value;
	//var title=document.getElementById("title_announce").value;
	var urlstring = "module=Users&action=UsersAjax&announcement="+announcement+"&announce_save=yes";
	ajaxObj.process("index.php?",urlstring);
}
</script>
{/literal}
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_USER_MANAGEMENT} > {$MOD.LBL_ANNOUNCEMENT}</b><div id="an_busy" style="display:none;float:left;position:relative;"><img src="{$IMAGE_PATH}vtbusy.gif" align="right"></div></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">

	<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
	<tbody><tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="7"><img src="{$IMAGE_PATH}top_left.jpg" align="top"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif; height: 6px;" bgcolor="#ebebeb"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="8"><img src="{$IMAGE_PATH}top_right.jpg" align="top" height="6" width="8"></td>
	</tr>
	<tr>

	<td bgcolor="#ebebeb" width="7"></td>
	<td bgcolor="#ececec">
	<table border="0" cellpadding="5" cellspacing="0" width="100%">
	<tbody><tr bgcolor="#ffffff">
	<td width="5%"><img src="include/images/announ.gif" align="absmiddle" hspace="5" vspace="5"></td>
	<td width="95%">
	<span class="genHeaderBig">{$MOD.LBL_SETTING_ANNOUNCEMENT}</span><br>
	{$MOD.LBL_ANNOUNCEMENT_INFO}
	</td>
	
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td align="right" valign="top">&nbsp;</td>
	<td align="left"><b>{$MOD.LBL_SETTING_ANNOUNCEMENT}</b><br>
	<textarea id="announcement" name="announcement" class="detailedViewTextBox" rows="5">{$ANNOUNCE}</textarea>
	</td>
	</tr>	

	<tr>
	<td align="right" valign="top">&nbsp;</td>
	<td align="center">
	<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" onclick="javascript:Announcement();" type="button">
	</td>
	</tr>
	</tbody></table>
	</td>
	<td bgcolor="#ebebeb" width="8"></td>
	
	</tr>
	<tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="7"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"></td>
	<td style="font-size: 1px;" bgcolor="#ececec" height="8"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="8"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom"></td>
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
	{include file='SettingsSubMenu.tpl'}
{literal}
<script>
function validate() {
	if (!emptyCheck("server","ftp Server Name","text")) return false
		if (!emptyCheck("server_username","ftp User Name","text")) return false
			return true;

}
</script>
{/literal}
