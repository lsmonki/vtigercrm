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
		<br/>
		<span class="lvtHeaderText">
			<b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS} </a> > {$MOD.LBL_CONFIGURATION} > {$APP.LBL_TAX} {$MOD.LBL_CONFIGURATION}</b>
		</span>

		<hr noshade="noshade" size="1" />




{if $EDIT_MODE eq 'true'}
	{assign var=formname value='EditTax'}
{else}
	{assign var=formname value='ListTax'}
{/if}


<!-- This table is used to display the Tax Configuration values-->
<!-- if EDIT_MODE is true then Textbox will be displayed else the value will be displayed-->
<form name="{$formname}" method="POST" action="index.php">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="">
<input type="hidden" name="save_tax" value="">
<input type="hidden" name="edit_tax" value="">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="95%">
<tbody>
   <tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="7"><img src="{$IMAGE_PATH}top_left.jpg" align="top"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif; height: 6px;" bgcolor="#ebebeb"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="6" width="8"><img src="{$IMAGE_PATH}top_right.jpg" align="top"></td>
   </tr>
   <tr>
	<td bgcolor="#ebebeb" width="7"></td>
	<td style="padding-left: 10px; padding-top: 10px; vertical-align: top;" bgcolor="#ececec">
		<table width="100%" border="0" cellpadding="0" cellspacing="0">
		   <tr>
			<td height="250" width="30%" bgcolor="#FFFFFF"  valign="bottom" background="{$IMAGE_PATH}taxConfig_top.gif" style="background-position:top right;background-repeat:no-repeat;padding:5px;">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td background="{$IMAGE_PATH}taxConfig_btm.gif" style="background-position:bottom right;background-repeat:no-repeat; " height="150">&nbsp;</td>
              </tr>
            </table></td>
			<td>
				<table width="100%" border="0" cellpadding="5" cellspacing="0" height="100%">
				   <tr>
					<td colspan="2" align="left" class="genHeaderBig">{$APP.LBL_TAX} {$MOD.LBL_CONFIGURATION}<br><hr></td>
				   </tr>
				   <tr><td colspan="2" >&nbsp;</td></tr>
				   <tr>
                                	<td align="right" width="50%"><b>{$APP.LBL_VAT} : </b></td>
                                	<td align="left">
						{if $EDIT_MODE eq 'true'}
							<input name="VAT" id="VAT" type="text" value="{$TAX_VALUES.VAT}" class="txtBox">
						{else}
							{$TAX_VALUES.VAT}
						{/if}
					</td>
				   </tr>
				   <tr>
					<td align="right"><b> {$APP.LBL_SALES} {$APP.LBL_TAX} : </b></td>
					<td align="left">
						{if $EDIT_MODE eq 'true'}
							<input name="Sales" id="Sales" type="text" value="{$TAX_VALUES.Sales}" class="txtBox">
						{else}
							{$TAX_VALUES.Sales}
						{/if}
					</td>
				   </tr>
				   <tr>
					<td align="right"> <b>{$APP.LBL_SERVICE} {$APP.LBL_TAX} : </b></td>
					<td align="left">
						{if $EDIT_MODE eq 'true'}
							<input name="Service" id="Service" type="text" value="{$TAX_VALUES.Service}" class="txtBox">
						{else}
							{$TAX_VALUES.Service}
						{/if}
					</td>
				   </tr>
				   <tr><td colspan="2">&nbsp;</td></tr>
				   <tr>
                                   	<td align="center" colspan="2">
						{if $EDIT_MODE eq 'true'}
							<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}"  onclick="this.form.action.value='TaxConfig'; this.form.save_tax.value='true'; return formValidate()" type="submit" name="button2" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " class="classBtn" >
							<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="this.form.module.value='Settings'; this.form.action.value='TaxConfig';" type="submit" name="button2" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " >
						{else}
							<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" class="classBtn" onclick="this.form.action.value='TaxConfig'; this.form.edit_tax.value='true';" type="submit" name="button" value="  {$APP.LBL_EDIT_BUTTON_LABEL}  ">
							<input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="classBtn" onclick="this.form.module.value='Settings'; this.form.action.value='index';" type="submit" name="button22" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
						{/if}
					</td>
				   </tr>
				   <tr><td colspan="2">&nbsp;</td></tr>
				</table>
			</td>
		   </tr>
		</table> 	
	</td>
	<td bgcolor="#ebebeb" width="8"></td>
   </tr>

   <tr>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="7"><img src="{$IMAGE_PATH}bottom_left.jpg" align="bottom"></td>
	<td style="font-size: 1px;" bgcolor="#ececec" height="8"></td>
	<td style="font-size: 1px; font-family: Arial,Helvetica,sans-serif;" height="8" width="8"><img src="{$IMAGE_PATH}bottom_right.jpg" align="bottom"></td>
   </tr>
</tbody>
</table>
</form>
<!-- Upto this added to display the Tax configuration -->



	</td>
   </tr>
</table>

