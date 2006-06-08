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
{literal}
<script>
function fetchSaveNotify(id)
{
	$("editdiv").style.display="none";
	$("status").style.display="inline";
	var active = $("notify_status").options[$("notify_status").options.selectedIndex].value;
	var subject = $("notifysubject").value;
        var body = $("notifybody").value;
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=UsersAjax&module=Users&file=SaveNotification&active='+active+'&notifysubject='+subject+'&notifybody='+body+'&record='+id,
                        onComplete: function(response) {
                                $("status").style.display="none";
				$("notifycontents").innerHTML=response.responseText;
                        }
                }
        );
}

function fetchEditNotify(id)
{
	$("status").style.display="inline";
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody:'action=UsersAjax&module=Users&file=EditNotification&record='+id,
                        onComplete: function(response) {
                                $("status").style.display="none";
                                $("editdiv").innerHTML=response.responseText;
				$("editdiv").style.display="inline";
                        }
                }
        );
}
</script>
{/literal}
