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


<!-- Added to display the Product Details -->
		<table class="prdTab"  border="0" cellspacing="0" cellpadding="2" id="proTab">
		   <tr>
			<th colspan="7" class="detailedViewHeader">
				<b>{$APP.LBL_PRODUCT_DETAILS}</b>
			</th>
		   </tr>
		   <tr>
			<th width="20%"><font color='red'>*</font>{$APP.LBL_PRODUCT}</th>

			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			   <th width="12%">{$APP.LBL_QTY_IN_STOCK}</th>
			{/if}

			<th width="10%"><font color='red'>*</font>{$APP.LBL_QTY}</th>
			<th width="10%">{$APP.LBL_UNIT_PRICE}</th>
			<th width="19%"><font color='red'>*</font>{$APP.LBL_LIST_PRICE}</th>
			<th width="10%">{$APP.LBL_TOTAL}</th>

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

		   <tr id="row{$row_no}" class="dvtCellLabel">
			<td nowrap>
				<input type="text" name="{$txtProduct}" value="{$data.$txtProduct}" class="detailedViewProdTextBox" readonly />&nbsp;<img src="themes/blue/images/search.gif" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}')" />
			</td>

			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			   <td style="padding:3px;"><div id="{$qtyInStock}">{$data.$qtyInStock}</div>&nbsp;</td>
			{/if}

			<td>
				<input type="text" name="{$txtQty}" value="{$data.$txtQty}" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)" />
			</td>
			<td style="padding:3px;">
				<div id="{$unitPrice}">{$data.$unitPrice}</div>&nbsp;
			</td>
			<td nowrap>
				<input type="text" name="{$txtListPrice}" value="{$data.$txtListPrice}" class="detailedViewProdTextBox" readonly onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)"/>&nbsp;
				<img src="themes/blue/images/pricebook.gif" onclick="priceBookPickList(this)" style="cursor: pointer;" title="Price Book" align="absmiddle" />
			</td>
			<td style="padding:3px;">
				<div id="{$total}" align="right">{$data.$total}</div>&nbsp;
			</td>
			<td>
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



