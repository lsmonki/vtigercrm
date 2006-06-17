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
<script language="JAVASCRIPT" type="text/javascript" src="include/js/smoothscroll.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/menu.js"></script>
<script language="JavaScript" type="text/javascript" src="include/js/general.js"></script>
<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
<br>
	<div align=center>

			{include file='SetMenu.tpl'}
				<!-- DISPLAY -->
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

				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{$IMAGE_PATH}taxConfiguration.gif" alt="Users" width="48" height="48" border=0 title="Users"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > 
							{if $EDIT_MODE eq 'true'}
								<strong>{$MOD.LBL_EDIT} {$MOD.LBL_TAX_SETTINGS} </strong></td>
							{else}
								<strong>{$MOD.LBL_TAX_SETTINGS} </strong></td>
							{/if}
				</b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_TAX_DESC}</td>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td>
				
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
					<tr>
						<td class="big"><strong>{$MOD.LBL_TAX_SETTINGS} </strong></td>
						<td class="small" align=right>
						{if $EDIT_MODE eq 'true'}	

							<input class="crmButton small save" title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}"  onclick="this.form.action.value='TaxConfig'; this.form.save_tax.value='true'; return formValidate()" type="submit" name="button2" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  ">&nbsp;&nbsp;
							<input class="crmButton small cancel" title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" onclick="window.history.back();" type="button" name="button22" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  ">
						{else}	
							<input title="{$APP.LBL_EDIT_BUTTON_TITLE}" accessKey="{$APP.LBL_EDIT_BUTTON_KEY}" onclick="this.form.action.value='TaxConfig'; this.form.edit_tax.value='true';" type="submit" name="button" value="  {$APP.LBL_EDIT_BUTTON_LABEL}  " class="crmButton small edit">
						{/if}
						</td>
					</tr>
					</table>
					
					<table border=0 cellspacing=0 cellpadding=5 width=100% class="listRow">
					<tr>
						<td width=20% class="cellLabel small">{$APP.LBL_VAT} </td>
						<td width=80% class="cellText small">
							{if $EDIT_MODE eq 'true'}
								<input name="VAT" id="VAT" type="text" value="{$TAX_VALUES.VAT}" class="detailedViewTextBox small">&nbsp;%
						{else}
								{$TAX_VALUES.VAT}&nbsp;%
						{/if}
						</td>
					  </tr>
					<tr>
						<td width=20% class="cellLabel small"> {$APP.LBL_SALES} {$APP.LBL_TAX} </td>
						<td width=80% class="cellText small">
							{if $EDIT_MODE eq 'true'}
							<input name="Sales" id="Sales" type="text" value="{$TAX_VALUES.Sales}" class="detailedViewTextBox small">&nbsp;%
						{else}
							{$TAX_VALUES.Sales}&nbsp;% 
						{/if}	
						</td>
					  </tr>
					<tr>
						<td width=20% class="cellLabel small">{$APP.LBL_SERVICE} {$APP.LBL_TAX} </td>
						<td width=80% class="cellText small">
							{if $EDIT_MODE eq 'true'}
								<input name="Service" id="Service" type="text" value="{$TAX_VALUES.Service}" class="detailedViewTextBox small">&nbsp;%
							{else}
								{$TAX_VALUES.Service}&nbsp;%
							{/if}
						</td>
					  </tr>
					</table>
					<table border=0 cellspacing=0 cellpadding=5 width=100% >
					<tr>
					  <td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td>
					</tr>
					</table>
				</td>
				</tr>
				</table>
			
			
			
			</td>
			</tr>
			</table>
		</td>
	</tr>
	</table>
	</form>		
	</div>

</td>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
   </tr>
</tbody>
</table>
