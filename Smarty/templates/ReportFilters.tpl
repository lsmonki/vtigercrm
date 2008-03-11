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
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$APP.LBL_JSCALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
{$BLOCKJS_STD}
<table class="small" bgcolor="#ffffff" border="0" cellpadding="5" cellspacing="0" width="100%">
	<tbody><tr>
	<td colspan="2">
	<span class="genHeaderGray">{$MOD.LBL_FILTERS}</span><br>
	{$MOD.LBL_SELECT_FILTERS_TO_STREAMLINE_REPORT_DATA}
	<hr>
	</td>
	</tr>
	<tr><td colspan="2">
	<table class="small" border="0" cellpadding="5" cellspacing="0" width="100%">
		<tbody>
		<tr>
		<td class="detailedViewHeader" colspan="2" width="60%">
		<b>{$MOD.LBL_STANDARD_FILTER}</b>
		</td>
		<td class="detailedViewHeader" width="20%">&nbsp;</td>
		<td class="detailedViewHeader" width="20%">&nbsp;</td>
		</tr>
		<tr>
		<td class="dvtCellLabel">{$MOD.LBL_SF_COLUMNS}:</td>
		<td class="dvtCellLabel">&nbsp;</td>
		<td class="dvtCellLabel">{$MOD.LBL_SF_STARTDATE}:</td>
		<td class="dvtCellLabel">{$MOD.LBL_SF_ENDDATE}:</td>
		</tr>
		<tr>
		<td class="dvtCellInfo" width="60%">
		<select name="stdDateFilterField" class="detailedViewTextBox" onchange='standardFilterDisplay();'>
		{$BLOCK1_STD}
		</select>
		</td>
		<td class="dvtCellInfo" width="25%">
		<select name="stdDateFilter" id="stdDateFilter" onchange='showDateRange( this.options[ this.selectedIndex ].value )' class="repBox">
		{$BLOCKCRITERIA_STD}
		</select>
		</td>
		<td class="dvtCellInfo">
		<input name="startdate" id="jscal_field_date_start" style="border: 1px solid rgb(186, 186, 186);" size="10" maxlength="10" value="{$STARTDATE_STD}" type="text" ><br>
		<img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_date_start" >
		<font size="1"><em old="(yyyy-mm-dd)">({$DATEFORMAT})</em></font>
		<script type="text/javascript">
                                        Calendar.setup ({ldelim}
                                        inputField : "jscal_field_date_start", ifFormat : "{$JS_DATEFORMAT}", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
                                        {rdelim})
                </script>
		</td>
		<td class="dvtCellInfo">
		<input name="enddate" id="jscal_field_date_end" style="border: 1px solid rgb(186, 186, 186);" size="10" maxlength="10" value="{$ENDDATE_STD}" type="text"><br>
                <img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_date_end" >
		<font size="1"><em old="(yyyy-mm-dd)">({$DATEFORMAT})</em></font>
                <script type="text/javascript">
                                        Calendar.setup ({ldelim}
                                        inputField : "jscal_field_date_end", ifFormat : "{$JS_DATEFORMAT}", showsTime : false, button : "jscal_trigger_date_end", singleClick : true, step : 1
                                        {rdelim})
                </script>
		</td>
		</tr>
		<tr>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
		<tr><td colspan="2" class="detailedViewHeader"><b>{$MOD.LBL_ADVANCED_FILTER}</b>
		</td>
		<td class="detailedViewHeader">&nbsp;</td>
		<td class="detailedViewHeader">&nbsp;</td>
		</tr>
		<tr>
		<td colspan="4">
		<ul>
		<li>{$MOD.LBL_AF_HDR2}</li> 
		<li>{$MOD.LBL_AF_HDR3}</li>
		</ul>  
		</td>	
		</tr>
		<tr>
		<td class="dvtCellLabel">
		<select name="fcol1" id="fcol1" onchange="updatefOptions(this, 'fop1');" class="detailedViewTextBox">
		<option value="">{$MOD.LBL_NONE}</option>
        {$BLOCK1}
		</select>
		</td>
		<td class="dvtCellLabel">
		<select name="fop1" id="fop1" class="repBox" style="width:100px;">
		<option value="">{$MOD.LBL_NONE}</option>
		{$FOPTION1}
		</select>
		</td>
		<td class="dvtCellLabel"><input name="fval1" id="fval1" class="repBox" type="text" value="{$VALUE1}"></td>
		<td class="dvtCellLabel">{$MOD.LBL_AND}</td>
		</tr>
		<tr>
		<td class="dvtCellInfo">
		<select name="fcol2" id="fcol2" onchange="updatefOptions(this, 'fop2');" class="detailedViewTextBox">
		<option value="">{$MOD.LBL_NONE}</option>
        {$BLOCK2}
		</select>
		</td>
		<td class="dvtCellInfo">
		<select name="fop2" id="fop2" class="repBox" style="width:100px;">
		<option value="">{$MOD.LBL_NONE}</option>
        {$FOPTION2}
		</select>
		</td>
		<td class="dvtCellInfo"><input name="fval2" id="fval2" class="repBox" type="text" value="{$VALUE2}"></td>
		<td class="dvtCellInfo">{$MOD.LBL_AND}</td>
		</tr>
		<tr>
		<td class="dvtCellLabel">
		<select name="fcol3" id="fcol3" onchange="updatefOptions(this, 'fop3');" class="detailedViewTextBox">
		<option value="">{$MOD.LBL_NONE}</option>
		{$BLOCK3}
		</select>
		</td>
		<td class="dvtCellLabel">
		<select name="fop3" id="fop3" class="repBox" style="width:100px;">
		<option value="">{$MOD.LBL_NONE}</option>
		{$FOPTION3}
		</select>
		</td>
		<td class="dvtCellLabel"><input name="fval3" id="fval3" class="repBox" type="text" value="{$VALUE3}"></td>
		<td class="dvtCellLabel">{$MOD.LBL_AND}</td>
		</tr>
		<tr>
		<td class="dvtCellInfo">
		<select name="fcol4" id="fcol4" onchange="updatefOptions(this, 'fop4');" class="detailedViewTextBox">
		<option value="">{$MOD.LBL_NONE}</option>
		{$BLOCK4}
		</select>
		</td>
		<td class="dvtCellInfo">
		<select name="fop4" id="fop4" class="repBox" style="width:100px;">
		<option value="">{$MOD.LBL_NONE}</option>
		{$FOPTION4}
		</select>
		</td>
		<td class="dvtCellInfo"><input name="fval4" id="fval4" class="repBox" type="text" value="{$VALUE4}"></td>
		<td class="dvtCellInfo">{$MOD.LBL_AND}</td>
		</tr>
		<tr>
		<td class="dvtCellLabel">
		<select name="fcol5" id="fcol5" onchange="updatefOptions(this, 'fop5');" class="detailedViewTextBox">
		<option value="">{$MOD.LBL_NONE}</option>
		{$BLOCK5}		
		</select>
		</td>
		<td class="dvtCellLabel">
		<select name="fop5" id="fop5" class="repBox" style="width:100px;">
		<option value="">{$MOD.LBL_NONE}</option>
		{$FOPTION5}
		</select>
		</td>
		<td class="dvtCellLabel"><input name="fval5" id="fval5" class="repBox" type="text" value="{$VALUE5}"></td>
		<td class="dvtCellLabel">&nbsp;</td>
		</tr>
		</tbody>
	</table>
	</td></tr>
	<tr><td colspan="2" height="50">&nbsp;</td></tr>
	</tbody>
</table>
{literal}
<script>    
    var filter = document.NewReport.stdDateFilter.options[document.NewReport.stdDateFilter.selectedIndex].value
    if( filter != "custom" )
    {
        showDateRange( filter );
    }
</script>
<script>
for(var i=1;i<=5;i++)
{
	var obj=document.getElementById("fcol"+i);
	if(obj.selectedIndex != 0)
		updatefOptions(obj, 'fop'+i);
}
</script>

<script>
// If current user has no access to date fields, we should disable selection
// Fix for: #4670
standardFilterDisplay();
</script>

{/literal}
