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

<!-- Added this file to display and hanld the Product Details in Inventory module  -->

   <tr>
	<td colspan="4" align="left">



<table width="100%"  border="0" align="center" cellpadding="5" cellspacing="0" class="crmTable" id="proTab">
   <!-- Header for the Product Details -->
   <tr>
	<td width=40% class="small crmTableColHeading"><font color='red'>*</font><b>{$APP.LBL_PRODUCT_NAME}</b></td>
	<td width=10% class="small crmTableColHeading"><b>{$APP.LBL_QTY_IN_STOCK}</b></td>
	<td width=10% class="small crmTableColHeading"><b>{$APP.LBL_QTY}</b></td>
	<td width=10% class="small crmTableColHeading" align="right"><b>{$APP.LBL_LIST_PRICE}</b></td>
	<td width=10% nowrap class="small crmTableColHeading" align="right"><b>{$APP.LBL_TOTAL}</b></td>
	<td width=10% valign="top" class="small crmTableColHeading" align="right"><b>{$APP.LBL_NET_PRICE}</b></td>
	<td width=5% valign="top" class="small crmTableColHeading" align="right"><b>{$APP.LBL_TOOLS}</b></td>
   </tr>
   <!-- Product Details First row - Starts -->
   <tr valign="top" id="row1">
	<!-- row1, column1 - Product Name-->
	<td class="crmTableRow small lineOnTop">
		<table width="100%"  border="0" cellspacing="0" cellpadding="1">
		   <tr>
			<td class="small">
				<input type="text" id="productName1" name="productName1" class="small" style="width:70%" value="{$PRODUCT_NAME}" readonly />
				<input type="hidden" id="hdnProductId1" name="hdnProductId1" value="{$PRODUCT_ID}">
				<img src="{$IMAGE_PATH}search.gif" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}',1)" />
			</td>
		   </tr>
		   <tr>
			<td class="small" id="setComment">
				<textarea id="comment1" name="comment1" class=small style="width:70%;height:40px"></textarea>
				<br>
				[<a href="#">Clear Comment</a>]
			</td>
		   </tr>
		</table>
	</td>
	<!-- row1, column2 - Quantity in Stock -->
	<td id="qtyInStock1" class="crmTableRow small lineOnTop" >{$QTY_IN_STOCK}</td>
	<!-- row1, column 3 - Quantity -->
	<td class="crmTableRow small lineOnTop">
		<input id="qty1" name="qty1" type="text" class="small " style="width:50px" onfocus="this.className='detailedViewTextBoxOn'" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this,1)" value=""/>
	</td>
	<!-- row1, column 4 - List Price, Discount, Total After Discount and Tax -->
	<td class="crmTableRow small lineOnTop" align="right">
		<table width="100%" cellpadding="0" cellspacing="0">
		   <tr>
			<td align="right">
				<input id="listPrice1" name="listPrice1" value="{$UNIT_PRICE}" type="text" class="small " style="width:70px" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)"/>&nbsp;<img src="{$IMAGE_PATH}pricebook.gif" onclick="priceBookPickList(this,1)">
			</td>
		   </tr>
		   <tr>
			<td align="right" style="padding:5px;" nowrap>
				(-)&nbsp;<b><a href="#">{$APP.LBL_DISCOUNT}</a> : </b>
			</td>
		   </tr>
		   <tr>
			<td align="right" style="padding:5px;" nowrap>
				<b>{$APP.LBL_TOTAL_AFTER_DISCOUNT} :</b>
			</td>
		   </tr>
		   <tr>
			<td align="right" style="padding:5px;" nowrap>
				(+)&nbsp;<b><a href="#">{$APP.LBL_TAX}</a> : </b>
			</td>
		   </tr>
		</table> 
	</td>
	<!-- row1, column 5 - Product Total -->
	<td class="crmTableRow small lineOnTop" align="right">
		<table width="100%" cellpadding="5" cellspacing="0">
		   <tr>
			<td id="productTotal1" align="right">&nbsp;</td>
		   </tr>
		   <tr>
			<td id="discount1" align="right">100</td>
		   </tr>
		   <tr>
			<td id="totalAfterDiscount1" align="right">&nbsp;</td>
		   </tr>
		   <tr>
			<td id="tax1" align="right">100</td>
		   </tr>
		</table>
	</td>
	<!-- row1, column 6 - Net Price -->
	<td id="netPrice1"  valign="bottom" class="crmTableRow small lineOnTop" align="right"><b>&nbsp;</b></td>
	<td  class="crmTableRow small lineOnTop">&nbsp;
		<input type="hidden" id="hdnRowStatus1" name="hdnRowStatus1">
	</td>
   </tr>
   <!-- Product Details First row - Ends -->

   <!-- Add Product Button -->
   <tr>
	<td colspan="7">
		{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			<input type="button" name="Button" class="small" value="{$APP.LBL_ADD_PRODUCT}" onclick="fnAddProductRow('{$MODULE}');" />
		{else}
			<input type="button" name="Button" class="small" value="{$APP.LBL_ADD_PRODUCT}" onclick="fnAddProductRow('{$MODULE}');" />
		{/if}
	</td>
   </tr>



   <!-- Product Details Final Total Discount, Tax and Shipping&Hanling  - Starts -->
   <tr valign="top">
	<td colspan="6" class="crmTableRow small lineOnTop" align="right"><b>{$APP.LBL_NET_TOTAL}</b></td>
	<td id="netTotal" class="crmTableRow small lineOnTop" align="right"><b>&nbsp;</b></td>
   </tr>
   <!-- Net Tax (GROUP) -->
   <tr valign="top">
	<td colspan="6" class="crmTableRow small lineOnTop" align="right">(-)&nbsp;<b><a href="#">{$APP.LBL_DISCOUNT}</a></b></td>
	<td id="finalDiscount" class="crmTableRow small lineOnTop" align="right"><b>&nbsp;</b></td>
   </tr>
   <!-- END -->
   <tr valign="top"><!-- style="display:none;" -->
	<td colspan="6" class="crmTableRow small lineOnTop" align="right">(+)&nbsp;<b><a href="#">{$APP.LBL_TAX}</a></b></td>
	<td id="finalTax" class="crmTableRow small lineOnTop" align="right"><b>&nbsp;</b></td>
   </tr>
   <tr valign="top">
	<td colspan="6" class="crmTableRow small" align="right">
		(+)&nbsp;<b>{$APP.LBL_SHIPPING_AND_HANDLING_CHARGES} : </b>
	</td>
	<td id="shipping_handling_charge" class="crmTableRow small" align="right">
		<input type="text" class="small" style="width:40px" value="">
	</td>
   </tr>
   <tr valign="top">
	<td colspan="6" class="crmTableRow small" align="right">
		(+)&nbsp;<b><a href="#">{$APP.LBL_TAX_FOR_SHIPPING_AND_HANDLING} : </a></b>
	</td>
	<td id="shipping_handling_tax" class="crmTableRow small" align="right">&nbsp;</td>
   </tr>
   <tr valign="top">
	<td colspan="6" class="crmTableRow small" align="right">
		{$APP.LBL_ADJUSTMENT}
		<select name="adjustmentType" class=small>
			<option value="+">Add</option>
			<option value="-">Deduct</option>
		</select>
	</td>
	<td class="crmTableRow small" align="right">
		<input id="adjustment" name="adjustment" type="text" class="small" style="width:40px" value="">
	</td>
   </tr>
   <tr valign="top">
	<td colspan="6" class="crmTableRow big lineOnTop" align="right"><b>{$APP.LBL_GRAND_TOTAL}</b></td>
	<td id="grandTotal" name="grandTotal" class="crmTableRow big lineOnTop" align="right"><b>&nbsp;</b></td>
   </tr>
</table>
		<input type="hidden" name="totalProductCount" id="totalProductCount" value="">




	</td>
   </tr>





<!--
   <tr>
	<td colspan="4" class="detailedViewHeader">
		<b>{$APP.LBL_PRODUCT_DETAILS}</b>
		<select id="taxtype" name="taxtype">
			<OPTION name="group" value="group">Group</OPTION>
			<OPTION name="individual" value="individual">Individual</OPTION>
		</select>
	</td>
   </tr>

   <tr>
	<td colspan=4>
		<table class="prdTab small"  border="0" cellspacing="0" cellpadding="5" id="proTab">
		   <tr>
			<th width="20%"><font color='red'>*</font>{$APP.LBL_PRODUCT}</th>

			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			<th width="8%" nowrap>{$APP.LBL_QTY_IN_STOCK}</th>
			{/if}

			<th width="8%"><font color='red'>*</font>{$APP.LBL_QTY}</th>
			<th width="10%">{$APP.LBL_UNIT_PRICE}</th>
			<th width="15%"><font color='red'>*</font>{$APP.LBL_LIST_PRICE}</th>
			<th width="24%">{$APP.LBL_TAX_CALCULATION}</th>
			<th width="10%">{$APP.LBL_TOTAL}</th>

			<th width="5%">&nbsp;</th>
		   </tr>
		   <tr id="row1" class="dvtCellLabel">
			<td nowrap valign="top">
				<input type="text"  id="txtProduct1" name="txtProduct1" class="txtBox" value="{$PRODUCT_NAME}" readonly />&nbsp;<img src="themes/blue/images/search.gif" style="cursor: pointer;" align="absmiddle" onclick="productPickList(this,'{$MODULE}')" />
			</td>

			{if $MODULE eq 'Quotes' || $MODULE eq 'SalesOrder' || $MODULE eq 'Invoice'}
			   <td style="padding:3px;" id="qtyInStock1" valign="top" value="{$QTY_IN_STOCK}">&nbsp;</td>
			{/if}

			<td valign="top">
				<input type="text" id="txtQty1" name="txtQty1" class="detailedViewTextBox" onfocus="this.className='detailedViewTextBoxOn'" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)" />
			</td>
			<td style="padding:3px;"  id="unitPrice1" valign="top" value="{$UNIT_PRICE}">&nbsp;

			</td>
			<td nowrap valign="top">
				<input type="text" id="txtListPrice1" name="txtListPrice1" value="{$UNIT_PRICE}" class="txtBox" onBlur="FindDuplicate(); settotalnoofrows(); calcTotal(this)"/>&nbsp;<img src="themes/blue/images/pricebook.gif" onclick="priceBookPickList(this)" style="cursor: pointer;" title="Price Book" align="absmiddle" />
			</td>
			<!-- Added for Tax calculation-->
			<td valign="top" style="padding-bottom:5px;">
				<input type="text" id="txtTaxTotal1" name="txtTaxTotal1" value="" class="detailedViewTextBox" style="width:65%;">
				<input type="hidden" id="hdnTaxTotal1" name="hdnTaxTotal1">
				&nbsp;<input type="button" name="showTax" value=" ... "  class="classBtnSmall"  onclick="fnshow_Hide('tax_Lay1');">

				<!-- This div is added to display the tax informations -->
				<div id="tax_Lay1" style="width:93%;position:relative;border:1px dotted #CCCCCC;display:none;background-color:#FFFFCC;top:5px;padding:5px;" align="center">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" class="small">
					   <tr id="vatrow1">
						<td align="left" width="40%" style="border:0px solid red;"><input type="text" id="txtVATTax1" name="txtVATTax1" value="{$VAT_TAX}" class="txtBox" onBlur="ValidateTax('txtVATTax1'); calcTotal(this);"/>%&nbsp;</td>
						<td width="20%" align="right" style="border:0px solid red;">&nbsp;{$APP.LBL_VAT}</td>
						<td align="left" width="40%" style="border:0px solid red;"><input type="text" id="txtVATTaxTotal1" name="txtVATTaxTotal1" class="txtBox" onBlur="ValidateTax('txtVATTaxTotal1'); calcTotal(this);"/></td>
					   </tr>
					   <tr id="salesrow1">
						<td align="left" style="border:0px solid red;"><input type="text" id="txtSalesTax1" name="txtSalesTax1" value="{$SALES_TAX}" class="txtBox" onBlur="ValidateTax('txtSalesTax1'); calcTotal(this);"/>%&nbsp;</td>
						<td align="right" style="border:0px solid red;">&nbsp;{$APP.LBL_SALES}</td>
						<td align="left" style="border:0px solid red;"><input type="text" id="txtSalesTaxTotal1" name="txtSalesTaxTotal1" class="txtBox" onBlur="ValidateTax('txtSalesTaxTotal1'); calcTotal(this);"/></td>
					   </tr>
					   <tr id="servicerow1">
						<td align="left" style="border:0px solid red;"><input type="text" id="txtServiceTax1" name="txtServiceTax1" value="{$SERVICE_TAX}" class="txtBox" onBlur="ValidateTax('txtServiceTax1'); calcTotal(this);"/>%&nbsp;</td>
						<td align="right" style="border:0px solid red;">&nbsp;{$APP.LBL_SERVICE}</td>
						<td align="left" style="border:0px solid red;"><input type="text" id="txtServiceTaxTotal1" name="txtServiceTaxTotal1" class="txtBox" onBlur="ValidateTax('txtServiceTaxTotal1'); calcTotal(this);"/></td>
					   </tr>
					</table>
				</div>
				<!-- This above div is added to display the tax informations --> 

			</td>
			<td style="padding:3px;" id="total1">&nbsp;</td>
			<td>
				<input type="hidden" id="hdnProductId1" name="hdnProductId1" value="{$PRODUCT_ID}">
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
-->
<style>
	.discountUI{ldelim}
		border:3px solid #CCCCCC;
		width:200px;
		padding:5px;
		position:absolute;
		left:0px;
		top:0px;
		
	{rdelim}
</style>
<div id="discountUI" class="discountUI">
	<table width="100%" border="0" cellpadding="5" cellspacing="0">
		<tr>
			<td nowrap class="small">Discount Details</td>
		</tr>
	</table>
</div>
