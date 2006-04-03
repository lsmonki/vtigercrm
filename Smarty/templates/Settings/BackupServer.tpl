<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<form action="index.php" method="post" name="tandc">
<input type="hidden" name="server_type" value="backup">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="index">
<input type="hidden" name="bkp_server_mode">
<input type="hidden" name="server_type" value="backup">
<input type="hidden" name="parenttab" value="Settings">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_CONFIGURATION} > {$MOD.LBL_BACKUP_SERVER_CONFIG}</b></span>
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
	<td style="padding-left: 10px; padding-top: 10px; vertical-align: top;" bgcolor="#ececec">
	<table border="0" cellpadding="10" cellspacing="0" width="100%">
	<tbody><tr>
	<td rowspan="6" style="background-image: url(include/images/noimage.gif); background-position: center; background-repeat: no-repeat;" bgcolor="#ffffff" width="25%">&nbsp;</td>
	{if $BKP_SERVER_MODE neq 'edit'}
	<td colspan="2" style="padding-top: 0px;" align="right" width="75%">
	<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='BackupServerConfig';this.form.bkp_server_mode.value='edit'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}"><br><hr>
	</td>
	{else}	
	<td colspan="2" style="padding-top: 0px;" align="left" width="75%">
	<b>{$MOD.LBL_BACKUP_SERVER_INFO}</b>&nbsp;{$ERROR_MSG}<br><hr>
	</td>
	{/if}
	</tr>
	
	{if $BKP_SERVER_MODE eq 'edit'}
	<tr>
		<td><font color="red">*</font>{$MOD.LBL_FTP_SERVER_NAME}:</td>
		<td><input class="dataInput" type="text" name="server" value="{$FTPSERVER}" size="25" /></td>
	</tr>
	<tr>
		<td><font color="red">*</font>{$MOD.LBL_FTP_USER_NAME}:</td>
		<td><input class="dataInput" type="text" name="server_username" value="{$FTPUSER}" size="25" /></td>
	</tr>
	<tr>
		<td><font color="red">*</font>{$MOD.LBL_FTP_PASSWORD}:</td>
		<td><input class="dataInput" type="password" name="server_password" value="{$FTPPASSWORD}" size="25" /></td>
	</tr>
	{else}
	<tr>
		<td width=40%>{$MOD.LBL_FTP_SERVER_NAME}:</td>
		<td>{$FTPSERVER}</td>
	</tr>
	<tr>
		<td>{$MOD.LBL_FTP_USER_NAME}:</td>
		<td>{$FTPUSER}</td>
	</tr>
	<tr>
		<td>{$MOD.LBL_FTP_PASSWORD}:</td>
		<td>
		{if $FTPPASSWORD neq ''}
		******
		{/if}&nbsp;
		</td>
	</tr>
	{/if}

	<tr><td colspan="2" width="75%">&nbsp; </td></tr>
	<tr><td colspan="2" align="center"width="75%"><hr> <br>
	
	{if $BKP_SERVER_MODE eq 'edit'}
	<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="this.form.action.value='Save'; return validate()"">&nbsp;&nbsp;&nbsp;
    <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
	{/if}	

	</td></tr>
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
</form>
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
