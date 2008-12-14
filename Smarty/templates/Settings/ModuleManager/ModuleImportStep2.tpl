<div id="vtlib_modulemanager_import" style="display:block;position:absolute;width:500px;"></div>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tr>
	<td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
    <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
    <br>

	<div align=center>
		{include file='SetMenu.tpl'}
		
		<table class="settingsSelUITopLine" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tr>
			<td rowspan="2" valign="top" width="50"><img src="{$IMAGE_PATH}vtlib_modmng.gif" alt="{$MOD.LBL_USERS}" title="{$MOD.LBL_USERS}" border="0" height="48" width="48"></td>
			<td class="heading2" valign="bottom"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> &gt; {$MOD.VTLIB_LBL_MODULE_MANAGER} &gt; {$APP.LBL_IMPORT}</b></td>
		</tr>

		<tr>
			<td class="small" valign="top">{$MOD.VTLIB_LBL_MODULE_MANAGER_DESCRIPTION}</td>
		</tr>
		</table>
				
		<br>
		<table border="0" cellpadding="10" cellspacing="0" width="100%">
		<tr>
			<td>
				<div id="vtlib_modulemanager_import_div">
                	<form method="POST" action="index.php">
						{if $MODULEIMPORT_FAILED neq ''}
							<table class='tableHeading' cellpadding=5 cellspacing=0 border=0 width=100%>
							<tr>
								<td class='big' colspan=2><b>Import Failure</b></td>
							</tr>
							</table>
							<table cellpadding=5 cellspacing=0 border=0 width=80%>
							<tr valign=top>
								<td class='cellText small'>
									{if $MODULEIMPORT_FILE_INVALID eq "true"}
										<font color=red><b>Invalid file</b></font> provided for module import! Try Again.
									{else}
										We were <font color=red>unable to upload</font> the zipfile on the server! Try Again.
									{/if}
								</td>
							</tr>
							</table>
							<table class='tableHeading' cellpadding=5 cellspacing=0 border=0 width=100%>
							<tr valign=top>
								<td class='cellText small' colspan=2 align=right>
									<input type="hidden" name="module" value="Settings">
									<input type="hidden" name="action" value="ModuleManager">
									<input type="hidden" name="parenttab" value="Settings">						
									<input type="submit" class="crmbutton small edit" value="{$APP.LBL_FINISH}">
								</td>
							</tr>
							</table>
						{else}
							<table class='tableHeading' cellpadding=5 cellspacing=0 border=0 width=100%>
							<tr>
								<td class='big' colspan=2><b>Verify Import Details</b></td>
							</tr>
							</table>
							<table cellpadding=5 cellspacing=0 border=0 width=100%>
							<tr valign=top>
								<td class='cellLabel small' width=20%>
									<b>Module Name</b>
								</td>
								<td class='cellText small'>
									{$MODULEIMPORT_NAME}
									{if $MODULEIMPORT_EXISTS eq 'true'} <font color=red><b>Exists</b></font> {/if}
								</td>
							</tr>
							{if $MODULEIMPORT_DIR}
							<tr valign=top>
								<td class='cellLabel small' width=20%>
									<b>Module Directory</b>
								</td>
								<td class='cellText small'>
									{$MODULEIMPORT_DIR}
									{if $MODULEIMPORT_DIR_EXISTS eq 'true'} <font color=red><b>Exists</b></font> 
										{* -- Avoiding File Overwrite
										 <br> Overwrite existing files? <input type="checkbox" name="module_dir_overwrite" value="true"> 
										-- *}
									{/if}
								</td>
							</tr>
							{/if}
							<tr valign=top>
								<td class='cellLabel small' width=20%>
									<b>Req. vtiger version</b>
								</td>
								<td class='cellText small'>
									{$MODULEIMPORT_DEP_VTVERSION}
								</td>
							</tr>

							{assign var="need_license_agreement" value="false"}

							{if $MODULEIMPORT_LICENSE}
							{assign var="need_license_agreement" value="true"}
							<tr valign=top>
								<td class='cellLabel small' width=20%>
									<b>License</b>
								</td>
								<td class='cellText small'>
									<textarea readonly class='small' style="background-color: #F5F5F5; border: 0; height: 150px; font: 10px 'Lucida Console', 'Courier New', Arial, sans-serif;">{$MODULEIMPORT_LICENSE}</textarea><br>
									{literal}
									<input type="checkbox" onclick="if(this.form.yesbutton){if(this.checked){this.form.yesbutton.disabled=false;}else{this.form.yesbutton.disabled=true;}}"> I accept the license agreement.
									{/literal}
								</td>
							</tr>
							{/if}
							</table>
							<table class='tableHeading' cellpadding=5 cellspacing=0 border=0 width=100%>
							<tr valign=top>
								<td class='cellText small' colspan=2 align=right>
									<input type="hidden" name="module" value="Settings">
									<input type="hidden" name="action" value="ModuleManager">
									<input type="hidden" name="parenttab" value="Settings">
									<input type="hidden" name="module_import_file" value="{$MODULEIMPORT_FILE}">
									<input type="hidden" name="module_import_type" value="{$MODULEIMPORT_TYPE}">
									<input type="hidden" name="module_import" value="Step3">
									<input type="hidden" name="module_import_cancel" value="false">

									{if $MODULEIMPORT_EXISTS eq 'true' || $MODULEIMPORT_DIR_EXISTS eq 'true'}										
										<input type="submit" class="crmbutton small delete" value="{$APP.LBL_CANCEL_BUTTON_LABEL}" 
												onclick="this.form.module_import.value=''; this.form.module_import_cancel.value='true';">
									{else}
										{literal}Do you want to proceed with the import?{/literal}
										<input type="submit" class="crmbutton small edit" value="{$MOD.LBL_YES}" 
											{if $need_license_agreement eq 'true'} disabled=true {/if}	name="yesbutton">
										<input type="submit" class="crmbutton small delete" value="{$MOD.LBL_NO}" 
												onclick="this.form.module_import.value=''; this.form.module_import_cancel.value='true';">
									{/if}
								</td>
							</tr>
							</table>
						{/if}
					</form>
                </div>
			</td>
		</tr>
		</table>
		<!-- End of Display -->
		
		</td>
        </tr>
        </table>
        </td>
        </tr>
        </table>
   </div>

        </td>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
	</tr>
</table>
<br>
