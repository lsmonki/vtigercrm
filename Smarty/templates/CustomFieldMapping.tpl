{*
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/ *}
<script language="JavaScript" type="text/javascript" src="include/js/customview.js"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr>
		{include file='SettingsMenu.tpl'}
		<td width="75%" valign="top">
		<table width="99%" border="0" cellpadding="0" cellspacing="0" align="center">
			<tr>
				<td class="showPanelBg" valign="top" style="padding-left:20px; "><br />
				<span class="lvtHeaderText">{$MOD.LBL_SETTINGS} &gt; {$MOD.LBL_STUDIO} &gt; {$MOD.LBL_CUSTOM_FIELD_SETTINGS} </span>
				<hr noshade="noshade" size="1" />
				</td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				</td>
				<form action="index.php?module=Settings&action=SaveConvertLead" method="post" name="index">
				<table class="leadTable" align="center" cellpadding="5" cellspacing="0" width="95%">
					<tr>
						<td style="border-bottom: 2px dotted rgb(204, 204, 204); padding: 5px;" width="5%">
						<img src="{$IMAGE_PATH}mapping.gif" align="middle" height="48" width="48">
						</td>
						<td style="border-bottom: 2px dotted rgb(170, 170, 170); padding: 5px;">
						<span class="genHeaderGrayBig">{$MOD.LBL_LEAD_MAP_CUSTOM_FIELD}</span><br>
						<span>{$MOD.leadCustomFieldDescription}</span>
						</td>
					</tr>
					<tr><td colspan="2">&nbsp;</td></tr>
					<tr>
						<td align="right">&nbsp;</td>
						<td><b class="lvtHeaderText">{$MOD.LBL_MAPPING_INFO}</b></td>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td>
							<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="95%">
								<tr>
									<td class="lvtCol" width="25%">{$MOD.LBL_LEAD_FIELD}</td>
									<td class="lvtCol" width="25%">{$MOD.LBL_ACCOUNT_FIELD}</td>
									<td class="lvtCol" width="25%">{$MOD.LBL_CONTACT_FIELD}</td>
									<td class="lvtCol" width="25%">{$MOD.LBL_POTENTIAL_FIELD}</td>	
								<tr>
								{foreach key=leadcf item=cfarray from=$CUSTOMFIELDMAPPING}
									<tr class="lvtColData" bgcolor="white">
									<td bgcolor="#e1e1e1">{$leadcf}</td>
									{foreach item=acc_cf key=fldnameacc from=$cfarray.account}
										<td>
										<select name='{$fldnameacc}'>
										<option value='None'>{$APP.LBL_NONE}</option>
										{foreach item=element from=$acc_cf}
										<option value="{$element.fieldid}" {$element.selected}>{$element.fieldlabel}</option>
										{/foreach}
										</td>
										</select>
									{/foreach}
									{foreach item=con_cf key=fldnamecon from=$cfarray.contact}
                                                                                <td>
                                                                                <select name='{$fldnamecon}'>
										<option value='None'>{$APP.LBL_NONE}</option>
										{foreach item=element from=$con_cf}
										<option value="{$element.fieldid}" {$element.selected}>{$element.fieldlabel}</option>
										{/foreach}
										</td>
                                                                                </select>                                                                                             {/foreach}
									{foreach item=pot_cf key=fldnamepot from=$cfarray.potential}
										<td>
										<select name='{$fldnamepot}'>
										<option value='None'>{$APP.LBL_NONE}</option>
										{foreach item=element from=$pot_cf}
										<option value="{$element.fieldid}" {$element.selected}>{$element.fieldlabel}</option>
										{/foreach}
										</td>
                                                                                </select>                                                                                             {/foreach}
									</tr>
								{/foreach}
							</table>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="border-bottom: 2px dotted rgb(170, 170, 170); padding: 5px;">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" style="border-bottom: 2px dotted rgb(170, 170, 170); padding: 5px;" align="center">
							<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" name="save" value=" &nbsp;{$APP.LBL_SAVE_BUTTON_LABEL}&nbsp; " class="classBtn" type="submit" onclick ="return validateCustomFieldAccounts();">&nbsp;
							<input title="{$APP.LBL_CANCEL_BUTTON_LABEL}>" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" name="cancel" value=" {$APP.LBL_CANCEL_BUTTON_LABEL} " onclick = "gotourl('index.php?action=CustomFieldList&module=Settings&fld_module=Leads&parenttab=Settings')"  class="classBtn" type="button">
					  	</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
	</td>
</tr></table>
{include file='SettingsSubMenu.tpl'}			
</td></tr></table>
			
