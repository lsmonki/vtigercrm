{*<!-- module header -->*}
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
		
		
		 <span class="lvtHeaderText">{$NAME} -  Editing Account Information</span> <br>
		 Updated 14 days ago (18 Nov 2005)
		 <hr noshade size=1>
		 <br> 
		
		{*<!-- Account details tabs -->*}
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>

		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100%>
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					<td class="dvtSelectedCell" align=center nowrap>{$MODULE} Information</td>
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

								<input type="button" value="Save" class=small style="width:70px" onClick="window.location='salesAccDetailedView.html'">&nbsp;
								<input type="button" value="Cancel" class=small style="width:70px" onClick="window.location='salesAccDetailedView.html'">
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
							{if $maindata[0][0] eq 2 || $maindata[0][0] eq 6}
							<td width=20% class="dvtCellLabel" align=right><font color="red">*</font>{$maindata[1][0]}</td>
							<td width=30% align=left class="dvtCellInfo"><input type="text" value="{$maindata[3][0]}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
							{elseif $maindata[0][0] eq 11 || $maindata[0][0] eq 1 ||$maindata[0][0] eq 13 || $maindata[0][0] eq 7 || $maindata[0][0] eq 9}
							<td width=20% class="dvtCellLabel" align=right>{$maindata[1][0]}</td>
                                                        <td width=30% align=left class="dvtCellInfo"><input type="text" value="{$maindata[3][0]}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'"></td>
							{elseif $maindata[0][0] eq 19}
							 <td align=left valign=top style="padding:5px">
							 <textarea class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'">
                                                           {$maindata[3][0]}
                                                         </textarea>
                                                         </td>
							{elseif $maindata[0][0] eq 21}
							  <td width=20% class="dvtCellLabel" align=right>{$maindata[1][0]}</td>
                                                        <td width=30% align=left class="dvtCellInfo"><textarea value="{$maindata[3][0]}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2></textarea></td>

							{elseif $maindata[0][0] eq 15 || $maindata[0][0] eq 16}
							<td width="20%" class="dvtCellLabel" align=right>
								{if $maindata[0][0] eq 16}<font color="red">*</font>{/if}
							{$maindata[1][0]}</td>
							<td width="30%" align=left class="dvtCellInfo">
							   <select name="{$maindata[2][0]}">
								{foreach item=arr from=$maindata[3][0]}
									{foreach key=sel_value item=value from=$arr}
										<option value={$sel_value} {$value}>{$sel_value}</option>
									{/foreach}
									
								{/foreach}
							   </select>
							</td>

							{elseif $maindata[0][0] eq 52}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$maindata[1][0]}</td>
                                                        <td width="30%" align=left class="dvtCellInfo">
                                                           <select name="{$maindata[2][0]}">
                                                                {foreach item=arr from=$maindata[3][0]}
                                                                        {foreach key=sel_value item=value from=$arr}
                                                                                <option value={$sel_value} {$value}>{$sel_value}</option>
                                                                        {/foreach}

                                                                {/foreach}
                                                           </select>
                                                        </td>
							{elseif $maindata[0][0] eq 51}
							<td width="20%" class="dvtCellLabel" align=right>{$maindata[1][0]}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$maindata[3][0]}"><input name="{$maindata[2][0]}" type="hidden" value="'.$value.'">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=\'\';this.form.account_name.value=\'\';return false;" align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>
							
							{elseif $maindata[0][0] eq 50}
							<td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$maindata[1][0]}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$maindata[3][0]}"><input name="{$maindata[2][0]}" type="hidden" value="'.$value.'">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>
							{elseif $maindata[0][0] eq 73}
                                                        <td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$maindata[1][0]}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$maindata[3][0]}"><input name="{$maindata[2][0]}" type="hidden" value="'.$value.'">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick=\'return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");\' align="absmiddle" style=\'cursor:hand;cursor:pointer\'></td>
							
							{elseif $maindata[0][0] eq 17}
							<td width="20%" class="dvtCellLabel" align=right>{$maindata[1][0]}</td>
							<td width="30%" align=left class="dvtCellInfo">&nbsp;&nbsp;http://<input type="text" name="{$maindata[2][0]}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" value="{$maindata[3][0]}"></td>
							
							{elseif $maindata[0][0] eq 71 || $maindata[0][0] eq 72}
							<td width="20%" class="dvtCellLabel" align=right>
							   {if $maindata[0][0] eq 72}
								<font color="red">*</font>
							   {/if}
							   {$maindata[1][0]}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="{$maindata[2][0]}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="{$maindata[3][0]}"></td>
							
							{elseif $maindata[0][0] eq 56}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$maindata[1][0]}</td>
								{if $maindata[3][0] eq 1}
							<td width="30%" align=left class="dvtCellInfo"><input name="{$maindata[2][0]}" type="checkbox"  checked></td>
								{else}				
							<td width="30%" align=left class="dvtCellInfo"><input name="{$maindata[2][0]}" type="checkbox"></td>
								{/if}

							{elseif $maindata[0][0] eq 23 || $maindata[0][0] eq 5 || $maindata[0][0] eq 6}
							<td width="20%" class="dvtCellLabel" align=right>{$maindata[1][0]}</td>
							<td width="30%" align=left class="dvtCellInfo">
							   {foreach key=date_value item=time_value from=$maindata[3][0]}
								{assign var=date_val value="$date_value"}
								{assign var=time_val value="$time_value"}
							   {/foreach}
							<input name="{$maindata[2][0]}" id="jscal_field" type="text" size="11" maxlength="10" value="{$date_val}">
							<img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger">
							</td>
							{/if}
							{/foreach}
							</tr>
							{/foreach}
							 
							{/foreach}
							<tr>
								<td  colspan=4 style="padding:5px">

								<input type="button" value="Save" class=small style="width:70px" onClick="window.location='salesAccDetailedView.html'">&nbsp;
								<input type="button" value="Cancel" class=small style="width:70px" onClick="window.location='salesAccDetailedView.html'">
								</td>
							</tr>
						
</table>
</td>
</tr>
</table>










							




