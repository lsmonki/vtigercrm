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

<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<style type="text/css">@import url(themes/blue/style.css);</style>

<table width="100%" border="0" cellpadding="0" cellspacing="0">
   <tr>
        {include file='SettingsMenu.tpl'}
	<td width="75%" valign="top">




<!-- Edit View -->
{if $EDIT_MODE eq 'true'}
<!-- This table is used to display the Tax Configuration values-->
<form name="EditTax" method="POST" action="index.php">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="">
<input type="hidden" name="save_tax" value="">
<input type="hidden" name="edit_tax" value="">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
   <tr>
	<td>&nbsp;</td>
	<td align="right">
		{$APP.LBL_VAT} :
	</td>
	<td align="left">
		<input name="VAT" id="VAT" type="text" value="{$TAX_VALUES.VAT}">
	</td>
   </tr>
   <tr>
	<td>&nbsp;</td>
	<td align="right">
		{$APP.LBL_SALES} {$APP.LBL_TAX} :
	</td>
	<td align="left">
		<input name="Sales" id="Sales" type="text" value="{$TAX_VALUES.Sales}">
	</td>
   </tr>
   <tr>
	<td>&nbsp;</td>
	<td align="right">
		{$APP.LBL_SERVICE} {$APP.LBL_TAX} : 
	</td>
	<td align="left">
		<input name="Service" id="Service" type="text" value="{$TAX_VALUES.Service}">
	</td>
   </tr>
   <tr>
	<td>&nbsp;</td>
	<td align="center">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="small" onclick="this.form.action.value='TaxConfig'; this.form.save_tax.value='true'; return formValidate()" type="submit" name="button" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " style="width:70px" >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="small" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
	</td>
   </tr>
</table>
</form>
<!-- Upto this added to display the Tax configuration Edit View -->

<!-- ListView -->
<!-- Display the List of Taxes and Values - ListView -->
{else}
<form name="ListTax" method="POST" action="index.php">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="">
<input type="hidden" name="save_tax" value="">
<input type="hidden" name="edit_tax" value="">
<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
   <tr>
	<td>&nbsp;</td>
	<td align="right">
		{$APP.LBL_VAT} :
	</td>
	<td align="left">
		{$TAX_VALUES.VAT}
	</td>
   </tr>
   <tr>
	<td>&nbsp;</td>
	<td align="right">
		{$APP.LBL_SALES} {$APP.LBL_TAX} :
	</td>
	<td align="left">
		{$TAX_VALUES.Sales}
	</td>
   </tr>
   <tr>
	<td>&nbsp;</td>
	<td align="right">
		{$APP.LBL_SERVICE} {$APP.LBL_TAX} : 
	</td>
	<td align="left">
		{$TAX_VALUES.Service}
	</td>
   </tr>
   <tr>
	<td>&nbsp;</td>
	<td align="center">
		<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="small" onclick="this.form.action.value='TaxConfig'; this.form.edit_tax.value='true';" type="submit" name="button" value="  {$APP.LBL_EDIT_BUTTON_LABEL}  " style="width:70px" >
		<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="small" onclick="window.history.back()" type="button" name="button" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " style="width:70px">
	</td>
   </tr>
</table>
</form>

{/if}



	</td>
   </tr>
</table>

