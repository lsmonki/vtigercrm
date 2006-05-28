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
<style type="text/css">@import url(themes/blue/style.css);</style>
<table width="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
	{include file='SettingsMenu.tpl'}
<td width="75%" valign="top">

<table width="100%" border="0" cellpadding="0" cellspacing="0" height="100%">
<form action="index.php" method="post" name="tandc">
<input type="hidden" name="module" value="Users">
<input type="hidden" name="action">
<input type="hidden" name="inv_terms_mode">
<input type="hidden" name="parenttab" value="Settings">
<tr>
<td class="showPanelBg" valign="top" width="100%" colspan="3" style="padding-left:20px; "><br/>
<span class="lvtHeaderText"><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_COMMUNICATION_TEMPLATES} > {$MOD.INVENTORYTERMSANDCONDITIONS}</b></span>
<hr noshade="noshade" size="1" />
</td>
</tr>
<tr>
<td width="75%" style="padding-left:20px;" valign="top">

	<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
	<tbody><tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="7"><img src="{$IMAGE_PATH}top_left.jpg" align="top"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif; height: 6px;" bgcolor="#ebebeb"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="8"><img src="{$IMAGE_PATH}top_right.jpg" align="top" height="6" width="8"></td>
	</tr>
	<tr>

	<td bgcolor="#ebebeb" width="7"></td>
	<td style="padding-left: 10px; padding-top: 10px; vertical-align: top;" bgcolor="#ececec">
	<table border="0" cellpadding="10" cellspacing="0" width="100%" class="small">
	<tbody><tr>
	<td rowspan="4" bgcolor="#ffffff" width="30%" valign="bottom" background="{$IMAGE_PATH}Inventory_top.gif" style="background-position:top right;background-repeat:no-repeat;">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<tr>
						<td background="{$IMAGE_PATH}Inventory_btm.gif" style="background-position:bottom right;background-repeat:no-repeat; " height="350">&nbsp;</td>
				</tr>
		</table>
	</td>
	{if $INV_TERMS_MODE eq 'view'}
	<td colspan="2" style="padding-top: 0px;" align="right" width="70%">
	<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='OrganizationTermsandConditions';this.form.inv_terms_mode.value='edit'" type="submit" name="Edit" value="{$APP.LBL_EDIT_BUTTON_LABEL}"><br><hr>
	</td>
	{else}	
	<td colspan="2" style="padding-top: 0px;" align="left" width="75%">
	<b>{$MOD.INVENTORYTERMSANDCONDITIONS}</b><br><hr>
	</td>
	{/if}
	</tr>
	
	<tr>
	<td align="right" valign="top" width="25%"><b>{$CMOD.INV_TANDC} : </b></td>
	<td align="left" width="75%">
	{if $INV_TERMS_MODE eq 'view'}
	{$INV_TERMSANDCONDITIONS}
	{else}
	<textarea name="inventory_tandc" class="txtBox" rows="10">{$INV_TERMSANDCONDITIONS}</textarea>
	{/if}
	</tr>
	<tr><td colspan="2" width="75%">&nbsp; </td></tr>
	<tr><td colspan="2" align="center"width="75%"><hr> <br>
	{if $INV_TERMS_MODE neq 'view'}
	<input title="{$APP.LBL_SAVE_BUTTON_LABEL}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="classBtn" type="submit" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}" onclick="this.form.action.value='savetermsandconditions';">&nbsp;&nbsp;&nbsp;
    <input title="{$APP.LBL_CANCEL_BUTTON_LABEL}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="window.history.back()" type="button" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
	{/if}	

	</td></tr>
	</tbody></table>
	</td>
	<td bgcolor="#ebebeb" width="8"></td>
	</tr>

	<tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="7"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"></td>
	<td style="font-size: 1px;" bgcolor="#ececec" height="8"></td>
	
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="8"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom"></td>
	</tr>
	</tbody></table>

</td>
<td width="1%" style="border-right:1px dotted #CCCCCC;">&nbsp;</td>
</tr>
</form>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
	{include file='SettingsSubMenu.tpl'}

