<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>vtiger CRM - Create Report</title>
	<link href="themes/blue/style.css" rel="stylesheet" type="text/css">
	<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
	<script language="JavaScript" type="text/javascript" src="modules/Reports/Report.js"></script>
</head>
<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0" marginheight="0" marginwidth="0">
	<table width="640" border="0" cellspacing="0" cellpadding="0" align="left">
		<form name="NewRep" method="POST" ENCTYPE="multipart/form-data" action="index.php">
		<input type="hidden" name="module" value="Reports">
		<input type="hidden" name="primarymodule" value="{$REP_MODULE}">
		<input type="hidden" name="file" value="NewReport1">
		<input type="hidden" name="action" value="ReportsAjax">
		<tr>
		<td colspan="2"><img src="{$IMAGE_PATH}report_newHdr.gif" ></td>
		</tr>
		<tr>
		<td width="25%" valign="top" bgcolor="#CCCCCC" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
			<tr><td id="step1label" class="lvtSel" height="30" style="padding-left:10px;">1. Report Details</td></tr>
			<tr><td id="step2label" class="lvtCol" height="30" style="padding-left:10px;">2. Relative Module </td></tr>
			<tr><td class="lvtCol" height="30" style="padding-left:10px;">3. Report Type </td></tr>
			<tr><td class="lvtCol" height="30" style="padding-left:10px;">4. Select Columns</td></tr>
			<tr><td class="lvtCol" height="30" style="padding-left:10px;">5. Specify Grouping</td></tr>
			<tr><td class="lvtCol" height="30" style="padding-left:10px;">6. Calculations</td></tr>
			<tr><td class="lvtCol" height="30" style="padding-left:10px;">7. Filters </td></tr>
		</table>
		</td>
		<td width="75%" valign="top" style="padding:10px;" bgcolor="#CCCCCC" >
		<div id="step1" style="display:block;">
		<table width="100%" border="0" cellpadding="5" cellspacing="0" bgcolor="#FFFFFF" class="small">
			<tr>
			<td colspan="2">
			<span class="genHeaderGray">Report Details</span><br>
			Type the Name &amp; Description for the Report<hr>
			</td>
			</tr>
			<tr>
			<td width="25%" align="right" style="padding-right:5px;"><b>Report Name : </b></td>
			<td width="75%" align="left" style="padding-left:5px;">
			<input type="text" name="reportname" class="txtBox">
			</td>
			</tr>
			<tr>
			<td width="25%" align="right" style="padding-right:5px;"><b>Report Folder : </b></td>
			<td width="75%" align="left" style="padding-left:5px;">
			<select name="reportfolder" class="txtBox">
			{foreach item=folder from=$REP_FOLDERS}
			<option value="{$folder.id}">{$folder.name}</option>
			{/foreach}
			</select>
			</td>
			</tr>
			<tr>
			<td align="right" style="padding-right:5px;" valign="top"><b>Description : </b></td>
			<td align="left" style="padding-left:5px;">
			<textarea name="reportdes" class="txtBox" rows="5"></textarea>
			</td>
			</tr>
			<tr>
			<td colspan="2" height="287">&nbsp;</td>
			</tr>
		</table>
		</div>
		<div id="step2" style="display:none;">
		<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
			<td colspan="2">
			<span class="genHeaderGray">Report Module</span><br>
			Select a relative module for this report<hr>
			</td>
			</tr>
			<tr>
			<td style="padding-right: 5px;" align="right" width="25%"><b>Module : </b></td>
			<td style="padding-left: 5px;" align="left" width="75%">
			<select name="secondarymodule" class="txtBox">
			<option value="">--None--</option>
			{foreach item=relmod from=$RELATEDMODULES}
			<option value="{$relmod}">{$relmod}</option>
			{/foreach}
			</select>
			</td>
			</tr>
			<tr><td colspan="2" height="409">&nbsp;</td></tr>
			</tbody>
		</table>
		</div>
		</td>
		</tr>
		<tr>
		<td background="{$IMAGE_PATH}report_bottom.gif">&nbsp;</td>
		<td align="right" style="padding:10px;" background="{$IMAGE_PATH}report_bottom.gif">
		<input type="button" name="back_rep" value=" &nbsp;&lt;&nbsp;Back&nbsp; " disabled="disabled" class="classBtn" onClick="changeStepsback();">
		&nbsp;<input type="button" name="next" value=" &nbsp;Next&nbsp;&rsaquo;&nbsp; " onClick="changeSteps();" class="classBtn">
		&nbsp;<input type="button" name="cancel" value=" &nbsp;Cancel&nbsp; " class="classBtn" onClick="fnClose()">
		</td>
		</tr>
	</form>	
	</table>
</body>
</html>
{literal}
<script>
</script>
{/literal}
