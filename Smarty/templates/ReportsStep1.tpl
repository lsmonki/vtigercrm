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
		<form name="NewReport" method="POST" ENCTYPE="multipart/form-data" action="index.php">
		<input type="hidden" name='modulesString' value=''/>
		<input type="hidden" name='primarymodule' value="{$PRI_MODULE}"/>
		<input type="hidden" name='secondarymodule' value="{$SEC_MODULE}"/>
		<input type="hidden" name='record' value="{$REPORT_ID}"/>
		<input type="hidden" name='module' value='Reports'/>
		<input type="hidden" name='reload' value='true'/>
		<input type="hidden" name='action' value='Save'/>
		<input type="hidden" name='reportName' value="{$REPORT_NAME}"/>
		<input type="hidden" name='reportDesc' value="{$REPORT_DESC}"/>
		<input type="hidden" name='folder' value="{$FOLDERID}"/>
		<tr>
		<td colspan="2"><img src="{$IMAGE_PATH}report_newHdr.gif" ></td>
		</tr>
		<tr>
		<td background="{$IMAGE_PATH}report_bottom.gif">&nbsp;</td>
		<td align="right" style="padding:10px;" background="{$IMAGE_PATH}report_bottom.gif">
		<input type="button" name="back_rep" value=" &nbsp;&lt;&nbsp;Back&nbsp; " class="classBtn" onClick="changeStepsback1();">&nbsp;
		<input type="button" name="next" value=" &nbsp;Next&nbsp;&rsaquo;&nbsp; " class="classBtn" onClick="changeSteps1()";>
		&nbsp;<input type="button" name="cancel" value=" &nbsp;Cancel&nbsp; " class="classBtn" onClick="self.close();">
		</td>
		</tr>
		<tr>
		<td width="25%" valign="top" bgcolor="#CCCCCC" >
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
			<tr><td class="lvtCol" height="30" style="padding-left:10px;">1. {$MOD.LBL_REPORT_DETAILS}</td></tr>
			<tr><td class="lvtCol" height="30" style="padding-left:10px;">2. {$MOD.LBL_RELATIVE_MODULE} </td></tr>
			<tr><td id="step1label" class="lvtSel" height="30" style="padding-left:10px;">3.{$MOD.LBL_REPORT_TYPE} </td></tr>
			<tr><td id="step2label" class="lvtCol" height="30" style="padding-left:10px;">4.{$MOD.LBL_SELECT_COLUMNS}</td></tr>
			<tr><td id="step3label" class="lvtCol" height="30" style="padding-left:10px;">5. {$MOD.LBL_SPECIFY_GROUPING}</td></tr>
			<tr><td id="step4label" class="lvtCol" height="30" style="padding-left:10px;">6. {$MOD.LBL_CALCULATIONS}</td></tr>
			<tr><td id="step5label" class="lvtCol" height="30" style="padding-left:10px;">7. {$MOD.LBL_FILTERS} </td></tr>
		</table>
		</td>
		<td width="75%" valign="top" style="padding:10px;" bgcolor="#CCCCCC" >
		<div id="step1" style="display:block;">
		<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
			<td colspan="2">
					{php}include("modules/Reports/ReportType.php");{/php}
			</td></tr>		
			</tbody>
		</table>
		</div>	
		<div id="step2" style="display:none;">
		<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
			<td colspan="2">
					{php}include("modules/Reports/ReportColumns.php");{/php}
			</td></tr>
			</tbody>
		</table>
		</div>	
		<div id="step3" style="display:none;">
		<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
			<td colspan="2">
					{php}include("modules/Reports/ReportGrouping.php");{/php}
			</td></tr>
			</tbody>
		</table>
		</div>	
		<div id="step4" style="display:none;">
		<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
			<td colspan="2">
					{php}include("modules/Reports/ReportColumnsTotal.php");{/php}
			</td></tr>
			</tbody>
		</table>
		</div>	
		<div id="step5" style="display:none;">
		<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
			<tbody><tr>
			<td colspan="2">
					{php}include("modules/Reports/ReportFilters.php");{/php}
			</td></tr>
			</tbody>
		</table>
		</div>	

		</td>
		</tr>
		<tr>
		<td background="{$IMAGE_PATH}report_bottom.gif">&nbsp;</td>
		<td align="right" style="padding:10px;" background="{$IMAGE_PATH}report_bottom.gif">
		<input type="button" name="back_rep" value=" &nbsp;&lt;&nbsp;Back&nbsp; " class="classBtn" onClick="changeStepsback1();">&nbsp;
		<input type="button" name="next" value=" &nbsp;Next&nbsp;&rsaquo;&nbsp; " class="classBtn" onClick="changeSteps1()";>
		&nbsp;<input type="button" name="cancel" value=" &nbsp;Cancel&nbsp; " class="classBtn" onClick="self.close();">
		</td>
		</tr>
	</form>	
	</table>
</body>
</html>
{literal}
<script>
setObjects();
hideTabs();
</script>
{/literal}
