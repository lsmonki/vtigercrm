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
<body class=small>
{include file='QuickCreateHidden.tpl'}
<table border=0 align="center" cellspacing=0 cellpadding=0 width="90%" class="mailClient mailClientBg">
<tr>
<td>
	<table border=0 cellspacing=0 cellpadding=0 width="100%" class=small>
	<tr>
		<td width-90% class="mailSubHeader" background="{'qcBg.gif'|@vtiger_imageurl:$THEME}"><b >{$APP.LBL_CREATE_BUTTON_LABEL} {$APP.$QCMODULE}</b></td>
		<td nowrap class="mailSubHeader moduleName" align=right><i>{$APP.LBL_QUICK_CREATE}</i></td></tr>
	</table>

	<table border=0 cellspacing=0 cellpadding=0 width="100%" class="small">
	<tr>
		<td>
		
		<!-- quick create UI starts -->
		<table border=0 cellspacing=0 cellpadding=5 width=100% class=small bgcolor=white >
		{foreach item=subdata from=$QUICKCREATE}
		<tr>
				{foreach key=mainlabel item=maindata from=$subdata}
				{assign var="uitype" value="$maindata[0][0]"}
				{assign var="fldlabel" value="$maindata[1][0]"}
				{assign var="fldlabel_sel" value="$maindata[1][1]"}
                {assign var="fldlabel_combo" value="$maindata[1][2]"}
                {assign var="fldlabel_other" value="$maindata[1][3]"}
				{assign var="fldname" value="$maindata[2][0]"}
                {assign var="fldvalue" value="$maindata[3][0]"}
                {assign var="secondvalue" value="$maindata[3][1]"}
				{if $uitype eq 2 }
				<td width=20% class="cellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
				<td width=30% align=left class="cellText"><input type="text" name="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
				{elseif $uitype eq 11 || $uitype eq 1 || $uitype eq 13 || $uitype eq 7 || $uitype eq 9}
				<td width=20% class="cellLabel" align=right>{$fldlabel}</td>
				{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}
					<td width=30% align=left class="cellText"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}sensex_info(){/if}"><span id="vtbusy_info" style="display:none;"><img src="{'vtbusy.gif'|@vtiger_imageurl:$THEME}" border="0"></span></td>
                    {else}
                    <td width=30% align=left class="cellText"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
                {/if}
				
				{elseif $uitype eq 19 || $uitype eq 20}
				<td width=20% class="cellLabel" align=right>
					{if $uitype eq 20}<font color="red">*</font>{/if}
					{$fldlabel}</td>
				<td colspan=3><textarea class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" name="{$fldname}"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$fldvalue}</textarea></td>
				{elseif $uitype eq 21 || $uitype eq 24}
				<td width=20% class="cellLabel" align=right>
					{if $uitype eq 24}
					<font color="red">*</font>
					{/if}
					{$fldlabel}
				</td>
                <td width=30% align=left class="cellText"><textarea value="{$fldvalue}" name="{$fldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2>{$fldvalue}</textarea></td>
				{elseif $uitype eq 15 || $uitype eq 16}
				<td width="20%" class="cellLabel" align=right>
					{if $uitype eq 16} <font color="red">*</font>{/if}
					{$fldlabel}
				</td>
				<td width="30%" align=left class="cellText">
					{if $QCMODULE eq 'Event'}
                                                {assign var=typejsfn value="onChange='calQCduedatetime();'"}
                                        {else}
                                                {assign var=typejsfn value=""}
                                        {/if}
					<select name="{$fldname}" {$typejsfn}>
					{foreach item=arr from=$fldvalue}
						<option value="{$arr[1]}" {$arr[2]}>{$arr[0]}</option>
					{/foreach}
					</select>
				</td>
				{elseif $uitype eq 53}
                <td width="20%" class="cellLabel" align=right>
					{$fldlabel}
                </td>
                <td width="30%" align=left class="cellText">
					{assign var=check value=1}
					{foreach key=key_one item=arr from=$fldvalue}
					{foreach key=sel_value item=value from=$arr}
						{if $value ne ''}
							{assign var=check value=$check*0}
						{else}
							{assign var=check value=$check*1}
						{/if}
					{/foreach}
					{/foreach}
					{if $check eq 0}
						{assign var=select_user value='checked'}
						{assign var=style_user value='display:block'}
						{assign var=style_group value='display:none'}
					{else}
						{assign var=select_group value='checked'}
						{assign var=style_user value='display:none'}
						{assign var=style_group value='display:block'}
					{/if}	
					<input type="radio" name="assigntype" {$select_user} value="U" onclick="toggleAssignType(this.value)">&nbsp;User
					{if $secondvalue neq ''}
					<input type="radio" name="assigntype" {$select_group} value="T" onclick="toggleAssignType(this.value)">&nbsp;Group
					{/if}									

		<span id="assign_user" style="{$style_user}">
        <select name="assigned_user_id">
        {foreach key=key_one item=arr from=$fldvalue}
        {foreach key=sel_value item=value from=$arr}
        <option value="{$key_one}" {$value}>{$sel_value}</option>
        {/foreach}
        {/foreach}
        </select></span>
        {if $secondvalue neq ''}
        <span id="assign_team" style="{$style_group}">
        <select name="assigned_group_id">';
        {foreach key=key_one item=arr from=$secondvalue}
        {foreach key=sel_value item=value from=$arr}
        <option value="{$key_one}" {$value}>{$sel_value}</option>
        {/foreach}
        {/foreach}
        </select></span>
        {/if}
        </td>
		{elseif $uitype eq 52 || $uitype eq 77}
                                                        <td width="20%" class="cellLabel" align=right>
							   {$fldlabel}
							</td>
                                                        <td width="30%" align=left class="cellText">
								{if $uitype eq 52}
                                                           	   <select name="assigned_user_id">
								{elseif $uitype eq 77}
								   <select name="assigned_user_id1">
								{else}
								   <select name="{$fldname}">
								{/if}

                                                                {foreach key=key_one item=arr from=$fldvalue}
                                                                        {foreach key=sel_value item=value from=$arr}
                                                                                <option value="{$key_one}" {$value}>{$sel_value}</option>
                                                                        {/foreach}

                                                                {/foreach}
                                                           </select>
                                                        </td>
							{elseif $uitype eq 51}
								{if $MODULE eq 'Accounts'}
									{assign var='popuptype' value = 'specific_account_address'}
								{else}
									{assign var='popuptype' value = 'specific_contact_account_address'}
								{/if}
							<td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="cellText"><input readonly size="9" name="account_name" style="border:1px solid #bababa;" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=EditView&form_submit=false&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
	
							{elseif $uitype eq 50}
							<td width="20%" class="cellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="cellText"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							{elseif $uitype eq 73}
                                                        <td width="20%" class="cellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="cellText"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 75 || $uitype eq 81}
                                                          <td width="20%" class="cellLabel" align=right>
                                                                {if $uitype eq 81}
								   <font color="red">*</font>
									{assign var="pop_type" value="specific_vendor_address"}
								{else}{assign var="pop_type" value="specific"}
                                                                {/if}
                                                                {$fldlabel}
                                                          </td>
                                                          <td width="30%" align=left class="cellText"><input name="vendor_name" readonly size="8" type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype={$pop_type}&form=EditView&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>
							  {if $uitype eq 75}
                                                           &nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							  {/if}
							{elseif $uitype eq 57}
							<td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="cellText"><input size="8" name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 80}
							<td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="cellText"><input size="8" name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							 {elseif $uitype eq 78}
							 <td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
							 <td width="30%" align=left class="cellText"><input size="8" name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$ID}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 76}
                                                        <td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
                                                        <td width="30%" align=left class="cellText"><input size="8" name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 17}
							<td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="cellText">&nbsp;&nbsp;http://&nbsp;<input type="text" name="{$fldname}" style="border:1px solid #bababa;" class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}"></td>
							
							{elseif $uitype eq 71 || $uitype eq 72}
							<td width="20%" class="cellLabel" align=right>
							   {if $uitype eq 72}
								<font color="red">*</font>
							   {/if}
							   {$fldlabel}</td>
							<td width="30%" align=left class="cellText"><input name="{$fldname}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="{$fldvalue}"></td>
							
							{elseif $uitype eq 56}
                       					<td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
										{if $fldname eq 'notime' && $ACTIVITY_MODE eq 'Events'}
                                        	{if $fldvalue eq 1}
                                            	<td width="30%" align=left class="cellText"><input name="{$fldname}" type="checkbox"  onclick="toggleTime()" checked></td>
                                            {else}
                                            	<td width="30%" align=left class="cellText"><input name="{$fldname}" type="checkbox" onclick="toggleTime()" ></td>
                                         	{/if}
                                        {else}
                                        	{if $fldvalue eq 1}
                                            	<td width="30%" align=left class="cellText"><input name="{$fldname}" type="checkbox"  checked></td>
                                        	{elseif $fldname eq 'filestatus'}
											<td width="30%" align=left class="cellText">
											<input name="{$fldname}" type="checkbox"  checked>
											</td>
				
                                        	{else}
                                            	<td width="30%" align=left class="cellText"><input name="{$fldname}" type="checkbox" {if ( $QCMODULE eq 'Product' ) ||( $QCMODULE eq 'PriceBook' )}checked{/if}></td>
                                         	{/if}
                                        {/if}
							{elseif $uitype eq 23 || $uitype eq 5 || $uitype eq 6}
								<td width="20%" class="cellLabel" align=right>
								{if $uitype eq 23 && $QCMODULE eq 'Event'}
                                	{$APP.LBL_EVENT_ENDDATE}
                            	{else}
                                	{$fldlabel}
                            	{/if}
                            	</td>
								<td width="30%" align=left class="cellText">
							   	{foreach key=date_value item=time_value from=$fldvalue}
									{assign var=date_val value="$date_value"}
									{assign var=time_val value="$time_value"}
							   	{/foreach}
							   	{foreach key=date_fmt item=date_str from=$secondvalue}
                                	{assign var=dateFormat value="$date_fmt"}
                                	{assign var=dateStr value="$date_str"}
                                {/foreach}
                                                        {if $uitype eq 6 && $QCMODULE eq 'Event'}
                                                                {assign var=datejsfn value="onChange='dochange(\"jscal_field_date_start\",\"jscal_field_due_date\");'"}
                                                                {assign var=timejsfn value="onChange='calQCduedatetime();'"}
                                                                <input name="dateFormat" type="hidden" value="{$dateFormat}">
                                                        {else}
                                                                {assign var=datejsfn value=""}
                                                                {assign var=timejsfn value=""}
                                                        {/if}	
							<input name="{$fldname}" id="jscal_field_{$fldname}" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$date_val}" {$datejsfn}>
							<img src="{$IMAGE_PATH}btnL3Calendar.gif" id="jscal_trigger_{$fldname}">
							{if $uitype eq 6}
							   <input name="time_start" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}" {$timejsfn} >
							{/if}
							{if $uitype eq 23 && $QCMODULE eq 'Event'}
                                                           <input name="time_end" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
                                                            <script id="date_calpopup23">
                                                                getCalendarPopup('jscal_trigger_{$fldname}','jscal_field_{$fldname}','{$dateFormat}');
                                                             </script>
                                                        {/if}
							{if $uitype eq 5 || $uitype eq 23}
							   <br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
							   {else}
							   <br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
							{/if}
							<script id="date_calpopup">
                                                                getCalendarPopup('jscal_trigger_{$fldname}','jscal_field_{$fldname}','{$dateFormat}')
                                                        </script>
							</td>

							{elseif $uitype eq 63}
							  <td width="20%" class="cellLabel" align=right>
							        {$fldlabel}
							  </td>
							  <td width="30%" align=left class="cellText">
							        <input name="{$fldname}" type="text" size="2" maxlength="2" value="{$fldvalue}">&nbsp;
							        <select name="duration_minutes">
						        	{foreach key=labelval item=selectval from=$secondvalue}
								<option value="{$labelval}" {$selectval}>{$labelval}</option>
								{/foreach}
								</select>

							{elseif $uitype eq 68 || $uitype eq 66 || $uitype eq 62}
							  <td width="20%" class="cellLabel" align=right>
								<select name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
								{section name=combo loop=$fldlabel}
                                                                <option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]}</option>
                                                                {/section}
								</select>
							  </td>
							<td width="30%" align=left class="cellText">
							<input name="{$fldname}" type="hidden" value="{$secondvalue}"><input size="8" name="parent_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}">
						&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 357}
								<td width="20%" class="cellLabel" align=right>To:&nbsp;</td>
								<td width="90%" colspan="3">
								<input name="{$fldname}" type="hidden" value="{$secondvalue}">
								<textarea readonly name="parent_name" cols="70" rows="2">{$fldvalue}</textarea>&nbsp;
								<select name="parent_type">
									{foreach key=labelval item=selectval from=$fldlabel}
		                                                                <option value="{$labelval}" {$selectval}>{$labelval}</option>
                	                                                {/foreach}
                                                                </select>
								&nbsp;<img src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView&fromlink=qcreate","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
								<tr style="height:25px">
								<td width="20%" class="cellLabel" align=right>CC:&nbsp;</td>	
								<td width="30%" align=left class="cellText">
								<input name="ccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value=""></td>
								<td width="20%" class="cellLabel" align=right>BCC:&nbsp;</td>
                                                                <td width="30%" align=left class="cellText">
                                                                <input name="bccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value=""></td>
								</tr>
				
 	                                                {elseif $uitype eq 59}
                                                          <td width="20%" class="cellLabel" align=right>
                                                           {$fldlabel}</td>
	<td width="30%" align=left class="cellText">
				<input name="{$fldname}" type="hidden" value="{$secondvalue}">
				<input name="product_name" readonly type="text" value="{$fldvalue}">&nbsp;<img tabindex="{$vt_tab}" src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_SELECT}" title="{$APP.LBL_SELECT}" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific&fromlink=qcreate","test","width=640,height=565,resizable=0,scrollbars=0,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{'clear_field.gif'|@vtiger_imageurl:$THEME}" alt="{$APP.LBL_CLEAR}" title="{$APP.LBL_CLEAR}" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'>
			</td>
		
							{elseif $uitype eq 55 || $uitype eq 255} 
								{if $uitype eq 55}	
                                                          <td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
								{elseif $uitype eq 255}	
                                                          <td width="20%" class="cellLabel" align=right><font color ="red">*</font>{$fldlabel}</td>
								{/if}
							  <td width="30%" align=left class="cellText">
							<input type="text" name="{$fldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value= "{$secondvalue}">
                                                 </td>
						 
						{elseif $uitype eq 22}
                                                          <td width="20%" class="cellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							  <td width="30%" align=left class="cellText">
								<textarea name="{$fldname}" cols="30" rows="2">{$fldvalue}</textarea>
                                                 </td>

						{elseif $uitype eq 69}
						<td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
						<td colspan="3" width="30%" align=left class="cellText">
						{if $MODULE eq 'Products'}
							<input name="imagelist" type="hidden" value="">
						    <div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">{$APP.Files_Maximum_6}
						    <input id="my_file_element" type="file" name="file_1"  onchange="validateFilename(this);" />
						    <input type="hidden" name="file_1_hidden" value="" />
                            </div>
                            <script>
                            {*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
                            var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
                            {*<!-- Pass in the file element -->*}
                            multi_selector.addElement( document.getElementById( 'my_file_element' ) );
                            </script>
	                     </td>
                         {else}
                         <input name="{$fldname}"  type="file" value="{$secondvalue}" onchange="validateFilename(this);"/>
			 <input type="hidden" name="{$fldname}_hidden" value="{$secondvalue}" />
			 <input type="hidden" name="id" value=""/>{$fldvalue}</td>
                         {/if}
				
                         {elseif $uitype eq 61}
                         <td width="20%" class="cellLabel" align=right>
						 	{$fldlabel}
						 </td>                         
						 <td colspan="3" width="30%" align=left class="cellText">
						{if $MODULE neq 'Documents'}
						 <input name="{$fldname}"  type="file" value="{$secondvalue}" onchange="validateFilename(this);"/>
						 <input name="{$fldname}_hidden"  type="hidden" value="{$secondvalue}"/>
						 <input type="hidden" name="id" value=""/>{$fldvalue}</td>
						{else}
							<input type="text" style="border: 1px solid rgb(186, 186, 186);" readonly name="{$fldname}" id="{$fldname}" value="{$fldvalue}" /> &nbsp;
							<input type="hidden" name="fileid" id="fileid" value="" />
							<a href="javascript:;" onclick="FileAdd(this,'fileLay','EditView');"><img id="FileAdd_img_id" src="{'select.gif'|@vtiger_imageurl:$THEME}" alt="Add File..." title="Add File..." border=0></a>
						{/if}
				
						</td>
						{elseif $uitype eq 30}
                                                <td width="20%" class="cellLabel" align=right>{$fldlabel}</td>
                                                <td colspan="3" width="30%" align=left class="cellText">
							{assign var=check value=$secondvalue[0]}
                                                        {assign var=yes_val value=$secondvalue[1]}
                                                        {assign var=no_val value=$secondvalue[2]}
                                                <input type="radio" name="set_reminder" value="Yes" {$check}>&nbsp;{$yes_val}&nbsp;<input type="radio" name="set_reminder" value="No">&nbsp;{$no_val}&nbsp;
                                                {foreach item=val_arr from=$fldvalue}
                                                        {assign var=start value="$val_arr[0]"}
                                                        {assign var=end value="$val_arr[1]"}
                                                        {assign var=sendname value="$val_arr[2]"}
                                                        {assign var=disp_text value="$val_arr[3]"}
                                                        {assign var=sel_val value="$val_arr[4]"}
                                                          <select name="{$sendname}">
                                                                {section name=reminder start=$start max=$end loop=$end step=1 }
                                                                {if $smarty.section.reminder.index eq $sel_val}
                                                                        {assign var=sel_value value="SELECTED"}
                                                                {/if}
                                                                <OPTION VALUE="{$smarty.section.reminder.index}" "{$sel_value}">{$smarty.section.reminder.index}</OPTION>
                                                                {/section}
                                                          </select>
                                                        &nbsp;{$disp_text}
                                                {/foreach}
                                                </td>
					          
						 {elseif $uitype eq 121}
							<td width="20%" class="dvtCellLabel" align=right>
							{$fldlabel}
							{if $MASS_EDIT eq '1'}
							<input type="checkbox" name="{$fldname}_mass_edit_check" id="{$fldname}_mass_edit_check" class="small" >{/if}
							</td>
							<td width="30%" align=left class="dvtCellInfo">
								<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
									{foreach item=v key=k from=$fldvalue}	 
									<option value="{$k}">{$v}</option> 
									{/foreach}
								</select>
							</td>
		                       
							{elseif $uitype eq 117}<!-- for currency in users details-->	
								<td width="20%" class="dvtCellLabel" align=right>
									{$fldlabel}
								</td>
								<td width="30%" align=left class="dvtCellInfo">
								   	<select name="{$fldname}" tabindex="{$vt_tab}" class="small">
					
									{foreach item=arr key=uivalueid from=$fldvalue}
										{foreach key=sel_value item=value from=$arr}
											<option value="{$uivalueid}" {$value}>{$sel_value}</option>
											<!-- code added to pass Currency field value, if Disabled for nonadmin -->
											{if $value eq 'selected' && $secondvalue neq 1}
												{assign var="curr_stat" value="$uivalueid"}
											{/if}
											<!--code ends -->
										{/foreach}
									{/foreach}
								   </select>
								</td>
												
							{else}
							<td width="20%" class="cellLabel">&nbsp;</td><td width="30%" class="cellText">&nbsp;</td>
							{/if}
							{/foreach}
						</tr>
							{/foreach}
					
					</table>	
		
		<!-- save cancel buttons -->
		<table border=0 cellspacing=0 cellpadding=5 width=100% class=qcTransport>
		<tr>

										{if $MODULE eq 'Accounts'}
									<td width=50% align=right><input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" onclick="this.form.action.value='Save';  if(getFormValidate())AjaxDuplicateValidate('Accounts','accountname',this.form);" type="button" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" ></td>
										{else}
											<td width=50% align=right><input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="crmbutton small save" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}" style="width:70px" ></td>
										{/if}


			<td width=50% align=left>
				<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="crmbutton small cancel" onclick="hide('qcform'); $('qccombo').options.selectedIndex=0;" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
			</td>
		</tr>
		</table>

		</td>
	</tr>
	</table>
</td>
</tr>
</table>
{if $QCMODULE eq 'Event'}
<SCRIPT id="qcvalidate">
        var qcfieldname = new Array('subject','date_start','time_start','eventstatus','activitytype','due_date','time_end','due_date','time_end');
        var qcfieldlabel = new Array('Subject','Start Date & Time','Start Date & Time','Status','Activity Type','End Date & Time','End Date & Time','End Date & Time','End Date & Time');
        var qcfielddatatype = new Array('V~M','DT~M~time_start','T~O','V~O','V~O','D~M~OTH~GE~date_start~Start Date & Time','T~M','DT~M~time_end','T~O~OTH~GE~time_start~Start Date & Time');
</SCRIPT>
{elseif $QCMODULE eq 'Todo'}
<SCRIPT id="qcvalidate">
	var qcfieldname = new Array('subject','date_start','time_start','taskstatus');
        var qcfieldlabel = new Array('Subject','Start Date & Time','Start Date & Time','Status');
        var qcfielddatatype = new Array('V~M','DT~M~time_start','T~O','V~O');
</SCRIPT>
{else}
<SCRIPT id="qcvalidate">
        var qcfieldname = new Array({$VALIDATION_DATA_FIELDNAME});
        var qcfieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL});
        var qcfielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE});
</SCRIPT>
{/if}
</form>
</body>
