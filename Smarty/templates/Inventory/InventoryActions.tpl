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

<!-- Avoid this actions display for PriceBook module-->
{if $MODULE neq 'PriceBooks'}


<!-- Added this file to display the Inventory Actions based on the Inventory Modules -->
<table width="100%" border="0" cellpadding="5" cellspacing="0">
   <tr>
	<td>&nbsp;</td>
   </tr>
   <tr>
	<td align="left" class="genHeaderSmall">{$APP.LBL_ACTIONS}</td>
   </tr>



	<!-- Module based actions starts -->
	{if $MODULE eq 'Products'}
	   <!-- Product Actions starts -->
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">Create Quote</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">Create Invoice</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">Create SalesOrder</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List Pending Old Quotes</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List Pending Old Invoices</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List Pending Old SalesOrders</a> 
		</td>
	   </tr>
	   <!-- Product Actions ends -->

	{elseif $MODULE eq 'Vendors'}
	   <!-- Vendors Actions starts -->
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">Create PurchaseOrder</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List PurchaseOrders for this Vendor</a> 
		</td>
	   </tr>
	   <!-- Vendors Actions ends -->

	{elseif $MODULE eq 'PurchaseOrder'}
	   <!-- PO Actions starts -->
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List Other PurchaseOrders to this Vendor</a> 
		</td>
	   </tr>
	   <!-- PO Actions ends -->

	{elseif $MODULE eq 'SalesOrder'}
	   <!-- SO Actions starts -->
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List Linked Quotes</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List Linked Invoices</a> 
		</td>
	   </tr>
	   <!-- SO Actions ends -->

	{elseif $MODULE eq 'Quotes'}
	   <!-- Vendors Actions starts -->
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/> 
			<a href="javascript: document.DetailView.return_module.value='{$MODULE}'; document.DetailView.return_action.value='DetailView'; document.DetailView.convertmode.value='{$CONVERTMODE}'; document.DetailView.module.value='Invoice'; document.DetailView.action.value='EditView'; document.DetailView.return_id.value='{$ID}'; document.DetailView.submit();" class="webMnu">Generate Invoice</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;border-bottom:1px dotted #CCCCCC;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="javascript: document.DetailView.return_module.value='SalesOrder'; document.DetailView.return_action.value='DetailView'; document.DetailView.convertmode.value='quotetoso'; document.DetailView.module.value='SalesOrder'; document.DetailView.action.value='EditView'; document.DetailView.submit();" class="webMnu">Generate Sales Order</a> 
		</td>
	   </tr>
	   <!-- Vendors Actions ends -->

	{elseif $MODULE eq 'Invoice'}
	   <!-- Invoice Actions starts -->
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">List Linked Quotes</a> 
		</td>
	   </tr>
	   <tr>
		<td align="left" style="padding-left:10px;">
			<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
			<a href="#" class="webMnu">Generare SalesOrder</a> 
		</td>
	   </tr>
	   <!-- Invoice Actions ends -->

	{/if}

	<!-- Module based actions ends -->






   <tr>
	<td align="left">
		<span class="genHeaderSmall">Other Functions</span><br /> 
	</td>
   </tr>


<!-- 
   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Use Customer Name</a> 
	</td>
   </tr>
   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Use Shipping Address </a> 
	</td>
   </tr>
-->


<!-- This following Export To PDF link will come for PO, SO, Quotes and Invoice -->
{if $MODULE eq 'PurchaseOrder' || $MODULE eq 'SalesOrder' || $MODULE eq 'Quotes' || $MODULE eq 'Invoice'}

   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Export To PDF</a> 
	</td>
   </tr>

{/if}



   <!-- The following links are common to all the inventory modules -->
   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Print</a> 
	</td>
   </tr>
   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Email Now </a> 
	</td>
   </tr>
</table>


{/if}
