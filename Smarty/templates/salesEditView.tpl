{*<!-- module header -->*}

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script type="text/javascript" src="include/general.js"></script>


<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>

<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>Sales > <a class="hdrLink" href="salesAccListView.html">{$MODULE}</a></td>
	<td class="sep1" style="width:1px"></td>
	<td class=small >
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="Create {$MODULE}..." title="Create {$MODULE}..." border=0></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Search.gif" alt="Search in {$MODULE}..." title="Search in {$MODULE}..." border=0></a></a></td>
				</tr>
				</table>
			</td>
			<td nowrap width=50>&nbsp;</td>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0></a></a></td>
				</tr>
				</table>
			</td>
			
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
				</tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr><td style="height:2px"></td></tr>

</TABLE>

{*<!-- Contents -->*}
<table border=0 cellspacing=0 cellpadding=0 width=98% align=center>
<tr>
	<td valign=top><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>

	<td class="showPanelBg" valign=top width=100%>
		{*<!-- PUBLIC CONTENTS STARTS-->*}
		<div class="small" style="padding:20px">
		
		 {if $OP_MODE eq 'edit_view'}   
			 <span class="lvtHeaderText">{$NAME} -  Editing {$SINGLE_MOD} Information</span> <br>
			 Updated 14 days ago (18 Nov 2005)
		 {/if}
		 {if $OP_MODE eq 'create_view'}
			<span class="lvtHeaderText">Creating {$SINGLE_MOD}</span> <br>
		 {/if}

		 <hr noshade size=1>
		 <br> 
		



<form name="EditView" method="POST" action="index.php">

                        <input type="hidden" name="module" value="{$MODULE}">

                        <input type="hidden" name="record" value="{$ID}">

                        <input type="hidden" name="mode" value="{$MODE}">

                        <input type="hidden" name="action">

                        <input type="hidden" name="return_module" value="{$RETURN_MODULE}">

                        <input type="hidden" name="return_id" value="{$RETURN_ID}">

                        <input type="hidden" name="return_action" value="{$RETURN_ACTION}">

                        <input type="hidden" name="return_viewname" value="{$RETURN_VIEWNAME}">




		{*<!-- Account details tabs -->*}
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>

		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td class="dvtSelectedCell" align=center nowrap>{$SINGLE_MOD} Information</td>
					<td class="dvtTabCache" style="width:10px">&nbsp;</td>
		
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign=top align=left >
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				<tr>

					<td align=left>
					{*<!-- content cache -->*}
					
					<table border=0 cellspacing=0 cellpadding=0 width=100%>
					<tr>
						<td style="padding:10px">
							<!-- General details -->
							<table border=0 cellspacing=0 cellpadding=0 width=100%>
							<tr>
								<td  colspan=4 style="padding:5px">

								<input value="Save" class=small style="width:70px" onclick="this.form.action.value='Save';return formValidate()" type="submit">&nbsp;
								<input type="button" value="Cancel" class=small style="width:70px" onclick="window.history.back()" name="button" >
								</td>
							</tr>
							{foreach key=header item=data from=$BLOCKS}
							<tr>
						         <td colspan=4 class="detailedViewHeader">
                                                	        <b>{$header}</b>
                                                         </td>
                                                         </tr>
							{foreach key=label item=subdata from=$data}
							<tr style="height:25px">
							{foreach key=mainlabel item=maindata from=$subdata}
							   {assign var="uitype" value="$maindata[0][0]"}
                                                           {assign var="fldlabel" value="$maindata[1][0]"}
                                                           {assign var="fldname" value="$maindata[2][0]"}
                                                           {assign var="fldvalue" value="$maindata[3][0]"}
                                                           {assign var="secondvalue" value="$maindata[3][1]"}

							{if $uitype eq 2}
							<td width=20% class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
							{elseif $uitype eq 11 || $uitype eq 1 || $uitype eq 13 || $uitype eq 7 || $uitype eq 9}
							<td width=20% class="dvtCellLabel" align=right>{$fldlabel}</td>
                                                        <td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}"  value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'"></td>
							{elseif $uitype eq 19 || $uitype eq 20}
							 <td width=20% class="dvtCellLabel" align=right>
								{if $uitype eq 20}<font color="red">*</font>{/if}
								{$fldlabel}</td>
							 <td colspan=3><textarea class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" name="{$fldname}"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">
                                                           {$fldvalue}
                                                         </textarea>
                                                         </td>
							{elseif $uitype eq 21 || $uitype eq 24}
							  <td width=20% class="dvtCellLabel" align=right>
								{if $uitype eq 24}
									<font color="red">*</font>
								{/if}
								{$fldlabel}
							  </td>
                                                        <td width=30% align=left class="dvtCellInfo"><textarea value="{$fldvalue}" name="{$fldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2></textarea></td>

							{elseif $uitype eq 15 || $uitype eq 16}
							<td width="20%" class="dvtCellLabel" align=right>
								{if $uitype eq 16}
									<font color="red">*</font>
								{/if}
							{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo">
							   <select name="{$fldname}">
								{foreach item=arr from=$fldvalue}
									{foreach key=sel_value item=value from=$arr}
										<option value={$sel_value} {$value}>{$sel_value}</option>
									{/foreach}
									
								{/foreach}
							   </select>
							</td>

							{elseif $uitype eq 52 || $uitype eq 53}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
                                                        <td width="30%" align=left class="dvtCellInfo">
                                                           <select name="{$fldname}">
                                                                {foreach item=arr from=$fldvalue}
                                                                        {foreach key=sel_value item=value from=$arr}
                                                                                <option value={$sel_value} {$value}>{$sel_value}</option>
                                                                        {/foreach}

                                                                {/foreach}
                                                           </select>
                                                        </td>
							{elseif $uitype eq 51}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" style="border:1px solid #bababa;" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="'.$value.'">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=\'\';this.form.account_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>
							
							{elseif $uitype eq 50}
							<td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="'.$value.'">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>
							{elseif $uitype eq 73}
                                                        <td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="'.$value.'">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>
							
							{elseif $uitype eq 57}
							<td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="'.$value.'">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.contact_id.value=\'\';this.form.contact_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>

							{elseif $uitype eq 17}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo">&nbsp;&nbsp;http://<input type="text" name="{$fldname}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}"></td>
							
							{elseif $uitype eq 71 || $uitype eq 72}
							<td width="20%" class="dvtCellLabel" align=right>
							   {if $uitype eq 72}
								<font color="red">*</font>
							   {/if}
							   {$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="{$fldvalue}"></td>
							
							{elseif $uitype eq 56}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
								{if $fldvalue eq 1}
							<td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"  checked></td>
								{else}				
							<td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"></td>
								{/if}

							{elseif $uitype eq 23 || $uitype eq 5 || $uitype eq 6}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo">
							   {foreach key=date_value item=time_value from=$fldvalue}
								{assign var=date_val value="$date_value"}
								{assign var=time_val value="$time_value"}
							   {/foreach}
							<input name="{$fldname}" id="jscal_field" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$date_val}">
							<img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger">
							{if $uitype eq 6}
							   <input name="time_start" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
							{/if}
							</td>
		
							{elseif $uitype eq 55} 
                                                          <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							  <td width="30%" align=left class="dvtCellInfo"><select name="salutationtype">
							  {foreach item=arr from=$fldvalue}
                                                              {foreach key=sel_value item=value from=$arr}
                                                              <option value={$sel_value} {$value}>{$sel_value}</option>
							  {/foreach}
                                                        {/foreach}
                                                        </select>
							<input type="text" name="{$fldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'" value= "{$secondvalue}">
                                                 </td>
						 
						{elseif $uitype eq 22}
                                                          <td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							  <td width="30%" align=left class="dvtCellInfo">
								<textarea name="{$fldname}" cols="30" rows="2">{$fldvalue}</textarea>
                                                 </td>



							{/if}
							{/foreach}
							</tr>
							{/foreach}
							 
							{/foreach}
							<tr>
								<td  colspan=4 style="padding:5px">

								<input type="submit" value="Save" class=small onclick="this.form.action.value='Save';return formValidate() "  style="width:70px">&nbsp;
								<input type="button" onclick="window.history.back()" name="button" value="Cancel" class=small style="width:70px">
								</td>
							</tr>
</table>
</td></tr></table>
</td></tr>
						
</table>
</td>
</tr>
</table>
</form>
<script>



        var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})

        var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})

        var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})

</script>
{$JAVASCRIPT}











							




