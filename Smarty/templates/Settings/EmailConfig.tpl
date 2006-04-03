<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_CONFIGURATION} > {$MOD.LBL_EMAIL_CONFIG}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">

	{if $EMAILCONFIG_MODE neq 'edit'}	
	<form action="index.php" method="post" name="MailServer" id="form">
	<input type="hidden" name="emailconfig_mode">
	{else}
	<form action="index.php" method="post" name="MailServer" id="form" onsubmit="return validate_mail_server(MailServer);">
	<input type="hidden" name="server_type" value="email">
	{/if}
	<input type="hidden" name="module" value="Settings">
	<input type="hidden" name="action">
	<input type="hidden" name="parenttab" value="Settings">
	<input type="hidden" name="return_module" value="Settings">
	<input type="hidden" name="return_action" value="EmailConfig">
	<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
	<tbody><tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="7"><img src="{$IMAGE_PATH}top_left.jpg" align="top"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif; height: 6px;" bgcolor="#ebebeb"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="8"><img src="{$IMAGE_PATH}top_right.jpg" align="top" height="6" width="8"></td>
	</tr>
	<tr>
	<td bgcolor="#ebebeb" width="7"></td>
	<td style="padding-left: 10px; padding-top: 10px; vertical-align: top;" bgcolor="#ececec">
	<table border="0" cellpadding="10" cellspacing="0" width="100%">
	<tbody><tr>
	<td rowspan="8" style="background-image: url(include/images/noimage.gif); background-position: center; background-repeat: no-repeat;" bgcolor="#ffffff" width="25%">&nbsp;</td>
	<td colspan="2" class="genHeaderBig" width="75%">Mail Server (SMTP) - Settings{$ERROR_MSG}<br><hr> </td>
	</tr>
	{if $EMAILCONFIG_MODE neq 'edit'}	
	<tr><td colspan="2" style="padding-top: 0px;" align="right" width="75%">
	<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='EmailConfig';this.form.emailconfig_mode.value='edit'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
	</tr>
	<tr>
	<td align="right" width="40%"><b>{$MOD.LBL_OUTGOING_MAIL_SERVER} :</b></td>
	<td align="left" width="60%">{$MAILSERVER}</td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_OUTGOING_MAIL_SERVER_LOGIN_USER_NAME} :</b></td>
	<td>{$USERNAME}</td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_OUTGOING_MAIL_SERVER_PASSWORD} :</b></td>
	<td>
	{if $PASSWORD neq ''}
	******
	{/if}&nbsp;
	</td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_REQUIRE_SMTP_AUTHENTICATION} :</b></td>
	<td>
	{if $SMTP_AUTH eq 'checked'}
	yes
	{else}
	no
	{/if}
	</td>
	</tr>
	{else}	
	<tr>
	<td align="right" width="40%"><b>{$MOD.LBL_OUTGOING_MAIL_SERVER} :</b></td>
	<td align="left" width="60%"><input class="txtBox" type="text" name="server" value="{$MAILSERVER}" size="50" /></td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_OUTGOING_MAIL_SERVER_LOGIN_USER_NAME} :</b></td>
	<td><input class="txtBox" type="text" name="server_username" value="{$USERNAME}" size="50"/></td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_OUTGOING_MAIL_SERVER_PASSWORD} :</b></td>
	<td><input class="txtBox" type="password" name="server_password" value="{$PASSWORD}" size="50"/></td>
	</tr>
	<tr>
	<td align="right"><b>{$MOD.LBL_REQUIRE_SMTP_AUTHENTICATION} :</b></td>
	<td><input type="checkbox" name="smtp_auth" {$SMTP_AUTH}/></td>
	</tr>
	{/if}
	<tr><td colspan="2" width="75%">&nbsp; </td></tr>
	<tr><td colspan="2" width="75%" align="center"><hr>
	{if $EMAILCONFIG_MODE eq 'edit'}	
	<br><input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='Save';" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" >&nbsp;&nbsp;
    <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}>" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
	{/if}
	</td></tr>
	<tr><td colspan="2" width="75%">&nbsp; </td></tr>
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
	</form>

	
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
function validate_mail_server(form)
{
	if(form.server.value =='')
	{
		alert("Server Name could not be empty")
			return false;
	}
	return true;
}
</script>
{/literal}
