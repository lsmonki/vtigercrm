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

{*<!-- module header -->*}

<link rel="stylesheet" type="text/css" media="all" href="jscalendar/calendar-win2k-cold-1.css">
<script type="text/javascript" src="jscalendar/calendar.js"></script>
<script type="text/javascript" src="jscalendar/lang/calendar-{$CALENDAR_LANG}.js"></script>
<script type="text/javascript" src="jscalendar/calendar-setup.js"></script>
<script type="text/javascript" src="modules/{$MODULE}/{$SINGLE_MOD}.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/searchajax.js"></script>

<script type="text/javascript">

function ajaxResponse(response)
{ldelim}
        document.getElementById('autocom').innerHTML = response.responseText;
        document.getElementById('autocom').style.display="block";
        hide('vtbusy_info');
{rdelim}

function sensex_info()
{ldelim}
        var Ticker = document.getElementById('tickersymbol').value;
        if(Ticker!='')
        {ldelim}
                show('vtbusy_info');
                var ajaxObj = new Ajax(ajaxResponse);
                //var Ticker = document.getElementById('tickersymbol').value;
                var urlstring = "module={$MODULE}&action=Tickerdetail&tickersymbol="+Ticker;
                ajaxObj.process("index.php?",urlstring);
        {rdelim}
{rdelim}

</script>

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>

<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:10px" class="moduleName" nowrap>{$CATEGORY} > <a class="hdrLink" href="index.php?action=ListView&module={$MODULE}">{$MODULE}</a></td>
	<td class="sep1" style="width:1px"></td>
	<td class=small >
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="index.php?module={$MODULE}&action=EditView&parenttab={$CATEGORY}"><img src="{$IMAGE_PATH}btnL3Add.gif" alt="Create {$SINGLE_MOD}..." title="Create {$SINGLE_MOD}..." border=0></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Search.gif" alt="Search in {$MODULE}..." title="Search in {$MODULE}..." border=0></a></a></td>
				</tr>
				</table>
			</td>
			<td nowrap width=50>&nbsp;</td>
			<td>
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calendar.gif" alt="Open Calendar..." title="Open Calendar..." border=0></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Clock.gif" alt="Show World Clock..." title="Show World Clock..." border=0 onClick="fnvshobj(this,'wclock')"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="{$IMAGE_PATH}btnL3Calc.gif" alt="Open Calculator..." title="Open Calculator..." border=0 onClick="fnvshobj(this,'calc')"></a></a></td>
				</tr>
				</table>
			</td>
			<td nowrap style="width:50%;padding:10px">&nbsp;</td>
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
			 <span class="lvtHeaderText"><font color="purple">[ {$ID} ] </font>{$NAME} -  Editing {$SINGLE_MOD} Information</span> <br>
			{$UPDATEINFO}	 
		 {/if}
		 {if $OP_MODE eq 'create_view'}
			<span class="lvtHeaderText">Creating New {$SINGLE_MOD}</span> <br>
		 {/if}

		 <hr noshade size=1>
		 <br> 
		
		{include file='EditViewHidden.tpl'}

		{*<!-- Account details tabs -->*}
		<table border=0 cellspacing=0 cellpadding=0 width=95% align=center>

		<tr>
			<td>
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="small">
				<tr>
					<td class="dvtTabCache" style="width:10px" nowrap>&nbsp;</td>
					{if $MODULE eq 'Leads' || $MODULE eq 'Accounts' || $MODULE eq 'Contacts' || $MODULE eq 'Products'}	
						<td width=75 style="width:15%" align="center" nowrap="nowrap" class="dvtSelectedCell" id="bi" onclick="fnLoadValues('bi','mi','basicTab','moreTab')"><b>Basic Information</b></td>
                    				<td class="dvtUnSelectedCell" style="width: 100px;" align="center" nowrap="nowrap" id="mi" onclick="fnLoadValues('mi','bi','moreTab','basicTab')"><b>More Information </b></td>
                   				<td class="dvtTabCache" style="width:100%" nowrap="nowrap">&nbsp;</td>
					{else}
						<td class="dvtSelectedCell" align=center nowrap>Basic Information</td>
	                                        <td class="dvtTabCache" style="width:100%">&nbsp;</td>
					{/if}
                                        
				</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td valign=top align=left >
				<div id="basicTab">
<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				<tr>

					<td align=left>
					<!-- content cache -->
					
					<table border=0 cellspacing=0 cellpadding=0 width=100%>
					<tr><td id ="autocom"></td></tr>
					<tr>
						<td style="padding:10px">
							<!-- General details -->
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
							<tr>
								<td  colspan=4 style="padding:5px">
								<div align="center">
								<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="small" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                                                 <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="small" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
								</div>
								</td>
							</tr>
							{foreach key=header item=data from=$BASBLOCKS}
							<tr>
						         <td colspan=4 class="detailedViewHeader">
                                                	        <b>{$header}</b>
							 </td>
		                                        </tr>
														
							{foreach key=label item=subdata from=$data}
							{if $header eq 'Product Details'}
								<tr>
							{else}
								<tr style="height:25px">
							{/if}
							{foreach key=mainlabel item=maindata from=$subdata}
							   {assign var="uitype" value="$maindata[0][0]"}
                                                           {assign var="fldlabel" value="$maindata[1][0]"}
							   {assign var="fldlabel_sel" value="$maindata[1][1]"}
                                                           {assign var="fldlabel_combo" value="$maindata[1][2]"}
                                                           {assign var="fldname" value="$maindata[2][0]"}
                                                           {assign var="fldvalue" value="$maindata[3][0]"}
                                                           {assign var="secondvalue" value="$maindata[3][1]"}
							
							{if $header eq 'Product Details'}
							<tr><td colspan=4>
							  <table class="prdTab"  border="0" cellspacing="0" cellpadding="2" id="proTab">
			                                      <tr>
			                                        <th width="20%"><font color='red'>*</font>Product</th>

								{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
									<th width="12%">Qty In Stock</th>
								{/if}

                        			                <th width="10%"><font color='red'>*</font>Qty</th>
                                        			<th width="10%">Unit Price </th>
                                        			<th width="19%"><font color='red'>*</font>List Price</th>
                                        			<th width="10%">Total</th>

			                                        <th width="5%">&nbsp;</th>
                        			              </tr>
                                      			      <tr id="row1" class="dvtCellLabel">
			                                        <td nowrap><input type="text" name="txtProduct1" class="detailedViewProdTextBox" readonly />&nbsp;<img src="themes/blue/images/search.gif" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this)" /></td>

								{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
									<td style="padding:3px;"><div id="qtyInStock1"></div>&nbsp;</td>
								{/if}

                        			                <td><input type="text" name="txtQty1" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onBlur="settotalnoofrows(); calcTotal(this)" /></td>
                                        			<td style="padding:3px;"><div id="unitPrice1"></div>&nbsp;</td>
			                                        <td nowrap><input type="text" name="txtListPrice1" class="detailedViewProdTextBox" readonly onBlur="settotalnoofrows(); calcTotal(this)"/>&nbsp;<img src="themes/blue/images/pricebook.gif" onclick="priceBookPickList(this)" style="cursor: pointer;" title="Price Book" align="absmiddle" /></td>
                        			                <td style="padding:3px;"><div id="total1" align="right"></div>&nbsp;</td>
                                        			<td><input type="hidden" id="hdnProductId1" name="hdnProductId1"><input type="hidden" id="hdnRowStatus1" name="hdnRowStatus1"><input type="hidden" id="hdnTotal1" name="hdnTotal1">&nbsp;</td>

                                      				</tr>
			                                    </table></td></tr>
								<tr><td colspan=4>
							     <table width="100%" border="0" cellspacing="0" cellpadding="0">
  								<tr>

								{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
									<td><input type="button" name="Button" class="small" value="Add Product" onclick="fnAddRow();" /></td>
									{else}
										<td><input type="button" name="Button" class="small" value="Add Product" onclick="fnAddRowForPO();" /></td>
								{/if}

								<td width="35%">&nbsp;</td>
								<td style="text-align:right;padding:5px;"><b>Sub Total</b></td>
								<td style="text-align:left;padding:5px;"><input type="text" name="subTotal"  class="detailedViewTextBox" readonly/></td>

								<td width="5%">&nbsp;</td>
								</tr>
								<tr>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
    								<td style="text-align:right;padding:5px;"><b>Tax</b></td>
								<td style="text-align:left;padding:5px;"><input type="text" name="txtTax" id="txtTax" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBox'" value="{$TAXVALUE}" onblur="calcGrandTotal()" /></td>
							        <td>&nbsp;</td>

  								</tr>
  								<tr>
								    <td>&nbsp;</td>
								    <td>&nbsp;</td>
								    <td style="text-align:right;padding:5px;"><b>Adjusment</b></td>
								    <td style="text-align:left;padding:5px;"><input type="text" name="txtAdjustment" id="txtAdjustment" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBox'" value="{$ADJUSTMENTVALUE}" onblur="calcGrandTotal()" /></td>
								    <td>&nbsp;</td>
								    </tr>

								  <tr>
								    <td>&nbsp;</td>
								    <td>&nbsp;</td>
								    <td style="text-align:right;padding:5px;"><b>Grand Total</b></td>
								    <td style="text-align:left;padding:5px;"><input type="text" name="grandTotal"  class="detailedViewTextBox"  readonly /></td>
								    <td>&nbsp;</td>
								    </tr>
								    </table>
<input type="hidden" name="hdnSubTotal" id="hdnSubTotal" value="">
  <input type="hidden" name="hdnGrandTotal" id="hdnGrandTotal" value="">
</td></tr>
							{/if}

							{if $uitype eq 2}
							<td width=20% class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
							{elseif $uitype eq 11 || $uitype eq 1 || $uitype eq 13 || $uitype eq 7 || $uitype eq 9}
							<td width=20% class="dvtCellLabel" align=right>{$fldlabel}</td>
							{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}
                                                        <td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}sensex_info(){/if}"><span id="vtbusy_info" style="display:none;"><img src="themes/blue/images/vtbusy.gif" border="0"></span></td>
                                                        {else}
                                                        <td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
                                                        {/if}
							{elseif $uitype eq 19 || $uitype eq 20}
							 <td width=20% class="dvtCellLabel" align=right>
							    {if $uitype eq 20}<font color="red">*</font>{/if}
								{$fldlabel}</td>
							 <td colspan=3><textarea class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" name="{$fldname}"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$fldvalue}</textarea>
                                                         </td>
							{elseif $uitype eq 21 || $uitype eq 24}
							  <td width=20% class="dvtCellLabel" align=right>
							     {if $uitype eq 24}
								<font color="red">*</font>
							     {/if}
							     {$fldlabel}
							  </td>
                                                          <td width=30% align=left class="dvtCellInfo"><textarea value="{$fldvalue}" name="{$fldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2>{$fldvalue}</textarea></td>
							{elseif $uitype eq 15 || $uitype eq 16}
							<td width="20%" class="dvtCellLabel" align=right>
								{if $uitype eq 16}
							           <font color="red">*</font>
								{/if}
								{$fldlabel}
							</td>
							<td width="30%" align=left class="dvtCellInfo">
							   <select name="{$fldname}">
								{foreach item=arr from=$fldvalue}
									{foreach key=sel_value item=value from=$arr}
										<option value="{$sel_value}" {$value}>{$sel_value}</option>
									{/foreach}
									
								{/foreach}
							   </select>
							</td>
							{elseif $uitype eq 33}
                                                        <td width="20%" class="dvtCellLabel" align=right>
                                                                {$fldlabel}
                                                        </td>
                                                        <td width="30%" align=left class="dvtCellInfo">
                                                           <select MULTIPLE name="{$fldname}" size="2">
                                                                {foreach item=arr from=$fldvalue}
                                                                        {foreach key=sel_value item=value from=$arr}
                                                                                <option value="{$sel_value}" {$value}>{$sel_value}</option>
                                                                        {/foreach}
                                                                {/foreach}
                                                           </select>
                                                        </td>

							{elseif $uitype eq 53}
                                                        <td width="20%" class="dvtCellLabel" align=right>
                                                           {$fldlabel}
                                                        </td>
                                                        <td width="30%" align=left class="dvtCellInfo">
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
	<input type="radio" name="assigntype" {$select_group} value="T" onclick="toggleAssignType(this.value)">&nbsp;Team
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
        <select name="assigned_group_name">';
        {foreach key=key_one item=arr from=$secondvalue}
        {foreach key=sel_value item=value from=$arr}
        <option value="{$sel_value}" {$value}>{$sel_value}</option>
        {/foreach}
        {/foreach}
        </select></span>
        {/if}
        </td>
		{elseif $uitype eq 52 || $uitype eq 77}
                                                        <td width="20%" class="dvtCellLabel" align=right>
							   {$fldlabel}
							</td>
                                                        <td width="30%" align=left class="dvtCellInfo">
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
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" style="border:1px solid #bababa;" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype={$popuptype}&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 50}
							<td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							{elseif $uitype eq 73}
                                                        <td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 75 || $uitype eq 81}
                                                          <td width="20%" class="dvtCellLabel" align=right>
                                                                {if $uitype eq 81}
								   <font color="red">*</font>
									{assign var="pop_type" value="specific_vendor_address"}
								{else}{assign var="pop_type" value="specific"}
                                                                {/if}
                                                                {$fldlabel}
                                                          </td>
                                                          <td width="30%" align=left class="dvtCellInfo"><input name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype={$pop_type}&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>
							  {if $uitype eq 75}
                                                           &nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							  {/if}
							{elseif $uitype eq 57}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 80}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							 {elseif $uitype eq 78}
							 <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							 <td width="30%" align=left class="dvtCellInfo"><input name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$ID}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 76}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
                                                        <td width="30%" align=left class="dvtCellInfo"><input name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 17}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo">&nbsp;&nbsp;http://&nbsp;<input type="text" name="{$fldname}" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}"></td>
							
							{elseif $uitype eq 71 || $uitype eq 72}
							<td width="20%" class="dvtCellLabel" align=right>
							   {if $uitype eq 72}
								<font color="red">*</font>
							   {/if}
							   {$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="{$fldvalue}"></td>
							
							{elseif $uitype eq 56}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							{if $fldname eq 'notime' && $ACTIVITY_MODE eq 'Events'}
                                                                {if $fldvalue eq 1}
                                                                <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"  onclick="toggleTime()" checked></td>
                                                                {else}
                                                                <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox" onclick="toggleTime()" ></td>
                                                                {/if}
                                                        {else}
                                                                {if $fldvalue eq 1}
                                                        <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"  checked></td>
                                                                {else}
                                                        <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"></td>
                                                                {/if}
                                                        {/if}
							{elseif $uitype eq 23 || $uitype eq 5 || $uitype eq 6}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo">
							   {foreach key=date_value item=time_value from=$fldvalue}
								{assign var=date_val value="$date_value"}
								{assign var=time_val value="$time_value"}
							   {/foreach}
							<input name="{$fldname}" id="jscal_field_{$fldname}" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$date_val}">
							<img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_{$fldname}">
							{if $uitype eq 6}
							   <input name="time_start" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
							{/if}
							{foreach key=date_format item=date_str from=$secondvalue}
                                                                {assign var=dateFormat value="$date_format"}
							        {assign var=dateStr value="$date_str"}
							{/foreach}
							{if $uitype eq 5 || $uitype eq 23}
							   <br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
							   {else}
							   <br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
							{/if}
							<script type="text/javascript">
                					Calendar.setup ({ldelim}
								inputField : "jscal_field_{$fldname}", ifFormat : "{$dateFormat}", showsTime : false, button : "jscal_trigger_{$fldname}", singleClick : true, step : 1
									{rdelim})
     						        </script>

							
							</td>

							{elseif $uitype eq 63}
							  <td width="20%" class="dvtCellLabel" align=right>
							        {$fldlabel}
							  </td>
							  <td width="30%" align=left class="dvtCellInfo">
							        <input name="{$fldname}" type="text" size="2" value="{$fldvalue}">&nbsp;
							        <select name="duration_minutes">
						        	{foreach key=labelval item=selectval from=$secondvalue}
								<option value="{$labelval}" {$selectval}>{$labelval}</option>
								{/foreach}
								</select>

							{elseif $uitype eq 68 || $uitype eq 66 || $uitype eq 62}
							  <td width="20%" class="dvtCellLabel" align=right>
								<select name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
								{section name=combo loop=$fldlabel}
                                                                <option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]}</option>
                                                                {/section}
								</select>
							  </td>
							<td width="30%" align=left class="dvtCellInfo">
							<input name="{$fldname}" type="hidden" value="{$secondvalue}"><input name="parent_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}">
						&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 357}
								<td width="20%" class="dvtCellLabel" align=right>To:&nbsp;</td>
								<td width="90%" colspan="3">
								<input name="{$fldname}" type="hidden" value="{$secondvalue}">
								<textarea readonly name="parent_name" cols="70" rows="2">{$fldvalue}</textarea>&nbsp;
								<select name="parent_type">
									{foreach key=labelval item=selectval from=$fldlabel}
		                                                                <option value="{$labelval}" {$selectval}>{$labelval}</option>
                	                                                {/foreach}
                                                                </select>
								&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
								<tr style="height:25px">
								<td width="20%" class="dvtCellLabel" align=right>CC:&nbsp;</td>	
								<td width="30%" align=left class="dvtCellInfo">
								<input name="ccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value=""></td>
								<td width="20%" class="dvtCellLabel" align=right>BCC:&nbsp;</td>
                                                                <td width="30%" align=left class="dvtCellInfo">
                                                                <input name="bccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value=""></td>
								</tr>
				
 	                                                {elseif $uitype eq 59}
                                                          <td width="20%" class="dvtCellLabel" align=right>
                                                           {$fldlabel}</td>
                                                          <td width="30%" align=left class="dvtCellInfo">
                                                           <input name="{$fldname}" type="hidden" value="{$secondvalue}"><input name="product_name" readonly type="text" value="{$fldvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
		
							{elseif $uitype eq 55} 
                                                          <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							  <td width="30%" align=left class="dvtCellInfo"><select name="salutationtype">
							  {foreach item=arr from=$fldvalue}
                                                              {foreach key=sel_value item=value from=$arr}
                                                              <option value="{$sel_value}" {$value}>{$sel_value}</option>
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

						{elseif $uitype eq 69}
						<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
						<td colspan="3" width="30%" align=left class="dvtCellInfo">
						{if $MODULE eq 'Products'}
							<input name="imagelist" type="hidden" value="">
						    <div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">Files Maximum 6
						    <input id="my_file_element" type="file" name="file_1" >
                            </div>
                            <script>
                            {*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
                            var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
                            {*<!-- Pass in the file element -->*}
                            multi_selector.addElement( document.getElementById( 'my_file_element' ) );
                            </script>
	                     </td>
                         {else}
                         <input name="{$fldname}"  type="file" value="{$secondvalue}"/><input type="hidden" name="id" value=""/>{$fldvalue}</td>
                         {/if}
				
                         {elseif $uitype eq 61}
                         <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
						 <td colspan="3" width="30%" align=left class="dvtCellInfo"><input name="{$fldname}"  type="file" value="{$secondvalue}"/><input type="hidden" name="id" value=""/>{$fldvalue}</td>
						{elseif $uitype eq 30}
                                                <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
                                                <td colspan="3" width="30%" align=left class="dvtCellInfo">
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

							{/if}
							{/foreach}
							</tr>
							{/foreach}
							 <tr style="height:25px"><td>&nbsp;</td></tr>
							{/foreach}

							<tr>
								<td  colspan=4 style="padding:5px">
					
								<div align="center">
								{if $MODULE eq 'Emails'}
                                                                <input title="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE}" accessKey="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_KEY}" class="small" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid={$ENTITY_ID}&entity={$ENTITY_TYPE}','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL}">
                                                                <input title="{$MOD.LBL_SEND}" accessKey="{$MOD.LBL_SEND}" class="small" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  {$MOD.LBL_SEND}  " >
                                                                {/if}
                                                                <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="small" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                                                <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="small" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
								</div>
								</td>
							</tr>
</table>
</td></tr></table>
</td></tr></table>
					
				</div>

				<div id="moreTab">
				<table border=0 cellspacing=0 cellpadding=3 width=100% class="dvtContentSpace">
				<tr>

					<td align=left>
					{*<!-- content cache -->*}
					
					<table border=0 cellspacing=0 cellpadding=0 width=100%>
					<tr><td id ="autocom"></td></tr>
					<tr>
						<td style="padding:10px">
							<!-- General details -->
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="small">
							<tr>
								<td  colspan=4 style="padding:5px">
								<div align="center">
								<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="small" onclick="this.form.action.value='Save';  return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                                                 <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="small" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
								</div>
								</td>
							</tr>
							{foreach key=header item=data from=$ADVBLOCKS}
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
							   {assign var="fldlabel_sel" value="$maindata[1][1]"}
                                                           {assign var="fldlabel_combo" value="$maindata[1][2]"}
                                                           {assign var="fldname" value="$maindata[2][0]"}
                                                           {assign var="fldvalue" value="$maindata[3][0]"}
                                                           {assign var="secondvalue" value="$maindata[3][1]"}


							{if $uitype eq 2}
							<td width=20% class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
							{elseif $uitype eq 11 || $uitype eq 1 || $uitype eq 13 || $uitype eq 7 || $uitype eq 9}
							<td width=20% class="dvtCellLabel" align=right>{$fldlabel}</td>
							{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}
                                                        <td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn';" onBlur="this.className='detailedViewTextBox';{if $fldname eq 'tickersymbol' && $MODULE eq 'Accounts'}sensex_info(){/if}"><span id="vtbusy_info" style="display:none;"><img src="themes/blue/images/vtbusy.gif" border="0"></span></td>
                                                        {else}
                                                        <td width=30% align=left class="dvtCellInfo"><input type="text" name="{$fldname}" id ="{$fldname}" value="{$fldvalue}" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"></td>
                                                        {/if}
							{elseif $uitype eq 19 || $uitype eq 20}
							 <td width=20% class="dvtCellLabel" align=right>
							    {if $uitype eq 20}<font color="red">*</font>{/if}
								{$fldlabel}</td>
							 <td colspan=3><textarea class="detailedViewTextBox" onFocus="this.className='detailedViewTextBoxOn'" name="{$fldname}"  onBlur="this.className='detailedViewTextBox'" cols="90" rows="8">{$fldvalue}</textarea>
                                                         </td>
							{elseif $uitype eq 21 || $uitype eq 24}
							  <td width=20% class="dvtCellLabel" align=right>
							     {if $uitype eq 24}
								<font color="red">*</font>
							     {/if}
							     {$fldlabel}
							  </td>
                                                          <td width=30% align=left class="dvtCellInfo"><textarea value="{$fldvalue}" name="{$fldname}"  class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" rows=2>{$fldvalue}</textarea></td>
							{elseif $uitype eq 15 || $uitype eq 16}
							<td width="20%" class="dvtCellLabel" align=right>
								{if $uitype eq 16}
							           <font color="red">*</font>
								{/if}
								{$fldlabel}
							</td>
							<td width="30%" align=left class="dvtCellInfo">
							   <select name="{$fldname}">
								{foreach item=arr from=$fldvalue}
									{foreach key=sel_value item=value from=$arr}
										<option value="{$sel_value}" {$value}>{$sel_value}</option>
									{/foreach}
									
								{/foreach}
							   </select>
							</td>
							{elseif $uitype eq 33}
                                                        <td width="20%" class="dvtCellLabel" align=right>
                                                                {$fldlabel}
                                                        </td>
                                                        <td width="30%" align=left class="dvtCellInfo">
                                                           <select MULTIPLE name="{$fldname}" size="2">
                                                                {foreach item=arr from=$fldvalue}
                                                                        {foreach key=sel_value item=value from=$arr}
                                                                                <option value="{$sel_value}" {$value}>{$sel_value}</option>
                                                                        {/foreach}
                                                                {/foreach}
                                                           </select>
                                                        </td>

							{elseif $uitype eq 53}
                                                        <td width="20%" class="dvtCellLabel" align=right>
                                                           {$fldlabel}
                                                        </td>
                                                        <td width="30%" align=left class="dvtCellInfo">
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
	<input type="radio" name="assigntype" {$select_group} value="T" onclick="toggleAssignType(this.value)">&nbsp;Team
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
        <select name="assigned_group_name">';
        {foreach key=key_one item=arr from=$secondvalue}
        {foreach key=sel_value item=value from=$arr}
        <option value="{$sel_value}" {$value}>{$sel_value}</option>
        {/foreach}
        {/foreach}
        </select></span>
        {/if}
        </td>
		{elseif $uitype eq 52 || $uitype eq 77}
                                                        <td width="20%" class="dvtCellLabel" align=right>
							   {$fldlabel}
							</td>
                                                        <td width="30%" align=left class="dvtCellInfo">
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
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" style="border:1px solid #bababa;" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype={$popuptype}&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.account_id.value=''; this.form.account_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 50}
							<td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							{elseif $uitype eq 73}
                                                        <td width="20%" class="dvtCellLabel" align=right><font color="red">*</font>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input readonly name="account_name" type="text" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Accounts&action=Popup&popuptype=specific_account_address&form=TasksEditView&form_submit=false","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 75 || $uitype eq 81}
                                                          <td width="20%" class="dvtCellLabel" align=right>
                                                                {if $uitype eq 81}
								   <font color="red">*</font>
									{assign var="pop_type" value="specific_vendor_address"}
								{else}{assign var="pop_type" value="specific"}
                                                                {/if}
                                                                {$fldlabel}
                                                          </td>
                                                          <td width="30%" align=left class="dvtCellInfo"><input name="vendor_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Vendors&action=Popup&html=Popup_picker&popuptype={$pop_type}&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>
							  {if $uitype eq 75}
                                                           &nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.vendor_id.value='';this.form.vendor_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							  {/if}
							{elseif $uitype eq 57}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="contact_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Contacts&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.contact_id.value=''; this.form.contact_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 80}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="salesorder_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=SalesOrder&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.salesorder_id.value=''; this.form.salesorder_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							 {elseif $uitype eq 78}
							 <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							 <td width="30%" align=left class="dvtCellInfo"><input name="quote_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$ID}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Quotes&action=Popup&html=Popup_picker&popuptype=specific&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.quote_id.value=''; this.form.quote_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 76}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
                                                        <td width="30%" align=left class="dvtCellInfo"><input name="potential_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}"><input name="{$fldname}" type="hidden" value="{$secondvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Potentials&action=Popup&html=Popup_picker&popuptype=specific_potential_account_address&form=EditView","test","width=600,height=400,resizable=1,scrollbars=1");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.potential_id.value=''; this.form.potential_name.value='';return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>

							{elseif $uitype eq 17}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo">&nbsp;&nbsp;http://&nbsp;<input type="text" name="{$fldname}" style="border:1px solid #bababa;" size="27" onFocus="this.className='detailedViewTextBoxOn'"onBlur="this.className='detailedViewTextBox'" value="{$fldvalue}"></td>
							
							{elseif $uitype eq 71 || $uitype eq 72}
							<td width="20%" class="dvtCellLabel" align=right>
							   {if $uitype eq 72}
								<font color="red">*</font>
							   {/if}
							   {$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value="{$fldvalue}"></td>
							
							{elseif $uitype eq 56}
                                                        <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							{if $fldname eq 'notime' && $ACTIVITY_MODE eq 'Events'}
                                                                {if $fldvalue eq 1}
                                                                <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"  onclick="toggleTime()" checked></td>
                                                                {else}
                                                                <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox" onclick="toggleTime()" ></td>
                                                                {/if}
                                                        {else}
                                                                {if $fldvalue eq 1}
                                                        <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"  checked></td>
                                                                {else}
                                                        <td width="30%" align=left class="dvtCellInfo"><input name="{$fldname}" type="checkbox"></td>
                                                                {/if}
                                                        {/if}
							{elseif $uitype eq 23 || $uitype eq 5 || $uitype eq 6}
							<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							<td width="30%" align=left class="dvtCellInfo">
							   {foreach key=date_value item=time_value from=$fldvalue}
								{assign var=date_val value="$date_value"}
								{assign var=time_val value="$time_value"}
							   {/foreach}
							<input name="{$fldname}" id="jscal_field_{$fldname}" type="text" style="border:1px solid #bababa;" size="11" maxlength="10" value="{$date_val}">
							<img src="{$IMAGE_PATH}calendar.gif" id="jscal_trigger_{$fldname}">
							{if $uitype eq 6}
							   <input name="time_start" style="border:1px solid #bababa;" size="5" maxlength="5" type="text" value="{$time_val}">
							{/if}
							{foreach key=date_format item=date_str from=$secondvalue}
                                                                {assign var=dateFormat value="$date_format"}
							        {assign var=dateStr value="$date_str"}
							{/foreach}
							{if $uitype eq 5 || $uitype eq 23}
							   <br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
							   {else}
							   <br><font size=1><em old="(yyyy-mm-dd)">({$dateStr})</em></font>
							{/if}
							<script type="text/javascript">
                					Calendar.setup ({ldelim}
								inputField : "jscal_field_{$fldname}", ifFormat : "{$dateFormat}", showsTime : false, button : "jscal_trigger_{$fldname}", singleClick : true, step : 1
									{rdelim})
     						        </script>

							
							</td>

							{elseif $uitype eq 63}
							  <td width="20%" class="dvtCellLabel" align=right>
							        {$fldlabel}
							  </td>
							  <td width="30%" align=left class="dvtCellInfo">
							        <input name="{$fldname}" type="text" size="2" value="{$fldvalue}">&nbsp;
							        <select name="duration_minutes">
						        	{foreach key=labelval item=selectval from=$secondvalue}
								<option value="{$labelval}" {$selectval}>{$labelval}</option>
								{/foreach}
								</select>

							{elseif $uitype eq 68 || $uitype eq 66 || $uitype eq 62}
							  <td width="20%" class="dvtCellLabel" align=right>
								<select name="parent_type" onChange='document.EditView.parent_name.value=""; document.EditView.parent_id.value=""'>
								{section name=combo loop=$fldlabel}
                                                                <option value="{$fldlabel_combo[combo]}" {$fldlabel_sel[combo]}>{$fldlabel[combo]}</option>
                                                                {/section}
								</select>
							  </td>
							<td width="30%" align=left class="dvtCellInfo">
							<input name="{$fldname}" type="hidden" value="{$secondvalue}"><input name="parent_name" readonly type="text" style="border:1px solid #bababa;" value="{$fldvalue}">
						&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
							
							{elseif $uitype eq 357}
								<td width="20%" class="dvtCellLabel" align=right>To:&nbsp;</td>
								<td width="90%" colspan="3">
								<input name="{$fldname}" type="hidden" value="{$secondvalue}">
								<textarea readonly name="parent_name" cols="70" rows="2">{$fldvalue}</textarea>&nbsp;
								<select name="parent_type">
									{foreach key=labelval item=selectval from=$fldlabel}
		                                                                <option value="{$labelval}" {$selectval}>{$labelval}</option>
                	                                                {/foreach}
                                                                </select>
								&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module="+ document.EditView.parent_type.value +"&action=Popup&html=Popup_picker&form=HelpDeskEditView","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.parent_id.value=''; this.form.parent_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
								<tr style="height:25px">
								<td width="20%" class="dvtCellLabel" align=right>CC:&nbsp;</td>	
								<td width="30%" align=left class="dvtCellInfo">
								<input name="ccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value=""></td>
								<td width="20%" class="dvtCellLabel" align=right>BCC:&nbsp;</td>
                                                                <td width="30%" align=left class="dvtCellInfo">
                                                                <input name="bccmail" type="text" class=detailedViewTextBox onFocus="this.className='detailedViewTextBoxOn'" onBlur="this.className='detailedViewTextBox'"  value=""></td>
								</tr>
				
 	                                                {elseif $uitype eq 59}
                                                          <td width="20%" class="dvtCellLabel" align=right>
                                                           {$fldlabel}</td>
                                                          <td width="30%" align=left class="dvtCellInfo">
                                                           <input name="{$fldname}" type="hidden" value="{$secondvalue}"><input name="product_name" readonly type="text" value="{$fldvalue}">&nbsp;<img src="{$IMAGE_PATH}select.gif" alt="Select" title="Select" LANGUAGE=javascript onclick='return window.open("index.php?module=Products&action=Popup&html=Popup_picker&form=HelpDeskEditView&popuptype=specific","test","width=600,height=400,resizable=1,scrollbars=1,top=150,left=200");' align="absmiddle" style='cursor:hand;cursor:pointer'>&nbsp;<input type="image" src="{$IMAGE_PATH}clear_field.gif" alt="Clear" title="Clear" LANGUAGE=javascript onClick="this.form.product_id.value=''; this.form.product_name.value=''; return false;" align="absmiddle" style='cursor:hand;cursor:pointer'></td>
		
							{elseif $uitype eq 55} 
                                                          <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
							  <td width="30%" align=left class="dvtCellInfo"><select name="salutationtype">
							  {foreach item=arr from=$fldvalue}
                                                              {foreach key=sel_value item=value from=$arr}
                                                              <option value="{$sel_value}" {$value}>{$sel_value}</option>
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

						{elseif $uitype eq 69}
						<td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
						<td colspan="3" width="30%" align=left class="dvtCellInfo">
						{if $MODULE eq 'Products'}
							<input name="imagelist" type="hidden" value="">
						    <div id="files_list" style="border: 1px solid grey; width: 500px; padding: 5px; background: rgb(255, 255, 255) none repeat scroll 0%; -moz-background-clip: initial; -moz-background-origin: initial; -moz-background-inline-policy: initial; font-size: x-small">Files Maximum 6
						    <input id="my_file_element" type="file" name="file_1" >
                            </div>
                            <script>
                            {*<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->*}
                            var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 6 );
                            {*<!-- Pass in the file element -->*}
                            multi_selector.addElement( document.getElementById( 'my_file_element' ) );
                            </script>
	                     </td>
                         {else}
                         <input name="{$fldname}"  type="file" value="{$secondvalue}"/><input type="hidden" name="id" value=""/>{$fldvalue}</td>
                         {/if}
                         {elseif $uitype eq 61}
                         <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
						 <td colspan="3" width="30%" align=left class="dvtCellInfo"><input name="{$fldname}"  type="file" value="{$secondvalue}"/><input type="hidden" name="id" value=""/>{$fldvalue}</td>

						{elseif $uitype eq 30}
                                                <td width="20%" class="dvtCellLabel" align=right>{$fldlabel}</td>
                                                <td colspan="3" width="30%" align=left class="dvtCellInfo">
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

							{/if}
							{/foreach}
							</tr>
							{/foreach}
							 <tr style="height:25px"><td>&nbsp;</td></tr>
							{/foreach}
							<tr>
								<td  colspan=4 style="padding:5px">
								<div align="center">
								{if $MODULE eq 'Emails'}
                                                                <input title="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_TITLE}" accessKey="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_KEY}" class="small" onclick="window.open('index.php?module=Users&action=lookupemailtemplates&entityid={$ENTITY_ID}&entity={$ENTITY_TYPE}','emailtemplate','top=100,left=200,height=400,width=300,menubar=no,addressbar=no,status=yes')" type="button" name="button" value="{$APP.LBL_SELECTEMAILTEMPLATE_BUTTON_LABEL}">
                                                                <input title="{$MOD.LBL_SEND}" accessKey="{$MOD.LBL_SEND}" class="small" onclick="this.form.action.value='Save';this.form.send_mail.value='true'; return formValidate()" type="submit" name="button" value="  {$MOD.LBL_SEND}  " >
                                                                {/if}
                                                                <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="small" onclick="this.form.action.value='Save';return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
                                                                <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="small" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
								</div>
								</td>
							</tr>
</table>
</td></tr></table>
</td></tr></table>
</div>	

</td></tr>
</table>
</div>
</td>
</tr>
</table>
</form>

{if ($MODULE eq 'Emails' || 'Notes') and ($FCKEDITOR_DISPLAY eq 'true')}
       <script type="text/javascript" src="include/fckeditor/fckeditor.js"></script>
       <script type="text/javascript" defer="1">

       var oFCKeditor = null;

       {if $MODULE eq 'Notes'}
               oFCKeditor = new FCKeditor( "notecontent" ) ;
       {/if}

       oFCKeditor.BasePath   = "include/fckeditor/" ;
       oFCKeditor.ReplaceTextarea() ;

       </script>
{/if}
<script>



        var fieldname = new Array({$VALIDATION_DATA_FIELDNAME})

        var fieldlabel = new Array({$VALIDATION_DATA_FIELDLABEL})

        var fielddatatype = new Array({$VALIDATION_DATA_FIELDDATATYPE})


</script>



