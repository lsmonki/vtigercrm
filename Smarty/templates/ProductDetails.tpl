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

<!-- Added this file to display and hanld the Product Details in Inventory module  -->

   <tr>
	<td colspan="4" class="detailedViewHeader">
		<b>{$APP.LBL_PRODUCT_DETAILS}</b>
	</td>
   </tr>

   <tr>
	<td colspan=4>
		<table class="prdTab small"  border="0" cellspacing="0" cellpadding="2" id="proTab">
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
		   <tr id="row1" class="dvtCellLabel">
			<td nowrap>
				<input type="text"  id="txtProduct1" name="txtProduct1" class="txtBox" readonly />&nbsp;<img src="themes/blue/images/search.gif" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}')" />
			</td>

			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			   <td style="padding:3px;"><div id="qtyInStock1"></div>&nbsp;</td>
			{/if}

			<td>
				<input type="text" id="txtQty1" name="txtQty1" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)" />
			</td>
			<td style="padding:3px;">
				<div id="unitPrice1"></div>&nbsp;
			</td>
			<td nowrap>
				<input type="text" id="txtListPrice1" name="txtListPrice1" class="txtBox" readonly onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)"/>&nbsp;<img src="themes/blue/images/pricebook.gif" onclick="priceBookPickList(this)" style="cursor: pointer;" title="Price Book" align="absmiddle" />
			</td>
			<td style="padding:3px;">
				<div id="total1" align="right"></div>&nbsp;
			</td>
			<td>
				<input type="hidden" id="hdnProductId1" name="hdnProductId1">
				<input type="hidden" id="hdnRowStatus1" name="hdnRowStatus1">
				<input type="hidden" id="hdnTotal1" name="hdnTotal1">&nbsp;
			</td>
		   </tr>
		</table>
	</td>
   </tr>
   <tr>
	<td colspan=4>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		   <tr>
			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			   <td>
				<input type="button" name="Button" class="small" value="{$APP.LBL_ADD_PRODUCT}" onclick="fnAddRow('{$MODULE}');" />
			   </td>
			{else}
			   <td>
				<input type="button" name="Button" class="small" value="{$APP.LBL_ADD_PRODUCT}" onclick="fnAddRowForPO('{$MODULE}');" />
			   </td>
			{/if}

			<td width="35%">&nbsp;</td>
			<td style="text-align:right;padding:5px;">
				<b>{$APP.LBL_SUB_TOTAL}</b>
			</td>
			<td style="text-align:left;padding:5px;">
				<input type="text" name="subTotal"  class="detailedViewTextBox" readonly/>
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
			<td style="text-align:left;padding:5px;"><input type="text" name="grandTotal"  class="detailedViewTextBox"  readonly /></td>
			<td>&nbsp;</td>
		   </tr>
		</table>

		<input type="hidden" name="hdnSubTotal" id="hdnSubTotal" value="">
		<input type="hidden" name="hdnGrandTotal" id="hdnGrandTotal" value="">
		<input type="hidden" name="totalProductCount" id="totalProductCount" value="">
	</td>
   </tr>

