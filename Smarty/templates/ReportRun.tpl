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
<br><br>
<script language="JavaScript" type="text/javascript" src="modules/Reports/Report.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
{$BLOCKJS}

<table class="small" align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
	<form name="NewReport" action="index.php" method="POST">
    <input type="hidden" name="booleanoperator" value="5"/>
    <input type="hidden" name="record" value="{$REPORTID}"/>
    <input type="hidden" name="reload" value=""/>    
    <input type="hidden" name="module" value="Reports"/>
    <input type="hidden" name="action" value="SaveAndRun"/>
    <input type="hidden" name="dlgType" value="saveAs"/>
    <input type="hidden" name="reportName"/>
    <input type="hidden" name="folderid" value="{$FOLDERID}"/>
    <input type="hidden" name="reportDesc"/>
    <input type="hidden" name="folder"/>

	<tbody>
	<tr>
	<td style="font-size: 1px;" height="4" width="4"><img src="{$IMAGE_PATH}top_left.gif" align="top" height="4" width="4"></td>
	<td style="border-top: 1px solid rgb(109, 109, 109);" colspan="2"></td>
	<td style="font-size: 1px;" height="4" width="4"><img src="{$IMAGE_PATH}top_right.gif" align="top" height="4" width="4"></td>
	</tr>
	<tr>
	<td style="border-left: 1px solid rgb(109, 109, 109);"></td>
	<td style="padding: 10px; text-align: left;" width="70%">
	<span class="genHeaderGrayBig">{$REPORTNAME}</span>&nbsp;&nbsp;
	( <a href="javascript:;" onClick="editReport('{$REPORTID}');">{$MOD.LBL_CUSTOMIZE_REPORT}</a> )<br>
	<a href="index.php?module=Reports&action=ListView" class="reportMnu" style="border-bottom: 0px solid rgb(0, 0, 0);">&lt;{$MOD.LBL_BACK_TO_REPORTS}</a>
	</td>
	<td style="border-left: 1px solid rgb(109, 109, 109); padding: 10px;" width="30%">
	<b>{$MOD.LBL_SELECT_ANOTHER_REPORT} : </b><br>
	<select name="another_report" class="detailedViewTextBox" onChange="selectReport()">
	{foreach key=report_in_fld_id item=report_in_fld_name from=$REPINFOLDER}
	{if $report_in_fld_id neq $REPORTID}
		<option value={$report_in_fld_id}>{$report_in_fld_name}</option>
	{else}	
		<option value={$report_in_fld_id} "selected">{$report_in_fld_name}</option>
	{/if}
	{/foreach}
	</select>&nbsp;&nbsp;
	</td>
	<td style="border-right: 1px solid rgb(109, 109, 109);"></td>
	</tr>
	<tr>
	<td style="font-size: 1px;" height="4" width="4"><img src="{$IMAGE_PATH}bottom_left.gif" align="absbottom" height="4" width="4"></td>
	<td style="border-bottom: 1px solid rgb(109, 109, 109);" colspan="2"></td>
	<td style="font-size: 1px;" height="4" width="4"><img src="{$IMAGE_PATH}bottom_right.gif" align="absbottom" height="4" width="4"></td>
	</tr>
	</tbody>
</table>
<table class="reportGenarateTable" align="center" cellpadding="0" cellspacing="0" width="90%">
	<tbody><tr><td colspan="4">&nbsp;</td></tr>
	<tr>
	<td>{$MOD.LBL_SELECT_COLUMN} :&nbsp;</td>
	<td>{$MOD.LBL_SELECT_TIME} :&nbsp;</td>
	<td>{$MOD.LBL_SF_STARTDATE} :&nbsp;</td>
	<td>{$MOD.LBL_SF_ENDDATE} :&nbsp;</td>
	</tr>
	<tr>
	<td align="left" width="25%">
	<select name="stdDateFilterField" class="detailedViewTextBox">
    {$BLOCK1}
    </select>
	</td>
	<td width="20%">
	<select name="stdDateFilter" class="importBox" onchange='showDateRange( this.options[ this.selectedIndex ].value )'>
	{$BLOCKCRITERIA}
	</select>
	</td>
	<td width="15%">
		<input name="startdate" id="jscal_field_date_start" type="text" size="10" class="importBox" style="width:70px;" value="{$STARTDATE}">
		<img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_date_start">
	</td>
	<td>
		<input name="enddate" id="jscal_field_date_end" type="text" size="10" class="importBox" style="width:70px;" value="{$ENDDATE}">
		<img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_date_end">
	</td>
	</tr>
	<tr>
	<td align="center" colspan="4"><input name="generatenw" value=" Generate Now " class="classBtn" type="button" onClick="generateReport({$REPORTID});"></td>
	</tr>
	</tbody>
</table>
<br><br>

<div style="display: block;" id="Generate" align="center">
	{include file="ReportRunContents.tpl"}
</div>
<br>
{literal}
<SCRIPT LANGUAGE=JavaScript>
function CrearEnlace(tipo,id){
	return "index.php?module=Reports&action="+tipo+"&record="+id+"&stdDateFilterField="+document.NewReport.stdDateFilterField.options  [document.NewReport.stdDateFilterField.selectedIndex].value+"&stdDateFilter="+document.NewReport.stdDateFilter.options[document.NewReport.stdDateFilter.selectedIndex].value+"&startdate="+document.NewReport.startdate.value+"&enddate="+document.NewReport.enddate.value;

}
function goToURL( url )
{
	document.location.href = url;
}
					
var filter = getObj('stdDateFilter').options[document.NewReport.stdDateFilter.selectedIndex].value
    if( filter != "custom" )
    {
        showDateRange( filter );
    }

Calendar.setup ({inputField : "jscal_field_date_start", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1});
    Calendar.setup ({inputField : "jscal_field_date_end", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger_date_end", singleClick : true, step : 1});
function generateReport(id)
{
	var stdDateFilterFieldvalue = document.NewReport.stdDateFilterField.options  [document.NewReport.stdDateFilterField.selectedIndex].value;
	var stdDateFiltervalue = document.NewReport.stdDateFilter.options[document.NewReport.stdDateFilter.selectedIndex].value;
	var startdatevalue = document.NewReport.startdate.value;
	var enddatevalue = document.NewReport.enddate.value;
	new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'action=ReportsAjax&file=SaveAndRun&mode=ajax&module=Reports&record='+id+'&stdDateFilterField='+stdDateFilterFieldvalue+'&stdDateFilter='+stdDateFiltervalue+'&startdate='+startdatevalue+'&enddate='+enddatevalue,
                        onComplete: function(response) {
				getObj('Generate').innerHTML = response.responseText;
				setTimeout("ReportInfor()",1);
                        }
                }
        );
}
function selectReport()
{
	var id = document.NewReport.another_report.options  [document.NewReport.another_report.selectedIndex].value;
	var folderid = getObj('folderid').value;
	url ='index.php?action=ReportsAjax&file=SaveAndRun&module=Reports&record='+id+'&folderid='+folderid;
	goToURL(url);
}
function ReportInfor()
{
	var stdDateFilterFieldvalue = document.NewReport.stdDateFilterField.options  [document.NewReport.stdDateFilterField.selectedIndex].text;
    var stdDateFiltervalue = document.NewReport.stdDateFilter.options[document.NewReport.stdDateFilter.selectedIndex].text;
	var startdatevalue = document.NewReport.startdate.value;
	var enddatevalue = document.NewReport.enddate.value;
	
	if(startdatevalue != '' && enddatevalue=='')
	{
		var reportinfr = 'Reporting  "'+stdDateFilterFieldvalue+'"   (from  '+startdatevalue+' )';
	}else if(startdatevalue == '' && enddatevalue !='')
	{
		var reportinfr = 'Reporting  "'+stdDateFilterFieldvalue+'"   (  till  '+enddatevalue+')';
	}else if(startdatevalue == '' && enddatevalue =='')
	{
		var reportinfr = 'No filter Selected';
	}else if(startdatevalue != '' && enddatevalue !='')
	{
	var reportinfr = 'Reporting  "'+stdDateFilterFieldvalue+'"  of  "'+stdDateFiltervalue+'"  ( '+startdatevalue+'  to  '+enddatevalue+' )';
	}
	getObj('report_info').innerHTML = reportinfr;
}
ReportInfor();
</SCRIPT>
{/literal}
