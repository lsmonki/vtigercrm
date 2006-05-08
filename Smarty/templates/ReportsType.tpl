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
<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
	<tbody><tr>
	<td colspan="2">
	<span class="genHeaderGray">Report Type </span><br>
	Select  the report	type	below							
	<hr>
	</td>
	</tr>
	<tr>
	<td style="padding-right: 5px;" rowspan="2" align="right" width="25%">
	<img src="{$IMAGE_PATH}tabular.gif" align="absmiddle">
	</td>
	<td style="padding-left: 5px;" align="left" height="50" valign="bottom" width="75%">
	{if $REPORT_TYPE eq 'tabular'}
	<input checked type="radio" name="reportType" value="tabular" onclick="hideTabs()">
	{else}
	<input type="radio" name="reportType" value="tabular" onclick="hideTabs()">
	{/if}
	<b>Tabular Format</b></td>
	</tr><tr><td style="padding-left: 25px;" align="left" valign="top" width="75%">
	Tabular reports are the simplest and fastest way to get a listing of your data.	
	</td>
	</tr>
	<tr><td colspan="2">&nbsp;</td></tr>
	<tr>
	<td style="padding-right: 5px;" rowspan="2" align="right" width="25%">
	<img src="{$IMAGE_PATH}summarize.gif" align="absmiddle">
	</td>
	<td style="padding-left: 5px;" align="left" height="50" valign="bottom" width="75%">
	{if $REPORT_TYPE eq 'summary'}
	<input type="radio" checked name="reportType" value="summary" onclick="hideTabs()">
	{else}
	<input type="radio" name="reportType" value="summary" onclick="hideTabs()">
	{/if}
	<b>Summary Report</b></td>
	</tr><tr><td style="padding-left: 25px;" align="left" valign="top" width="75%">
	Summary reports allow you to view your data along with subtotals and other summary information.	
	</td>
	</tr>
	<tr><td colspan="2" height="183">&nbsp;</td></tr>
	</tbody>
</table>
