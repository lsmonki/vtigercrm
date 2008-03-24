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
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
<br>
	<div align=center>
			{include file="SetMenu.tpl"}
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<form action="index.php" method="post" name="tandc">
				<input type="hidden" name="server_type" value="backup">
				<input type="hidden" name="module" value="Settings">
				<input type="hidden" name="action" value="index">
				<input type="hidden" name="bkp_server_mode">
				<input type="hidden" name="server_type" value="backup">
				<input type="hidden" name="parenttab" value="Settings">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{$IMAGE_PATH}backupserver.gif" alt="{$MOD.LBL_USERS}" width="48" height="48" border=0 title="{$MOD.LBL_USERS}"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_BACKUP_SERVER_SETTINGS} </b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_BACKUP_SERVER_DESC} </td>
				</tr>
				</table>
				
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
				<tr>
				<td class="big" height="40px;" width="70%"><strong>{$MOD.LBL_BACKUP_SERVER_SETTINGS}</strong></td>
				<td class="small" align="center" width="30%">&nbsp;
					<span id="view_info" class="crmButton small cancel" style="display:none;"></span>
				</td>
				</tr>
				</table>
				
				<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
				<tr>
				<td class="small" valign=top >
					<table width="100%"  border="0" cellspacing="0" cellpadding="5">
					<tr>
					<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_ENABLE} {$MOD.LBL_BACKUP_SERVER_SETTINGS}</strong></td>
					<td width="80%" class="small cellText">
					{if $BACKUP_STATUS eq 'enabled'}
						<input type="checkbox" checked name="enable_backup" onclick="backupenabled(this)"></input>
					{else}
						<input type="checkbox" name="enable_backup" onclick="backupenabled(this)"></input>
					{/if}
					</td>
					</tr>
					</table>
					<table width="100%" border="0" cellspacing="0" cellpadding="5">
                                        <tr>
                                        <td class="small" valign="top">
                                                <br>{$MOD.LBL_BACKUP_DESC}
                                        </td>
                                        </tr>
                                        </table>
				</td>
				</tr>
				<tr>
				<td class="small" valign=top >
				<br>
				{if $BACKUP_STATUS eq 'enabled'}
					<div id='bckcontents' style="display:block;">
				{else}
					<div id='bckcontents' style="display:none;">
				{/if}
					<table border=0 cellspacing=0 cellpadding=10 width=100% >
					<tr>
					<td>
				
						<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
						<tr>
							<td class="big"><strong>{$MOD.LBL_BACKUP_SERVER_SETTINGS} ({$MOD.LBL_FTP})<br>{$ERROR_MSG}</strong></td>
							{if $BKP_SERVER_MODE neq 'edit'}
							<td class="small" align=right>
								<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmButton small edit" onclick="this.form.action.value='BackupServerConfig';this.form.bkp_server_mode.value='edit'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">&nbsp;
								<input title="{$MOD.LBL_CLEAR_DATA}" accessKey="{$MOD.LBL_CLEAR_DATA}" class="crmButton small cancel" onclick="clearBackupServer();" type="button" name="Clear" value="{$MOD.LBL_CLEAR_DATA}">
							</td>
							{else}
							<td class="small" align=right>
								<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmButton small save" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="this.form.action.value='Save'; return validate()">&nbsp;&nbsp;
							    <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmButton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
							</td>
							{/if}
						</tr>
						</table>
					</td>
					</tr>
					<tr>
					<td>
						<div id="BackupServerContents">
							{include file="Settings/BackupServerContents.tpl"}
						</div>
					</td>
					</tr>
					</table>
					</div>
				</td>
				</tr>
				</table>
		</td>
		</tr>
		</form>
		</table>
		</td></tr>
		</table>	
	</div>
	</td>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
</tr>
</tbody>
</table>
{literal}
<script>
function validate() {
	if (!emptyCheck("server","ftp Server Name","text")) return false
		if (!emptyCheck("server_username","ftp User Name","text")) return false
				if (!emptyCheck("server_password","ftp Password","text")) return false
			return true;

}

function clearBackupServer()
{
new Ajax.Request('index.php',
                        {queue: {position: 'end', scope: 'command'},
                                method: 'post',
                                postBody: 'module=Settings&action=SettingsAjax&ajax=true&file=BackupServerConfig&opmode=del',
                                onComplete: function(response) {
                                $("BackupServerContents").innerHTML=response.responseText;
                                }
                        }
                );	
}

function backupenabled(ochkbox)
{
	if(ochkbox.checked == true)
	{
		$('bckcontents').style.display='block';
		var status='enabled';
		$('view_info').innerHTML = 'Backup Enabled';
		$('view_info').style.display = 'block';		
		
			
	}
	else
	{
		$('bckcontents').style.display='none';
		var status = 'disabled';	
	     	$('view_info').innerHTML = 'Backup Disabled';
	     	$('view_info').style.display = 'block';		
	}
             $("status").style.display="block";
	     new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Settings&action=SettingsAjax&file=SaveEnableBackup&ajax=true&enable_backup='+status,
                        onComplete: function(response) {
                                $("status").style.display="none";
                        }
                }
        );
			
	setTimeout("hide('view_info')",3000);
}

</script>
{/literal}
