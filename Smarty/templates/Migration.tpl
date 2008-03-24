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
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>

<form name="Migration" method="POST" action="index.php" enctype="multipart/form-data">
	<input type="hidden" name="parenttab" value="Settings">
	<input type="hidden" name="module" value="Migration">
	<input type="hidden" name="action" value="MigrationStep1">

	<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%" class="small">
	   <tr>
		<td class="showPanelBg" valign="top" width="95%"  style="padding-left:20px; "><br />
			<span class="lvtHeaderText"> {$MOD.LBL_SETTINGS} &gt; {$MOD.LBL_UPGRADE_VTIGER}</span>
			<hr noshade="noshade" size="1" />
		</td>
		<td width="5%" class="showPanelBg">&nbsp;</td>
	   </tr>
	   <tr>
		<td width="98%" style="padding-left:20px;" valign="top">
			<!-- module Select Table -->
			<table width="95%"  border="0" cellspacing="0" cellpadding="0" align="center" class="mailClient">
			   <tr>
				<td class="mailClientBg" width="7"></td>
				<td class="mailClientBg" style="padding-left:10px;padding-top:10px;vertical-align:top;">
					<table width="100%"  border="0" cellpadding="5" cellspacing="0" class="small">
					   <tr>
						<td width="10%"><img src="{$IMAGE_PATH}migrate.gif" align="absmiddle"/></td>
						<td width="90%">
							<span class="genHeaderBig">{$MOD.LBL_UPGRADE_VTIGER}</span><br />
							({$MOD.LBL_UPGRADE_FROM_VTIGER_5X})
						</td>
					   </tr>
					   <tr>
						<td colspan="2" class="hdrNameBg">
							<span class="genHeaderGray">{$MOD.LBL_STEP} 1 : </span>
					  		<span class="genHeaderSmall">{$MOD.LBL_SELECT_SOURCE}</span><br />
							{$MOD.LBL_PATCH_OR_MIGRATION}<br /><br />
						</td>
					   </tr>
					   <tr bgcolor="#FFFFFF">
						<td align="right" valign="top">
							<input type="radio" name="radio" id="migration" value="migration" onclick="this.form.action.value='MigrationStep1'; showSource()" checked/>
						</td>
						<td>
							<b>Migration from vtiger 4.2.x (4.2Patch2/4.2.3/4.2.4) to Current Version ({$CURRENT_VERSION})</b><br /><br />
							<b>{$MOD.LBL_NOTE_TITLE}</b> Used to migrate 4.2Patch2/4.2.3/4.2.4 to Current ({$CURRENT_VERSION}) Version. This option will need source ie., 4.2.x database details which will be get in next page
						</td>
					   </tr>
					   <tr><td colspan="2" bgcolor="#FFFFFF" height="10"></td></tr>
					   <tr bgcolor="#FFFFFF">
						<td align="right" valign="top">
							<input type="radio" name="radio" id="patch" value="patch" onclick="this.form.action.value='PatchApply'; showSource();"/>
						</td>
						<td>
							<b>Upgrade my vtiger 5.x version to Current Version ({$CURRENT_VERSION})</b><br /><br />
							<b>{$MOD.LBL_NOTE_TITLE}</b> This option will apply the dbchanges from Source 5.x version to the Current ({$CURRENT_VERSION}) version
						</td>
					   </tr>
					   <tr><td colspan="2" bgcolor="#FFFFFF" height="10"></td></tr>
					   <tr><td colspan="2" bgcolor="#FFFFFF" height="10"></td></tr>
	
					   <tr><td colspan="2" height="10"></td></tr>
					   <tr>
						<td colspan="2" class="hdrNameBg">
	
							<!-- Source Version Combo box div - starts -->
							<div id="mnuTab" style="display:none">
							<table width="100%" border="0" cellpadding="5" cellspacing="0" class="small">
							   <tr >
								<td colspan="2">
									<span class="genHeaderGray">{$MOD.LBL_STEP} 2 : </span>
									<span class="genHeaderSmall">Select Source Version</span><br />(Database Changes between this source version to current version will be applied)<br />
								</td>
							   </tr>
							   <tr>
								<td width="10%">&nbsp;</td>
								<td width="90%">
									Source vtiger Version
									{$SOURCE_VERSION}
								</td>
							   </tr>
							   <tr><td colspan="2" height="10"></td></tr>
							</table>
							</div>
							<!-- Source Version Combo box div - ends -->


		
						</td>
					   </tr>

					   <tr>
						<td colspan="2" style="padding:10px;" align="center">
							<input type="submit" name="migrate" value="  {$MOD.LBL_MIGRATE_BUTTON}  "  class="crmbutton small save" onclick="return getConfirmation('{$CURRENT_VERSION}');"/>
							&nbsp;<input type="button" name="cancel" value=" &nbsp;{$MOD.LBL_CANCEL_BUTTON}&nbsp; "  class="crmbutton small cancel" onClick="window.history.back();"/>
 						</td>
					   </tr>
					</table>
				</td>
				<td class="mailClientBg" width="8"></td>
			   </tr>
			  </table>
			<br />
		</td>
		<td>&nbsp;</td>
	   </tr>
	</table>
</form>

<script language="javascript" type="text/javascript">
{literal}
	function showSource()
	{
		if(document.getElementById("migration").checked == true)
			document.getElementById('mnuTab').style.display = 'none';
		else
			document.getElementById('mnuTab').style.display = 'block';
	}
	function getConfirmation(current_version)
	{
		if(document.getElementById("migration").checked == true)
			return true;
		else
		{
			var tagName = document.getElementById('source_version');
			var source_version = tagName.options[tagName.selectedIndex].text;
			{/literal}
                        if(confirm("{$APP.DATABASE_CHANGE_CONFIRMATION}"+source_version+"{$APP.TO}"+current_version))
                        {literal}
				return true;
			else
				return false;
		}
	}
{/literal}
</script>
