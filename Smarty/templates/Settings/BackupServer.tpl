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
					<td width=50 rowspan=2 valign=top><img src="{$IMAGE_PATH}backupserver.gif" alt="Users" width="48" height="48" border=0 title="Users"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_BACKUP_SERVER_SETTINGS} </b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_BACKUP_SERVER_DESC} </td>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td>
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong>{$MOD.LBL_BACKUP_SERVER_SETTINGS} ({$MOD.LBL_FTP})<br>{$ERROR_MSG}</strong></td>
						{if $BKP_SERVER_MODE neq 'edit'}
						<td class="small" align=right>
							<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="crmButton small edit" onclick="this.form.action.value='BackupServerConfig';this.form.bkp_server_mode.value='edit'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}">
						</td>
						{else}
						<td class="small" align=right>
							<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmButton small save" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="this.form.action.value='Save'; return validate()">&nbsp;&nbsp;
						    <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmButton small cancel" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
						</td>
						{/if}
					</tr>
					</table>
				
			{if $BKP_SERVER_MODE eq 'edit'}	
			<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
			<tr>
      			    <td class="small" valign=top ><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <td width="20%" nowrap class="small cellLabel"><font color="red">*</font><strong>{$MOD.LBL_SERVER_ADDRESS} </strong></td>
                            <td width="80%" class="small cellText">
				<input type="text" class="detailedViewTextBox small" value="{$FTPSERVER}" name="server"></strong>
			    </td>
                          </tr>
                          <tr valign="top">

                            <td nowrap class="small cellLabel"><font color="red">*</font><strong>{$MOD.LBL_USERNAME}</strong></td>
                            <td class="small cellText">
				<input type="text" class="detailedViewTextBox small" value="{$FTPUSER}" name="server_username">
			    </td>
                          </tr>
                          <tr>
                            <td nowrap class="small cellLabel"><font color="red">*</font><strong>{$MOD.LBL_PASWRD}</strong></td>
                            <td class="small cellText">
				<input type="password" class="detailedViewTextBox small" value="{$FTPPASSWORD}" name="server_password">
			    </td>
                          </tr>
                        </table>
			{else}
			<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
			<tr>
	         	    <td class="small" valign=top ><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_SERVER_ADDRESS} </strong></td>
                            <td width="80%" class="small cellText"><strong>{$FTPSERVER}</strong></td>
                        </tr>
                        <tr valign="top">
                            <td nowrap class="small cellLabel"><strong>{$MOD.LBL_USERNAME}</strong></td>
                            <td class="small cellText">{$FTPUSER}</td>
                        </tr>
                        <tr>
                            <td nowrap class="small cellLabel"><strong>{$MOD.LBL_PASWRD}</strong></td>
                            <td class="small cellText">
				{if $FTPPASSWORD neq ''}
				******
				{/if}&nbsp;
			    </td>
                        </tr>
                        </table>
					
			{/if}				
						</td>
					  </tr>
					</table>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr>
					  <td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td>
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
	</form>
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
</script>
{/literal}
