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


<!-- Added this file to display the Inventory Actions based on the Inventory Modules -->
<table width="100%" border="0" cellpadding="5" cellspacing="0">
   <tr>
	<td>&nbsp;</td>
   </tr>
   <tr>
	<td align="left" class="genHeaderSmall">{$APP.LBL_ACTIONS}</td>
   </tr>
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
   <tr>
	<td align="left">
		<span class="genHeaderSmall">Find Other Quotes</span><br /> 
		You can find similar quotes using information of this quote 
	</td>
   </tr>
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
   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Use Billing Address </a> 
	</td>
   </tr>
   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Use Product 1 </a> 
	</td>
   </tr>
   <tr>
	<td align="left" style="padding-left:10px;">
		<img src="{$IMAGE_PATH}pointer.gif" hspace="5" align="absmiddle"/>
		<a href="#" class="webMnu">Use Product 2 </a> 
	</td>
   </tr>
</table>

