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


<br>
<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" class="mailSubHeader">
	<tbody><tr>
	{if $EXPORT_PERMITTED eq 'YES'}
		<td align="left" nowrap ><input class="crmbutton small create" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTPDF_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreatePDF',{$REPORTID}));" title="{$MOD.LBL_EXPORTPDF_BUTTON}"></td>
		<td align="left" nowrap ><input class="crmbutton small create" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTXL_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreateXL',{$REPORTID}));" title="{$MOD.LBL_EXPORTXL_BUTTON}" ></td>
	{/if}
	<td align="left" width="100%"><input name="PrintReport" value="{$MOD.LBL_PRINT_REPORT}" onClick="goToPrintReport({$REPORTID});" class="crmbutton small create" type="button"></td>
	</tr>
	</tbody>
</table>

<table style="border: 1px solid rgb(0, 0, 0);" align="center" cellpadding="0" cellspacing="0" width="100%">
	<tbody><tr>
	<td style="background-repeat: repeat-y;" background="{'report_btn.gif'|@vtiger_imageurl:$THEME}" width="16"></td>

	<td style="padding: 5px;" valign="top">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tbody><tr>
		<td align="left" width="75%">
		<span class="genHeaderGray">
		{if $MOD.$REPORTNAME neq ''}
			{$MOD.$REPORTNAME}
		{else}
			{$REPORTNAME}
		{/if}
		</span><br>
		</td>
		<td align="right" width="25%">
		<span class="genHeaderGray">{$APP.LBL_TOTAL} : {$REPORTHTML.1}  {$APP.LBL_RECORDS}</span>
		</td>
		</tr>
		<tr><td id="report_info" align="left" colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
		<td colspan="2">
		{if $ERROR_MSG eq ''}
		{$REPORTHTML.0}
		{else}
		{$ERROR_MSG}
		{/if}
		</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">{$REPORTTOTHTML}</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		</tbody>
	</table>
	</td>
	<td style="background-repeat: repeat-y;" background="{'report_btn.gif'|@vtiger_imageurl:$THEME}" width="16"></td>
	</tr>

	</tbody>
</table>

<table align="center" border="0" cellpadding="5" cellspacing="0" width="100%" class="mailSubHeader">
	<tbody><tr>
	<td align="left" nowrap ><input class="crmbutton small create" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTPDF_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreatePDF',{$REPORTID}));" title="{$MOD.LBL_EXPORTPDF_BUTTON}"></td>
	<td align="left" nowrap ><input class="crmbutton small create" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTXL_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreateXL',{$REPORTID}));" title="{$MOD.LBL_EXPORTXL_BUTTON}" ></td>
	<td align="left" width=100% nowrap><input name="PrintReport" value="{$MOD.LBL_PRINT_REPORT}" class="crmbutton small create" onClick="goToPrintReport({$REPORTID});" type="button"></td>
	</tr>
	</tbody>
</table>

