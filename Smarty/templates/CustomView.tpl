<!--*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/
-->
<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="modules/CustomView/CustomView.js"></script>
<script language="JavaScript" type="text/javascript" src="include/calculator/calc.js"></script>
<form enctype="multipart/form-data" name="CustomView" method="POST" action="index.php" onsubmit="return mandatoryCheck();">
<input type="hidden" name="module" value="CustomView">
<input type="hidden" name="action" value="Save">
<input type="hidden" name="cvmodule" value="{$CVMODULE}">
<input type="hidden" name="return_module" value="{$RETURN_MODULE}">
<input type="hidden" name="record" value="{$CUSTOMVIEWID}">
<input type="hidden" name="return_action" value="{$RETURN_ACTION}">
<script language="javascript" type="text/javascript">
var typeofdata = new Array();
typeofdata['V'] = ['e','n','s','c','k'];
typeofdata['N'] = ['e','n','l','g','m','h'];
typeofdata['T'] = ['e','n','l','g','m','h'];
typeofdata['I'] = ['e','n','l','g','m','h'];
typeofdata['C'] = ['e','n'];
typeofdata['DT'] = ['e','n'];
typeofdata['D'] = ['e','n'];
var fLabels = new Array();
fLabels['e'] = 'equals';
fLabels['n'] = 'not equal to';
fLabels['s'] = 'starts with';
fLabels['c'] = 'contains';
fLabels['k'] = 'does not contain';
fLabels['l'] = 'less than';
fLabels['g'] = 'greater than';
fLabels['m'] = 'less or equal';
fLabels['h'] = 'greater or equal';
var noneLabel;
function goto_CustomAction(module)
{ldelim}
        document.location.href = "index.php?module="+module+"&action=CustomAction&record={$CUSTOMVIEWID}";
{rdelim}

function mandatoryCheck()
{ldelim}

        var mandatorycheck = false;
        var i,j;
        var manCheck = new Array({$MANDATORYCHECK});
        var showvalues = "{$SHOWVALUES}";
        if(manCheck)
        {ldelim}
                var isError = false;
                var errorMessage = "";
                if (trim(document.CustomView.viewName.value) == "") {ldelim}
                        isError = true;
                        errorMessage += "\nView Name";
                {rdelim}
                // Here we decide whether to submit the form.
                if (isError == true) {ldelim}
                        alert("Missing required fields:" + errorMessage);
                        return false;
                {rdelim}
		
		for(i=1;i<=9;i++)
                {ldelim}
                        var columnvalue = document.getElementById("column"+i).value;
                        if(columnvalue != null)
                        {ldelim}
                                for(j=0;j<manCheck.length;j++)
                                {ldelim}
                                        if(columnvalue == manCheck[j])
                                        {ldelim}
                                                mandatorycheck = true;
                                        {rdelim}
                                {rdelim}
                                if(mandatorycheck == true)
                                {ldelim}
                                        return true;
                                {rdelim}else
                                {ldelim}
                                        mandatorycheck = false;
                                {rdelim}
                        {rdelim}
                {rdelim}
        {rdelim}
        if(mandatorycheck == false)
        {ldelim}
                alert("Must have one Required field to be selected\n"+showvalues);
        {rdelim}
        
        return false;
{rdelim}
</script>

<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
 <tbody><tr>
  <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
  <td class="showPanelBg" valign="top" width="100%">
   <div class="small" style="padding: 20px;">
      <span class="lvtHeaderText">{$MODULE} &gt; New Custom View</span> <br>
      <hr noshade="noshade" size="1">
      <form name="EditView" method="post" enctype="multipart/form-data" action="index.php">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
             <tbody><tr>
              <td align="left" valign="top">
               <table width="100%"  border="0" cellspacing="0" cellpadding="5">
		<tr>
		 <td colspan="4" class="detailedViewHeader"><strong>Details</strong></td>
		</tr>
		<tr>
		 <td class="dvtCellInfo" align="right" width="25%"><span class="style1">*</span>View Name </td>
		 <td class="dvtCellInfo" width="25%">
		  <input class="detailedViewTextBox" type="text" name='viewName' value="{$VIEWNAME}" onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/>
		 </td>
		 <td class="dvtCellInfo" width="25%">
		  <input type="checkbox" name="setDefault" value="checkbox" />{$MOD.LBL_SETDEFAULT}
		 </td>
		 <td class="dvtCellInfo" width="25%">
		  <input type="checkbox" name="setMetrics" value="checkbox" />{$MOD.LBL_LIST_IN_METRICS}
		 </td>
		</tr>
		<tr><td colspan="3">&nbsp;</td></tr>
		<tr>
		 <td colspan="4" class="detailedViewHeader">
		  <b>Select Columns to show in this view </b>
		 </td>
		</tr>
		<tr class="dvtCellLabel">
		  <td><select name="column1" id="column1">
	                <option value="">None</option>
			{foreach item=filteroption key=label from=$CHOOSECOLUMN1}
				<optgroup label="{$label}" class=\"select\" style=\"border:none\">
				{foreach item=text from=$filteroption}
		                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                	        {/foreach}
			{/foreach}
          	        {$CHOOSECOLUMN1}
	              </select></td>
		   <td><select name="column2" id="column2">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN2}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN2}
                      </select></td>
		   <td><select name="column3" id="column3">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN3}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN3}
                      </select></td>
		   <td><select name="column4" id="column4">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN4}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN4}
                      </select></td>
			
		</tr>
		<tr class="dvtCellInfo">
		   <td><select name="column5" id="column5">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN5}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN5}
                      </select></td>
                   <td><select name="column6" id="column6">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN6}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN6}
                      </select></td>
                   <td><select name="column7" id="column7">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN7}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN7}
                      </select></td>
                   <td><select name="column8" id="column8">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN8}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN8}
			</select></td>
		</tr>
		<tr class="dvtCellLabel">
		   <td><select name="column9" id="column9">
                        <option value="">None</option>
                        {foreach item=filteroption key=label from=$CHOOSECOLUMN9}
                                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                                {foreach item=text from=$filteroption}
                                     <option {$text.selected} value={$text.value}>{$text.text}</option>
                                {/foreach}
                        {/foreach}
                        {$CHOOSECOLUMN9}
                        </select></td>
		     <td>&nbsp;</td>
		     <td>&nbsp;</td>
		     <td>&nbsp;</td>
		</tr>	
		{*section name=SelectColumn start=1 loop=4 step=1}
		<tr class="{cycle values="dvtCellLabel,dvtCellInfo"}">
		 {section name=Column start=1 loop=5 step=1}
		<td align="center">
		{math equation="(x-1)*4+ y" x=$smarty.section.SelectColumn.index y=$smarty.section.Column.index}.&nbsp;	
		  <select id="column{math equation="(x-1)*4+ y" x=$smarty.section.SelectColumn.index y=$smarty.section.Column.index}" name ="column{math equation="(x-1)*4+ y" x=$smarty.section.SelectColumn.index y=$smarty.section.Column.index}" class="detailedViewTextBox">
		   <option value="">None</option>
		   {foreach item=filteroption key=label from={$CHOOSECOLUMN|cat: {math equation="(x-1)*4+ y" x=$smarty.section.SelectColumn.index y=$smarty.section.Column.index}}}
		    <optgroup label="{$label}" class=\"select\" style=\"border:none\">
		    {foreach item=text from=$filteroption}
		     <option {$text.selected} value={$text.value}>{$text.text}</option>
		    {/foreach}
		   {/foreach}
		  </select>
		 </td>
		 {/section}
	        </tr>
		{/section*}
		<tr><td colspan="4">&nbsp;</td></tr>
		<tr><td colspan="4"><table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
		<tbody><tr>
		 <td>
		  <table class="small" border="0" cellpadding="3" cellspacing="0" width="100%">
		   <tbody><tr>
		    <td class="dvtTabCache" style="width: 10px;" nowrap="nowrap">&nbsp;</td>
		    <td width="75" align="center" nowrap="nowrap" class="dvtSelectedCell" id="pi" onclick="fnLoadValues('pi','mi','mnuTab','mnuTab2')">
		     <b>Simple Filter</b>
		    </td>
		    <td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap="nowrap" id="mi" onclick="fnLoadValues('mi','pi','mnuTab2','mnuTab')">
		     <b>Advanced Filter </b>
		    </td>
		    <td class="dvtTabCache" nowrap="nowrap">&nbsp;</td>
		   </tr>
		   </tbody>
	          </table>
		 </td>
	        </tr>
		<tr>
		 <td align="left" valign="top">
		  <div id="mnuTab">
		     <table width="100%" cellspacing="0" cellpadding="5" class="dvtContentSpace">
		      <tr><td>&nbsp;</td></tr>
                      <tr><td class="dvtCellInfo">
		       Simple Time Filter allows you to select date based on <b>Account Created Time</b> or 
		       <b>Account Modified Time</b>.<br /><br />
		      </td></tr>
                      <tr><td>
			<table width="75%" border="0" cellpadding="5" cellspacing="0" align="center">
			  <tr><td colspan="2" class="detailedViewHeader"><b>Simple Time Filter</b></td></tr>
			  <tr>
			     <td width="75%" align="right" class="dvtCellLabel">Select a Column :</td>
			     <td width="25%" class="dvtCellInfo">
				<select name="stdDateFilterField" class="select">
				{foreach item=stdfilter from=$STDFILTERCOLUMNS}
					<option {$stdfilter.selected} value={$stdfilter.value}>{$stdfilter.text}</option>	
				{/foreach}
                                </select>
			  </tr>
			  <tr>
			     <td align="right" class="dvtCellLabel">Select Duration :</td>
			     <td class="dvtCellInfo">
			        <select name="stdDateFilter" class="select" onchange='showDateRange(this.options[this.selectedIndex].value )'>
				{foreach item=duration from=$STDFILTERCRITERIA}
					<option {$duration.selected} value={$duration.value}>{$duration.text}</option>
				{/foreach}
				</select>
			     </td>
			  </tr>
			  <tr>
			     <td align="right" class="dvtCellLabel">Start Date :</td>
			     <td width="25%" align=left class="dvtCellInfo">
			     <input name="startdate" id="jscal_field_date_start" type="text" size="10" class="textField" value="{$STARTDATE}">
			     <img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_date_start">
			     <script type="text/javascript">
			  		Calendar.setup ({ldelim}
			 		inputField : "jscal_field_date_start", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
					{rdelim})
			     </script></td>
	            	  </tr>
			  <tr>
			     <td align="right" class="dvtCellLabel">End Date :</td> 
  			     <td width="25%" align=left class="dvtCellInfo">
			     <input name="enddate" id="jscal_field_date_end" type="text" size="10" class="textField" value="{$ENDDATE}">
			     <img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_date_end">
			     <script type="text/javascript">
					Calendar.setup ({ldelim}
					inputField : "jscal_field_date_end", ifFormat : "%Y-%m-%d", showsTime : false, button : "jscal_trigger_date_end", singleClick : true, step : 1
					{rdelim})
			     </script></td>
			  </tr>
			</table>
		      </td></tr>
		      <tr><td>&nbsp;</td></tr>
            </table>
   </div>
   <div id="mnuTab2">
      <table width="100%" cellspacing="0" cellpadding="5" class="dvtContentSpace">
       <tr><td>&nbsp;</td></tr>
       <tr><td class="dvtCellInfo">{$MOD.LBL_AF_HDR1}<br /><br />
	<li style="margin-left:30px;">{$MOD.LBL_AF_HDR2}</li>
	<li style="margin-left:30px;">{$MOD.LBL_AF_HDR3}</li>
	<br /><br />
       </td></tr>
       <tr><td>
	<table width="75%" border="0" cellpadding="5" cellspacing="0" align="center">
	  <tr><td colspan="3" class="detailedViewHeader"><b>Rule</b></td></tr>
	  
	  <tr class="dvtCellLabel">
          <td><select name="fcol1" id="fcol1" onchange="updatefOptions(this, 'fop1');">
              <option value="">None</option>
              {foreach item=filteroption key=label from=$BLOCK1}
                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                {foreach item=text from=$filteroption}
                  <option {$text.selected} value={$text.value}>{$text.text}</option>
                {/foreach}
              {/foreach}
              </select> &nbsp; <select name="fop1" id="fop1">
              <option value="">None</option>
              {foreach item=criteria from=$FOPTION1}
                <option {$criteria.selected} value={$criteria.value}>{$criteria.text}</option>
              {/foreach}
              </select>&nbsp; <input name="fval1" id="fval1" type="text" size=30 maxlength=80 value="{$VALUE1}">
            &nbsp;And</td>
        </tr>
	<tr class="dvtCellInfo">
          <td><select name="fcol2" id="fcol2" onchange="updatefOptions(this, 'fop2');">
              <option value="">None</option>
              {foreach item=filteroption key=label from=$BLOCK2}
                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                {foreach item=text from=$filteroption}
                  <option {$text.selected} value={$text.value}>{$text.text}</option>
                {/foreach}
              {/foreach}
              </select> &nbsp; <select name="fop2" id="fop2">
              <option value="">None</option>
              {foreach item=criteria from=$FOPTION2}
                <option {$criteria.selected} value={$criteria.value}>{$criteria.text}</option>
              {/foreach}
              </select>&nbsp; <input name="fval2" id="fval2" type="text" size=30 maxlength=80 value="{$VALUE2}">
            &nbsp;And</td>
        </tr>
	<tr class="dvtCellLabel">
          <td><select name="fcol3" id="fcol3" onchange="updatefOptions(this, 'fop3');">
              <option value="">None</option>
              {foreach item=filteroption key=label from=$BLOCK3}
                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                {foreach item=text from=$filteroption}
                  <option {$text.selected} value={$text.value}>{$text.text}</option>
                {/foreach}
              {/foreach}
              </select> &nbsp; <select name="fop3" id="fop3">
              <option value="">None</option>
              {foreach item=criteria from=$FOPTION3}
                <option {$criteria.selected} value={$criteria.value}>{$criteria.text}</option>
              {/foreach}
              </select>&nbsp; <input name="fval3" id="fval3" type="text" size=30 maxlength=80 value="{$VALUE3}">
            &nbsp;And</td>
        </tr>
	<tr class="dvtCellInfo">
          <td><select name="fcol4" id="fcol4" onchange="updatefOptions(this, 'fop4');">
              <option value="">None</option>
              {foreach item=filteroption key=label from=$BLOCK4}
                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                {foreach item=text from=$filteroption}
                  <option {$text.selected} value={$text.value}>{$text.text}</option>
                {/foreach}
              {/foreach}
              </select> &nbsp; <select name="fop4" id="fop4">
              <option value="">None</option>
              {foreach item=criteria from=$FOPTION4}
                <option {$criteria.selected} value={$criteria.value}>{$criteria.text}</option>
              {/foreach}
              </select>&nbsp; <input name="fval4" id="fval4" type="text" size=30 maxlength=80 value="{$VALUE4}">
            &nbsp;And</td>
        </tr>
	<tr class="dvtCellLabel">
          <td><select name="fcol5" id="fcol5" onchange="updatefOptions(this, 'fop5');">
              <option value="">None</option>
              {foreach item=filteroption key=label from=$BLOCK5}
                <optgroup label="{$label}" class=\"select\" style=\"border:none\">
                {foreach item=text from=$filteroption}
                  <option {$text.selected} value={$text.value}>{$text.text}</option>
                {/foreach}
              {/foreach}
              </select> &nbsp; <select name="fop5" id="fop5">
              <option value="">None</option>
              {foreach item=criteria from=$FOPTION5}
                <option {$criteria.selected} value={$criteria.value}>{$criteria.text}</option>
              {/foreach}
              </select>&nbsp; <input name="fval5" id="fval5" type="text" size=30 maxlength=80 value="{$VALUE5}">
            &nbsp;</td>
        </tr>

	  {*section name=advancedFilter start=1 loop=6 step=1}
	  <tr class="{cycle values="dvtCellInfo,dvtCellLabel"}">
	    <td align="left" width="33%">
	      <select name="fcol{$smarty.section.advancedFilter.index}" id="fcol{$smarty.section.advancedFilter.index}" onchange="updatefOptions(this, 'fop{$smarty.section.advancedFilter.index}'); class="detailedViewTextBox">
	      <option value="">None</option>
	      {foreach item=filteroption key=label from=$BLOCK}
		<optgroup label="{$label}" class=\"select\" style=\"border:none\">
		{foreach item=text from=$filteroption}
		  <option {$text.selected} value={$text.value}>{$text.text}</option>
		{/foreach}
	      {/foreach}
	      </select>
	    </td>
	    <td align="left" width="33%">
	      <select name="fcol{$smarty.section.advancedFilter.index}" id="fcol{$smarty.section.advancedFilter.index}" class="detailedViewTextBox">
	      <option value="">None</option>
	      {foreach item=criteria from=$FOPTION}
		<option {$criteria.selected} value={$criteria.value}>{$criteria.text}</option>
	      {/foreach}
	      </select>
	    </td>
	    <td width="34%" nowrap><input name="txt" value="" class="detailedViewTextBox" type="text"  onfocus="this.className='detailedViewTextBoxOn'" onblur="this.className='detailedViewTextBox'"/>&nbsp;And</td>
	  </tr>
	  {/section*}
	</table>
       </td></tr>
       <tr><td>&nbsp;</td></tr>
     </table>
   </div>
  </td></tr>
  </table>
  </td></tr>
  <tr><td colspan="4">&nbsp;</td></tr>
  <tr><td colspan="4" style="padding: 5px;">
	<div align="center">
	  <input title="Save [Alt+S]" accesskey="S" class="small"  name="button2" value="{$APP.LBL_SAVE_BUTTON_LABEL}" style="width: 70px;" type="submit" />
	  <input title="Cancel [Alt+X]" accesskey="X" class="small" name="button2" onclick='window.history.back()' value="{$APP.LBL_CANCEL_BUTTON_LABEL}" style="width: 70px;" type="button" />
	</div>
  </td></tr>
  <tr><td colspan="4">&nbsp;</td></tr>
</table>
{$STDFILTER_JAVASCRIPT}
{$JAVASCRIPT}
