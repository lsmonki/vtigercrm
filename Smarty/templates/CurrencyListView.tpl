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
<form action="index.php">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="CurrencyEditView">
<input type="hidden" name="parenttab" value="Settings">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
	<tr>
		<td class="showPanelBg" valign="top" width="90%"  style="padding-left:20px; "><br />
			<span class="lvtHeaderText">{$MOD.LBL_MODULE_NAME} &gt;{$MOD.LBL_CONFIGURATION} &gt; {$MOD.LBL_CURRENCY_CONFIG}</span>
			<hr noshade="noshade" size="1" />
		</td>
		<td width="10%" class="showPanelBg">&nbsp;</td>
	</tr>
	<tr>
		<td width="90%" style="padding-left:20px;" valign="top">
			<input type="submit" name="new" value="{$MOD.LBL_NEW_CURRENCY}" class="classBtn" /><br /><br />
			<table style="background-color: rgb(204, 204, 204);" class="small" border="0" cellpadding="5" cellspacing="1" width="100%">
			<tbody>
				<tr>
					<td class="lvtCol" width="25%">{$MOD.LBL_CURRENCY_NAME}</td>
			                <td class="lvtCol" width="20%">{$MOD.LBL_CURRENCY_CODE}</td>
				        <td class="lvtCol" width="5%">{$MOD.LBL_CURRENCY_SYMBOL}</td>
			                <td class="lvtCol" width="20%">{$MOD.LBL_CURRENCY_CRATE}</td>
					<td class="lvtCol" width="15%">{$MOD.LBL_CURRENCY_STATUS}</td>
					<td class="lvtCol" width="15%">{$MOD.LBL_CURRENCY_TOOL}</td>			                      </tr>
				{foreach item=currency_array key=id from=$CURRENCY_LIST}
					<tr class="lvtColData" onmouseover="this.className='lvtColDataHover'" onmouseout="this.className='lvtColData'" bgcolor="white">
					{foreach item=currencyvalues from=$currency_array}
						<td nowrap>{$currencyvalues}</td>
					{/foreach}
					</tr>
				{/foreach}
			</tbody>
		        </table>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
</form>
