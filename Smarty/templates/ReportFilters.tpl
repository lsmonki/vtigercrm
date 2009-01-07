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
		<td colspan="2" width="100%">
			<table width="100%" cellspacing="0" cellpadding="3" border="0" class="small">
				<tbody>
					<tr height="25px">
						<td nowrap="" style="width: 10px;" class="dvtTabCache"> </td>
						<td nowrap="" onclick="fnLoadRepValues('pi','mi','mnuTab','mnuTab2');" id="pi" class="dvtSelectedCell" style="width: 25%;"><!--  -->
						<b>{$MOD.LBL_STANDARD_FILTER}</b>
						</td>
				    	<td nowrap="" align="center"  id="mi"onclick="fnLoadRepValues('mi','pi','mnuTab2','mnuTab');" style="width:25%;" class="dvtUnSelectedCell"><!---->
				     	<b>{$MOD.LBL_ADVANCED_FILTER}</b>
				    	</td>
						<td nowrap="" style="width:50%;" class="dvtUnSelectedCell"> </td>
					</tr>
				</tbody>
			</table>		
		</td>
		</tr>
		<tr>
		<td>
			<div id='mnuTab' style="display:block; height:250px;">
				<table class="small">
					<tr>
						<td class="dvtCellLabel">{$MOD.LBL_SF_COLUMNS}:</td>
						<td class="dvtCellInfo" width="60%">
							<select name="stdDateFilterField" class="detailedViewTextBox" onchange='standardFilterDisplay();'>
							{$BLOCK1_STD}
							</select>
						</td>
					</tr>
					<tr>
						<td class="dvtCellLabel">&nbsp;</td>
						<td class="dvtCellInfo" width="25%">
							<select name="stdDateFilter" id="stdDateFilter" onchange='showDateRange( this.options[ this.selectedIndex ].value )' class="repBox">
							{$BLOCKCRITERIA_STD}
							</select>
						</td>
					</tr>
					<tr>
						<td class="dvtCellLabel">{$MOD.LBL_SF_STARTDATE}:</td>
						<td class="dvtCellInfo">
							<input name="startdate" id="jscal_field_date_start" style="border: 1px solid rgb(186, 186, 186);" size="10" maxlength="10" value="{$STARTDATE_STD}" type="text" >
							<img src="themes/images/calendar.gif" id="jscal_trigger_date_start" >
							<font size="1"><em old="(yyyy-mm-dd)">({$DATEFORMAT})</em></font>
							<script type="text/javascript">
	                            Calendar.setup ({ldelim}
	                            inputField : "jscal_field_date_start", ifFormat : "{$JS_DATEFORMAT}", showsTime : false, button : "jscal_trigger_date_start", singleClick : true, step : 1
	                            {rdelim})
			                </script>
						</td>
					</tr>
					<tr>
						<td class="dvtCellLabel">{$MOD.LBL_SF_ENDDATE}:</td>
						<td class="dvtCellInfo">
							<input name="enddate" id="jscal_field_date_end" style="border: 1px solid rgb(186, 186, 186);" size="10" maxlength="10" value="{$ENDDATE_STD}" type="text">
			                <img src="themes/images/calendar.gif" id="jscal_trigger_date_end" >
							<font size="1"><em old="(yyyy-mm-dd)">({$DATEFORMAT})</em></font>
			                <script type="text/javascript">
	                            Calendar.setup ({ldelim}
	                            inputField : "jscal_field_date_end", ifFormat : "{$JS_DATEFORMAT}", showsTime : false, button : "jscal_trigger_date_end", singleClick : true, step : 1
	                            {rdelim})
			                </script>
						</td>
					</tr>
				</table>
			</div>
			<div id='mnuTab2' style="display:none;height:250px">
				<table class="small">
					<tr>
					<td colspan="4">
					<ul>
					<li>{$MOD.LBL_AF_HDR2}</li> 
					<li>{$MOD.LBL_AF_HDR3}xczxc</li>
					</ul>  
					</td>	
					</tr>
			
					<tr>
					<td class="dvtCellLabel" width=25%>
					<select name="fcol1" id="fcol1" onchange="updatefOptions(this, 'fop1');updateRelFieldOptions(this, 'fval_1');" class="detailedViewTextBox">
					<option value="">{$MOD.LBL_NONE}</option>
			        {$BLOCK1}
					</select>
					</td>
					<td class="dvtCellLabel" width=25%>
					<select name="fop1" id="fop1" class="repBox" style="width:100px;">
					<option value="">{$MOD.LBL_NONE}</option>
					{$FOPTION1}
					</select>
					</td>
					<td class="dvtCellLabel" width=40%>
					<input name="fval1" id="fval1" class="repBox1" size=40 type="text" value="{$VALUE1}"><img height=20 width=20 src='themes/images/terms.gif' onClick="showHideSelectDiv('show_val1');placeAtCenterOfDiv(mnuTab2,show_val1);"/><input type="image" align="absmiddle" style="cursor: pointer;" onclick="this.form.fval1.value='';return false;" language="javascript" title="Clear" alt="Clear" src="themes/images/clear_field.gif"/>
					</td>
					<td class="dvtCellLabel" style="width:50px;">{$MOD.LBL_AND}</td>
					</tr>
					<tr>
					<td class="dvtCellInfo">
					<select name="fcol2" id="fcol2" onchange="updatefOptions(this, 'fop2');updateRelFieldOptions(this, 'fval_2');" class="detailedViewTextBox">
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
					<td class="dvtCellInfo"><input name="fval2" id="fval2" size=40 class="repBox1" type="text" value="{$VALUE2}"><img height=20 width=20 src='themes/images/terms.gif' onClick="showHideSelectDiv('show_val2');placeAtCenterOfDiv(mnuTab2,show_val2);"/><input type="image" align="absmiddle" style="cursor: pointer;" onclick="this.form.fval2.value='';return false;" language="javascript" title="Clear" alt="Clear" src="themes/images/clear_field.gif"/>
					</td>
					<td class="dvtCellInfo" style="width:50px;">{$MOD.LBL_AND}</td>
					</tr>
					<tr>
					<td class="dvtCellLabel">
					<select name="fcol3" id="fcol3" onchange="updatefOptions(this, 'fop3');updateRelFieldOptions(this, 'fval_3');" class="detailedViewTextBox">
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
					<td class="dvtCellLabel"><input name="fval3" id="fval3" size=40 class="repBox1" type="text" value="{$VALUE3}"><img height=20 width=20 src='themes/images/terms.gif' onClick="showHideSelectDiv('show_val3');placeAtCenterOfDiv(mnuTab2,show_val3);"/><input type="image" align="absmiddle" style="cursor: pointer;" onclick="this.form.fval3.value='';return false;" language="javascript" title="Clear" alt="Clear" src="themes/images/clear_field.gif"/>
					</td>
					<td class="dvtCellLabel" style="width:50px;">{$MOD.LBL_AND}</td>
					</tr>
					<tr>
					<td class="dvtCellInfo">
					<select name="fcol4" id="fcol4" onchange="updatefOptions(this, 'fop4');updateRelFieldOptions(this, 'fval_4');" class="detailedViewTextBox">
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
					<td class="dvtCellInfo"><input name="fval4" id="fval4" size=40 class="repBox1" type="text" value="{$VALUE4}"><img height=20 width=20 src='themes/images/terms.gif' onClick="showHideSelectDiv('show_val4');placeAtCenterOfDiv(mnuTab2,show_val4);"/><input type="image" align="absmiddle" style="cursor: pointer;" onclick="this.form.fval4.value='';return false;" language="javascript" title="Clear" alt="Clear" src="themes/images/clear_field.gif"/>
					</td>
					<td class="dvtCellInfo" style="width:50px;">{$MOD.LBL_AND}</td>
					</tr>
					<tr>
					<td class="dvtCellLabel">
					<select name="fcol5" id="fcol5" onchange="updatefOptions(this, 'fop5');updateRelFieldOptions(this, 'fval_5');" class="detailedViewTextBox">
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
					<td class="dvtCellLabel"><input name="fval5" id="fval5" size=40 class="repBox1" type="text" value="{$VALUE5}">
					<img height=20 width=20 src='themes/images//terms.gif' onClick="showHideSelectDiv('show_val5');placeAtCenterOfDiv(mnuTab2,show_val5);"/>
					<input type="image" align="absmiddle" style="cursor: pointer;" onclick="this.form.fval5.value='';return false;" language="javascript" title="Clear" alt="Clear" src="themes/images/clear_field.gif"/>
					</td>
					<td class="dvtCellLabel">&nbsp;</td>
					</tr>
				</table
				</div>
<!-- DIV FOR Showing comparing fields selection -->
					<div class="layerPopup" id='show_val1'style="border:0; position: absolute; width:300px; z-index: 50; display: none;">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="mailClient mailClientBg">
						<tbody><tr>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="layerHeadingULine">
							<tbody><tr background="themes/images/qcBg.gif" class="mailSubHeader">
								<td width=90% class="genHeaderSmall"><b>{$MOD.LBL_SELECT_FIELDS}</b></td>
								<td align=right> <img border="0" align="absmiddle" src="themes/images/close.gif" style="cursor: pointer;" alt="Close" title="Close" onclick="showHideSelectDiv('show_val1');"/></td
							</tbody></table>
						
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small">
							<tbody><tr>
								<td>
								<table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="white" class="small">
									<tbody><tr>
									<td width="30%" align="left" class="cellLabel small">{$MOD.LBL_RELATED_FIELDS}</td>
									<td width="30%" align="left" class="cellText">
										<select name="fval_1" id="fval_1" onChange='AddFieldToFilter(1,this);' class="detailedViewTextBox">
										<option value="">{$MOD.LBL_NONE}</option>
						        		{$REL_FIELDS1}
						        		</select>
									</td>
								</tr>
								</tbody></table>	
								<!-- save cancel buttons -->
								<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerPopupTransport">
								<tbody><tr>
									<td width="50%" align="center">
										<input type="button" style="width: 70px;" value="  Cancel  " name="button" onclick="showHideSelectDiv('show_val1');" class="crmbutton small cancel" accesskey="X" title="Cancel [Alt+X]"/>
									</td>
								</tr>
								</tbody></table>
						
								</td>
							</tr>
							</tbody></table>
						</td>
						</tr>
						</tbody></table>
					</div>
					<div class="layerPopup" id='show_val2'style="border:0; position: absolute; width:300px; z-index: 50; display: none;">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="mailClient mailClientBg">
						<tbody><tr>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="layerHeadingULine">
							<tbody><tr background="themes/images/qcBg.gif" class="mailSubHeader">
								<td width=90% class="genHeaderSmall"><b>{$MOD.LBL_SELECT_FIELDS}</b></td>
								<td align=right> <img border="0" align="absmiddle" src="themes/images/close.gif" style="cursor: pointer;" alt="Close" title="Close" onclick="showHideSelectDiv('show_val2');"/></td
							</tbody></table>
						
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small">
							<tbody><tr>
								<td>
								<table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="white" class="small">
									<tbody><tr>
									<td width="30%" align="left" class="cellLabel small">{$MOD.LBL_RELATED_FIELDS}</td>
									<td width="30%" align="left" class="cellText">
										<select name="fval_2" id="fval_2" onChange='AddFieldToFilter(2,this);' class="detailedViewTextBox">
										<option value="">{$MOD.LBL_NONE}</option>
						        		{$REL_FIELDS2}
						        		</select>
									</td>
								</tr>
								</tbody></table>	
								<!-- save cancel buttons -->
								<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerPopupTransport">
								<tbody><tr>
									<td width="50%" align="center">
										<input type="button" style="width: 70px;" value="  Cancel  " name="button" onclick="showHideSelectDiv('show_val2');" class="crmbutton small cancel" accesskey="X" title="Cancel [Alt+X]"/>
									</td>
								</tr>
								</tbody></table>
						
								</td>
							</tr>
							</tbody></table>
						</td>
						</tr>
						</tbody></table>
					</div>
					<div class="layerPopup" id='show_val3'style="border:0; position: absolute; width:300px; z-index: 50; display: none;">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="mailClient mailClientBg">
						<tbody><tr>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="layerHeadingULine">
							<tbody><tr background="themes/images/qcBg.gif" class="mailSubHeader">
								<td width=90% class="genHeaderSmall"><b>{$MOD.LBL_SELECT_FIELDS}</b></td>
								<td align=right> <img border="0" align="absmiddle" src="themes/images/close.gif" style="cursor: pointer;" alt="Close" title="Close" onclick="showHideSelectDiv('show_val3');"/></td
							</tbody></table>
						
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small">
							<tbody><tr>
								<td>
								<table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="white" class="small">
									<tbody><tr>
									<td width="30%" align="left" class="cellLabel small">{$MOD.LBL_RELATED_FIELDS}</td>
									<td width="30%" align="left" class="cellText">
										<select name="fval_3" id="fval_3" onChange='AddFieldToFilter(3,this);' class="detailedViewTextBox">
										<option value="">{$MOD.LBL_NONE}</option>
						        		{$REL_FIELDS3}
						        		</select>
									</td>
								</tr>
								</tbody></table>	
								<!-- save cancel buttons -->
								<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerPopupTransport">
								<tbody><tr>
									<td width="50%" align="center">
										<input type="button" style="width: 70px;" value="  Cancel  " name="button" onclick="showHideSelectDiv('show_val3');" class="crmbutton small cancel" accesskey="X" title="Cancel [Alt+X]"/>
									</td>
								</tr>
								</tbody></table>
						
								</td>
							</tr>
							</tbody></table>
						</td>
						</tr>
						</tbody></table>
					</div>
					<div class="layerPopup" id='show_val4'style="border:0; position: absolute; width:300px; z-index: 50; display: none;">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="mailClient mailClientBg">
						<tbody><tr>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="layerHeadingULine">
							<tbody><tr background="themes/images/qcBg.gif" class="mailSubHeader">
								<td width=90% class="genHeaderSmall"><b>{$MOD.LBL_SELECT_FIELDS}</b></td>
								<td align=right> <img border="0" align="absmiddle" src="themes/images/close.gif" style="cursor: pointer;" alt="Close" title="Close" onclick="showHideSelectDiv('show_val4');"/></td
							</tbody></table>
						
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small">
							<tbody><tr>
								<td>
								<table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="white" class="small">
									<tbody><tr>
									<td width="30%" align="left" class="cellLabel small">{$MOD.LBL_RELATED_FIELDS}</td>
									<td width="30%" align="left" class="cellText">
										<select name="fval_4" id="fval_4" onChange='AddFieldToFilter(4,this);' class="detailedViewTextBox">
										<option value="">{$MOD.LBL_NONE}</option>
						        		{$REL_FIELDS4}
						        		</select>
									</td>
								</tr>
								</tbody></table>	
								<!-- save cancel buttons -->
								<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerPopupTransport">
								<tbody><tr>
									<td width="50%" align="center">
										<input type="button" style="width: 70px;" value="  Cancel  " name="button" onclick="showHideSelectDiv('show_val4');" class="crmbutton small cancel" accesskey="X" title="Cancel [Alt+X]"/>
									</td>
								</tr>
								</tbody></table>
						
								</td>
							</tr>
							</tbody></table>
						</td>
						</tr>
						</tbody></table>
					</div>
					<div class="layerPopup" id='show_val5'style="border:0; position: absolute; width:300px;z-index: 50; display: none;">
						<table width="100%" cellspacing="0" cellpadding="0" border="0" align="center" class="mailClient mailClientBg">
						<tbody><tr>
						<td>
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="layerHeadingULine">
							<tbody><tr background="themes/images/qcBg.gif" class="mailSubHeader">
								<td width=90% class="genHeaderSmall"><b>{$MOD.LBL_SELECT_FIELDS}</b></td>
								<td align=right> <img border="0" align="absmiddle" src="themes/images/close.gif" style="cursor: pointer;" alt="Close" title="Close" onclick="showHideSelectDiv('show_val5');"/></td
							</tbody></table>
						
							<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small">
							<tbody><tr>
								<td>
								<table width="100%" cellspacing="0" cellpadding="5" border="0" bgcolor="white" class="small">
									<tbody><tr>
									<td width="30%" align="left" class="cellLabel small">{$MOD.LBL_RELATED_FIELDS}</td>
									<td width="30%" align="left" class="cellText">
										<select name="fval_5" id="fval_5" onChange='AddFieldToFilter(5,this);' class="detailedViewTextBox">
										<option value="">{$MOD.LBL_NONE}</option>
						        		{$REL_FIELDS5}
						        		</select>
									</td>
								</tr>
								</tbody></table>	
								<!-- save cancel buttons -->
								<table width="100%" cellspacing="0" cellpadding="5" border="0" class="layerPopupTransport">
								<tbody><tr>
									<td width="50%" align="center">
										<input type="button" style="width: 70px;" value="  Cancel  " name="button" onclick="showHideSelectDiv('show_val5');" class="crmbutton small cancel" accesskey="X" title="Cancel [Alt+X]"/>
									</td>
								</tr>
								</tbody></table>
						
								</td>
							</tr>
							</tbody></table>
						</td>
						</tr>
						</tbody></table>
					</div>
<!-- Div ends -->			


		</td>
		</tr>
		<tr><td colspan="4">
			<table width="100%" cellspacing="0" cellpadding="0" border="0" class="small">
				<tbody>
					<tr>
				    	<td nowrap="" align="center" id="mi" style="width: 100px;" class="dvtSelectedCell"><!--onclick="fnLoadRepValues('mi','pi','mnuTab2','mnuTab')"-->
				     	<b>{$MOD.LBL_SHARING}</b>
				    	</td>
						<td nowrap="" style="width: 55%;" class="dvtTabCache"> </td>
					</tr>
				</tbody>
			</table>		
		</td>
		</tr>
		<tr>
		<td colspan=4>
				<table width="100%" cellspacing="0" cellpadding="0" class="small" height="190px">
					<tr valign=top><td colspan="2">
						<table width="100%" border="0" cellpadding="5" class="small" cellspacing="0" align="center">
							<tr>
								<td align="right" class="dvtCellLabel" width="50%">{$MOD.SELECT_FILTER_TYPE} :</td>
								<td class="dvtCellInfo" width="50%" align="left">
									<select name="stdtypeFilter" id="stdtypeFilter" class="select" onchange='toggleAssignType(this.options[this.selectedIndex].value );'>
										{foreach item=visible from=$VISIBLECRITERIA}
											<option {$visible.selected} value={$visible.value}>{$visible.text}</option>
										{/foreach}
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2">
									<div id="assign_team" style="display:none">
										<table width="100%" border="0" cellpadding="0" class="small" cellspacing="0" align="center">
											<tr>
												<td align=left colspan=2 class='dvtCellLabel' valign=top>
													<select id="memberType" name="memberType" class="small" onchange="show_Options()">
													<option value="groups" selected>{$MOD.LBL_GROUPS}</option>
													<option value="users">{$MOD.LBL_USERS}</option>
													</select>
													<input type="hidden" name="findStr" class="small">&nbsp;
												</td>
												<td align=right colspan=1 class='dvtCellLabel' valign=top>
													<b>{$MOD.LBL_MEMBERS}</b>
												</td>
											</tr>
											<tr>
												<td valign=top width=45%>
														<select id="availableList" name="availableList" multiple size="5" class="small crmFormList"></select>
														<input type="hidden" name="selectedColumnsStr"/>
												</td>
												<td width="10%">
													<div align="center">
														<input type="button" name="Button" value="&nbsp;&rsaquo;&rsaquo;&nbsp;" onClick="addColumns()" class="crmButton small"/><br /><br />
														<input type="button" name="Button1" value="&nbsp;&lsaquo;&lsaquo;&nbsp;" onClick="removeColumns()" class="crmButton small"/>
													</div>
												</td>
												<td class="small" width="45%" align='right' valign=top> 
													<select id="columnsSelected" name="columnsSelected" multiple size="5" class="small crmFormList">
														{foreach item=element from=$MEMBER}
															<option value="{$element.id}">{$element.name}</option>
														{/foreach}
													</select>
												</td>
											</tr>
										</table>
									</div>
								</td>
							</tr>
					</table>
					</td></tr>
				</table>
			</td>
			</tr>
		</tbody>
	</table>
	</td></tr>
	</tbody>
</table>
<script>
 var rel_fields = {$REL_FIELDS};
var constructedOptionValue;
var constructedOptionName;
var userIdArr=new Array({$USERIDSTR});
var userNameArr=new Array({$USERNAMESTR});
var grpIdArr=new Array({$GROUPIDSTR});
var grpNameArr=new Array({$GROUPNAMESTR});

</script>
{literal}
<script>
stdfilterTypeDisplay();
function stdfilterTypeDisplay(){
	if(document.getElementById('stdtypeFilter').value == 'Shared'){
		document.getElementById("assign_team").style.display = 'block';
	} else {
		document.getElementById("assign_team").style.display = 'none';
	}
}
function toggleAssignType(id){
	if(id =='Shared'){
		document.getElementById("assign_team").style.display = 'block';
	} else {
		document.getElementById("assign_team").style.display = 'none';
	}
}
function show_Options() {
	var selectedOption=document.NewReport.memberType.value;
	
	//Completely clear the select box
	document.forms['NewReport'].availableList.options.length = 0;

	if(selectedOption == 'groups') {
		constructSelectOptions('groups',grpIdArr,grpNameArr);		
	} else if(selectedOption == 'users') {
		constructSelectOptions('users',userIdArr,userNameArr);		
	}
}

function constructSelectOptions(selectedMemberType,idArr,nameArr)
{
	var i;
	var findStr=document.NewReport.findStr.value;
	if(findStr.replace(/^\s+/g, '').replace(/\s+$/g, '').length !=0)
	{
		var k=0;
		for(i=0; i<nameArr.length; i++)
		{
			if(nameArr[i].indexOf(findStr) ==0)
			{
				constructedOptionName[k]=nameArr[i];
				constructedOptionValue[k]=idArr[i];
				k++;			
			}
		}
	}
	else
	{
		constructedOptionValue = idArr;
		constructedOptionName = nameArr;	
	}
	
	//Constructing the selectoptions
	var j;
	var nowNamePrefix;	
	for(j=0;j<constructedOptionName.length;j++)
	{
		if(selectedMemberType == 'groups') {
			nowNamePrefix = 'Group::'
		} else if(selectedMemberType == 'users') {
			nowNamePrefix = 'User::'
		}

		var nowName = nowNamePrefix + constructedOptionName[j];
		var nowId = selectedMemberType + '::'  + constructedOptionValue[j]
		document.forms['NewReport'].availableList.options[j] = new Option(nowName,nowId);	
	}
	
	//clearing the array
	constructedOptionValue = new Array();
    constructedOptionName = new Array();	
}

function showHideSelectDiv(id){
	for(var i=1;i<=5;i++){
		if(id=='show_val'+i){
			if(document.getElementById(id).style.display=='block')
				document.getElementById(id).style.display='none';
			else
				document.getElementById(id).style.display='block';
		}
		else
			document.getElementById('show_val'+i).style.display='none';
	}
	return true;
}
</script>

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
standardFilterDisplay();
</script>

<script language="JavaScript" type="text/JavaScript">    
var moveupLinkObj,moveupDisabledObj,movedownLinkObj,movedownDisabledObj;

function set_Objects() {
	availableListObj=getObj("availableList")
	columnsSelectedObj=getObj("columnsSelected")
}

function addColumns() 
        {
            for (i=0;i<columnsSelectedObj.length;i++) 
            {
                columnsSelectedObj.options[i].selected=false
            }

            for (i=0;i<availableListObj.length;i++) 
            {
                if (availableListObj.options[i].selected==true) 
                {            	
                	var rowFound=false;
                	var existingObj=null;
                    for (j=0;j<columnsSelectedObj.length;j++) 
                    {
                        if (columnsSelectedObj.options[j].value==availableListObj.options[i].value) 
                        {
                            rowFound=true
                            existingObj=columnsSelectedObj.options[j]
                            break
                        }
                    }

                    if (rowFound!=true) 
                    {
                        var newColObj=document.createElement("OPTION")
                        newColObj.value=availableListObj.options[i].value
                        if (browser_ie) newColObj.innerText=availableListObj.options[i].innerText
                        else if (browser_nn4 || browser_nn6) newColObj.text=availableListObj.options[i].text
                        columnsSelectedObj.appendChild(newColObj)
                        availableListObj.options[i].selected=false
                        newColObj.selected=true
                        rowFound=false
                    } 
                    else 
                    {
                        availableListObj.options[i].selected=false
                        if(existingObj != null) existingObj.selected=true
                    }
                }
            }
        }

function removeColumns() 
{
	for (i=columnsSelectedObj.options.length;i>0;i--) 
	{
		if (columnsSelectedObj.options.selectedIndex>=0)
			columnsSelectedObj.remove(columnsSelectedObj.options.selectedIndex)
	}
}

function formSelectedColumnString()
{
	var selectedColStr = "";
	for (i=0;i<columnsSelectedObj.options.length;i++) 
	{
		selectedColStr += columnsSelectedObj.options[i].value + ";";
	}
	document.NewReport.selectedColumnsStr.value = selectedColStr;
}

function placeAtCenterOfDiv(node1, node2){
	var centerPixel = getDimension(node1);
	node2.style.position = "absolute";
	
	var point = getDimension(node2);
	var x = findPosX(node1);
	var y = findPosY(node1);
	
	var ua=navigator.userAgent.toLowerCase();

	if(ua.indexOf('msie')!=-1){
		node2.style.top = centerPixel.y - (centerPixel.y/2+point.y)-30 +"px";
		node2.style.left = centerPixel.x - (centerPixel.x/2+point.x/2) + "px";
	
	} else {
		node2.style.top = y+centerPixel.y - (centerPixel.y/2+point.y)-30 +"px";
		node2.style.left = x+centerPixel.x - (centerPixel.x/2+point.x/2) + "px";
	}
}

function getDimension(node){
	
	var ht = node.offsetHeight;
	var wdth = node.offsetWidth;
	var nodeChildren = node.getElementsByTagName("*");
	var noOfChildren = nodeChildren.length;
	for(var index =0;index<noOfChildren;++index){
		ht = Math.max(nodeChildren[index].offsetHeight, ht);
		wdth = Math.max(nodeChildren[index].offsetWidth,wdth);
	}
	return {x: wdth,y: ht};
}

set_Objects();
show_Options();
</script>
{/literal}
