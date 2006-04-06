<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/ajax.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_COMMUNICATION_TEMPLATES} > {$MOD.NOTIFICATIONSCHEDULERS}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">
	
	<div id="notifycontents">
	{include file='Settings/EmailNotificationContents.tpl'}
	</div>
	
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
	<div id="editdiv" style="display:none;position:absolute;left:180px;top:30px;"></div>
	<div id="status" style="display:none;position:absolute;background-color:#bbbbbb;left:887px;top:0px;height:17px;white-space:nowrap;">Processing Request...</div>
{literal}
<script>
function fetchSaveNotify(id)
{
	hide('editdiv');
	show('status');
	var ajaxObj = new Ajax(ajaxsavenotifyresponse);
	var active = document.getElementById("notify_status").options[document.getElementById("notify_status").options.selectedIndex].value;
	var subject = document.getElementById("notifysubject").value;
	var body = document.getElementById("notifybody").value;
	urlstring ='action=UsersAjax&module=Users&file=SaveNotification&active='+active+'&notifysubject='+subject+'&notifybody='+body+'&record='+id;
	ajaxObj.process("index.php?",urlstring);
}
function ajaxsavenotifyresponse(response)
{
	hide("status");
	document.getElementById("notifycontents").innerHTML=response.responseText;	
}

function fetchEditNotify(id)
{
	show('status');
	var ajaxObj = new Ajax(ajaxnotifyresponse);
	urlstring ='action=UsersAjax&module=Users&file=EditNotification&record='+id;
	ajaxObj.process("index.php?",urlstring);
}
function ajaxnotifyresponse(response)
{
	hide("status");
	document.getElementById("editdiv").innerHTML=response.responseText;	
	show("editdiv");
}
</script>
{/literal}
