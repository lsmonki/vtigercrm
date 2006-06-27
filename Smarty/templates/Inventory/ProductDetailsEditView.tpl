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

<script type="text/javascript" src="include/js/Inventory.js"></script>
<script type="text/javascript" src="include/js/general.js"></script>
<!-- Added to display the Product Details -->
		<table class="prdTab small"  border="0" cellspacing="0" cellpadding="2">
		 <tr>
			<th colspan="8" class="detailedViewHeader">
				<b>{$APP.LBL_PRODUCT_DETAILS}</b>
			</th>
		   </tr>
		   <tr>
		</table>
		<table class="prdTab small"  border="0" cellspacing="0" cellpadding="5" id="proTab">
		   <tr>
			<th width="22%"><font color='red'>*</font>{$APP.LBL_PRODUCT}</th>

			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			   <th width="8%" nowrap>{$APP.LBL_QTY_IN_STOCK}</th>
			{/if}

			<th width="8%"><font color='red'>*</font>{$APP.LBL_QTY}</th>
			<th width="10%">{$APP.LBL_UNIT_PRICE}</th>
			<th width="17%"><font color='red'>*</font>{$APP.LBL_LIST_PRICE}</th>
			<th width="22%">{$APP.LBL_TAX_CALCULATION}</th>
			<th width="8%">{$APP.LBL_TOTAL}</th>

			<th width="5%">&nbsp;</th>
		   </tr>

		   {foreach key=row_no item=data from=$ASSOCIATEDPRODUCTS}
			{assign var="txtProduct" value="txtProduct"|cat:$row_no}
			{assign var="qtyInStock" value="qtyInStock"|cat:$row_no}
			{assign var="txtQty" value="txtQty"|cat:$row_no}
			{assign var="unitPrice" value="unitPrice"|cat:$row_no}
			{assign var="txtListPrice" value="txtListPrice"|cat:$row_no}
			{assign var="total" value="total"|cat:$row_no}
			{assign var="hdnProductId" value="hdnProductId"|cat:$row_no}
			{assign var="hdnRowStatus" value="hdnRowStatus"|cat:$row_no}
			{assign var="hdnTotal" value="hdnTotal"|cat:$row_no}

			{assign var="txtVATTax" value="txtVATTax"|cat:$row_no}
			{assign var="txtSalesTax" value="txtSalesTax"|cat:$row_no}
			{assign var="txtServiceTax" value="txtServiceTax"|cat:$row_no}

		   <tr id="row{$row_no}" class="dvtCellLabel">
			<td nowrap valign="top">
				<input type="text" name="{$txtProduct}" value="{$data.$txtProduct}" class="txtBox" readonly />&nbsp;<img src="themes/blue/images/search.gif" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}')" />
			</td>

			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			   <td style="padding:3px;" id="{$qtyInStock}" valign="top">{$data.$qtyInStock}&nbsp;</td>
			{/if}

			<td valign="top">
				<input type="text" name="{$txtQty}" value="{$data.$txtQty}" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)" />
			</td>
			<td style="padding:3px;" id="{$unitPrice}" valign="top">
				{$data.$unitPrice}&nbsp;
			</td>
			<td nowrap valign="top">
				<input type="text" name="{$txtListPrice}" value="{$data.$txtListPrice}" class="txtBox" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)"/>&nbsp;
				<img src="themes/blue/images/pricebook.gif" onclick="priceBookPickList(this)" style="cursor: pointer;" title="Price Book" align="absmiddle" />
			</td>
			<!-- Added for Tax calculation-->
			<td valign="top" style="padding-bottom:5px;">
				<input type="text" id="txtTaxTotal{$row_no}" name="txtTaxTotal{$row_no}" value="" class="detailedViewTextBox" style="width:65%;">
				<input type="hidden" id="hdnTaxTotal{$row_no}" name="hdnTaxTotal{$row_no}">
				&nbsp;<input type="button" name="showTax" value=" ... "  class="classBtnSmall"  onclick="fnshow_Hide('tax_Lay{$row_no}');">

				<!-- This div is added to display the tax informations -->
				<div id="tax_Lay{$row_no}" style="width:93%;position:relative;border:1px dotted #CCCCCC;display:none;background-color:#FFFFCC;top:5px;padding:5px;" align="center">
					<table width="100%" border="0" cellpadding="2" cellspacing="0" class="small">
					   <tr id="vatrow{$row_no}">
						<td align="left" width="40%" style="border:0px solid red;"><input type="text" id="txtVATTax{$row_no}" name="txtVATTax{$row_no}" class="txtBox" value="{$data.$txtVATTax}" onBlur="ValidateTax('txtVATTax{$row_no}'); calcTotal(this);"/>%&nbsp;</td>
						<td width="20%" align="right" style="border:0px solid red;">{$APP.LBL_VAT}</td>
						<td align="left" width="40%" style="border:0px solid red;"><input type="text" id="txtVATTaxTotal{$row_no}" name="txtVATTaxTotal{$row_no}" class="txtBox" value="" onBlur="ValidateTax('txtVATTaxTotal{$row_no}'); calcTotal(this);"/></td>
					   </tr>
					   <tr id="salesrow{$row_no}">
						<td align="left" style="border:0px solid red;"><input type="text" id="txtSalesTax{$row_no}" name="txtSalesTax{$row_no}" class="txtBox" value="{$data.$txtSalesTax}" onBlur="ValidateTax('txtSalesTax{$row_no}'); calcTotal(this);"/>%&nbsp;</td>
						<td  align="right" style="border:0px solid red;">{$APP.LBL_SALES}</td>
						<td align="left" style="border:0px solid red;"><input type="text" id="txtSalesTaxTotal{$row_no}" name="txtSalesTaxTotal{$row_no}" class="txtBox" value="" onBlur="ValidateTax('txtSalesTaxTotal{$row_no}'); calcTotal(this);"/></td>
					   </tr>
					   <tr id="servicerow{$row_no}">
						<td align="left" style="border:0px solid red;"><input type="text" id="txtServiceTax{$row_no}" name="txtServiceTax{$row_no}" class="txtBox" value="{$data.$txtServiceTax}" onBlur="ValidateTax('txtServiceTax{$row_no}'); calcTotal(this);"/>%&nbsp;</td>
						<td align="right" style="border:0px solid red;">{$APP.LBL_SERVICE}</td>
						<td align="left" style="border:0px solid red;"><input type="text" id="txtServiceTaxTotal{$row_no}" name="txtServiceTaxTotal{$row_no}" class="txtBox" value="" onBlur="ValidateTax('txtServiceTaxTotal{$row_no}'); calcTotal(this);"/></td>
					   </tr>
					</table>
				</div>
				<!-- Added to calculate the tax and total values when page loads -->
				<script>calcTotal(getObj("txtVATTax{$row_no}"));</script>
				<!-- This above div is added to display the tax informations --> 


			<td style="padding:3px;" valign="top">
				<div id="{$total}" align="right">{$data.$total}</div>&nbsp;
			</td>
			<td valign="top">
				<input type="hidden" id="{$hdnProductId}" name="{$hdnProductId}" value="{$data.$hdnProductId}">
				<input type="hidden" id="{$hdnRowStatus}" name="{$hdnRowStatus}">
				<input type="hidden" id="{$hdnTotal}" name="{$hdnTotal}" value="{$data.$hdnTotal}">&nbsp;
			</td>
		   </tr>
		   {/foreach}
		</table>
	</td>
   </tr>
   <tr>
	<td colspan=4>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		   <tr>

		      {if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			<td>
				<input type="button" name="Button" class="small" value="{$APP.LBL_ADD_PRODUCT}" onclick="fnAddRow('{$MODULE}');"/>
			</td>
		      {else}
			<td>
				<input type="button" name="Button" class="small" value="{$APP.LBL_ADD_PRODUCT}" onclick="fnAddRowForPO('{$MODULE}');" />
			</td>
		      {/if}

			<td width="35%">&nbsp;</td>
			<td style="text-align:right;padding:5px;"><b>{$APP.LBL_SUB_TOTAL}</b></td>
			<td style="text-align:left;padding:5px;">
				<input type="text" name="subTotal" value="{$SUBTOTAL}" class="detailedViewTextBox" readonly/>
			</td>
			<td width="5%">&nbsp;</td>
		   </tr>
		   <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td style="text-align:right;padding:5px;"><b>{$APP.LBL_TAX}</b></td>
			<td style="text-align:left;padding:5px;"><input type="text" name="txtTax" id="txtTax" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBox'" value="{$TAXVALUE}" onblur="calcGrandTotal()" /></td>
		        <td>&nbsp;</td>
		   </tr>
		   <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td style="text-align:right;padding:5px;"><b>{$APP.LBL_ADJUSTMENT}</b></td>
			<td style="text-align:left;padding:5px;"><input type="text" name="txtAdjustment" id="txtAdjustment" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBox'" value="{$ADJUSTMENTVALUE}" onblur="calcGrandTotal()" /></td>
			<td>&nbsp;</td>
		   </tr>
		   <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td style="text-align:right;padding:5px;"><b>{$APP.LBL_GRAND_TOTAL}</b></td>
			<td style="text-align:left;padding:5px;"><input type="text" name="grandTotal"  value="{$GRANDTOTAL}" class="detailedViewTextBox"  readonly /></td>
			<td>&nbsp;</td>
		   </tr>
		</table>
		<script>
			rowCnt = {$row_no};
			//rowCnt = document.getElementById('proTab').rows.length -2;
		</script>
		<input type="hidden" name="hdnSubTotal" id="hdnSubTotal" value="{$SUBTOTAL}">
		<input type="hidden" name="hdnGrandTotal" id="hdnGrandTotal" value="{$GRANDTOTAL}">
		<input type="hidden" name="totalProductCount" id="totalProductCount" value="{$row_no}">
<!-- Upto this Added to display the Product Details -->



