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

<br>
<table align="center" border="0" cellpadding="0" cellspacing="0" width="98%">
<tbody><tr>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopLeft.gif"></td>
        <td class="showPanelBg" style="padding: 10px;" valign="top" width="100%">
<form action="index.php" method="post" id="form">
<input type='hidden' name='module' value='Users'>
<input type='hidden' name='action' value='DefModuleView'>
<input type='hidden' name='return_action' value='ListView'>
<input type='hidden' name='return_module' value='Users'>
<input type='hidden' name='parenttab' value='Settings'>

        <br>

	<div align=center>
			{include file='SetMenu.tpl'}
				<!-- DISPLAY -->
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="settingsSelUITopLine">
				<tr>
					<td width=50 rowspan=2 valign=top><img src="{$IMAGE_PATH}settingsInvNumber.gif" alt="{$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER}" width="48" height="48" border=0 title="{$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER}"></td>
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_CUSTOMIZE_INVENTORY_NUMBER}</b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_CUSTOMIZE_INVENTORY_NUMBER_DESCRIPTION}</td>
				</tr>
				</table>

				<br>

				<table border=0 cellspacing=0 cellpadding=10 width=100% >
					<tr>
						<td>
							<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
								<tr>
									<td width='70%'>
									<table border=0 cellspacing=0 cellpadding=5 width=100%>
									<tr>
									<td class="big" height="20px;" width="75%"><strong><span id='module_info'>{$MOD.Invoice}</span> Number Customization</strong></td>
									<td  align="right" valign='top' width='25%'>
										<span id="view_info" class="crmButton small cancel" style="display:none;">Successfully Updated.</span>
									</td>									</tr>
									</table>
									</td>
									<td>
									<td align='right'><b>{$MOD.LBL_SELECT_MODULE}</b>:&nbsp;
										<SELECT name='selected_module' class='small'onChange='change_module(this.value)'>
											<OPTION value='Invoice' selected>Invoice</OPTION>
											<OPTION value='PurchaseOrder' >PurchaseOrder</OPTION>
											<OPTION value='SalesOrder' >SalesOrder</OPTION>
											<OPTION value='Quote'>Quote</OPTION>
										</SELECT>
									</td>

								</tr>
							</table>

						<span id='InvoiceCustomization' style='display:block'>
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
								<tr>
	         	    				<td class="small" valign=top ><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        			<tr>
                            			<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMINVOICE_STRING}</strong></td>
                            			<td width="80%" class="small cellText">
											<input type="text" id="invoicestring" name="invoicestring" class="small" style="width:30%" value="{$inv_str}" onkeyup="preview('invoice');" onchange="preview('invoice');" />
										</td>
                        			</tr>
			                        <tr>
										<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMINVOICE_NUMBER}</strong></td>
                						<td width="80%" class="small cellText">
											<input type="text" id="invoicenumber" name="invoicenumber" class="small" style="width:30%" value="{$inv_no}"  onkeyup="preview('invoice');" onchange="preview('invoice');" />
										</td>
									</tr>
									<tr>
										<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_INVOICE_NUMBER_PREVIEW}</strong></td>
                						<td width="80%" class="small cellText" id="invoicepreview" style="font-weight:bold">{$inv_str}{$inv_no}</td>
                        			</tr>
									<tr>
										<td width="20%" nowrap colspan="2" align ="center">
											<input type="button" name="update" class="crmbutton small create" value="{$MOD.LBL_UPDATE_BUTTON}" onclick="validatefn1('invoice');" />
											<input type="button" name="cancel" class="crmbutton small cancel" value="{$MOD.LBL_CANCEL_BUTTON}"  onClick="window.history.back();"/>
									    </td>
                        			</tr></table>
								</td>
							</tr>                       
                       </table>
      	        </span>
                <!--INVOICE ENDS -->
                <!-- QUOTE Start-->
				<span id='QuoteCustomization' style='display:none'>
		
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
								<tr>
	         	    				<td class="small" valign=top ><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        			<tr>
                            			<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMQUOTE_STRING}</strong></td>
                            			<td width="80%" class="small cellText">
											<input type="text" id="quotestring" name="quotestring" class="small" style="width:30%" value="{$quo_str}" onkeyup="preview('quote');" onchange="preview('quote');" />
										</td>
                        			</tr>
			                        <tr>
										<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMQUOTE_NUMBER}</strong></td>
                						<td width="80%" class="small cellText">
											<input type="text" id="quotenumber" name="quotenumber" class="small" style="width:30%" value="{$quo_no}"  onkeyup="preview('quote');" onchange="preview('quote');" />
										</td>
									</tr>
									<tr>
										<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_QUOTE_NUMBER_PREVIEW}</strong></td>
                						<td width="80%" class="small cellText" id="quotepreview" style="font-weight:bold">{$quo_str}{$quo_no}</td>
                        			</tr>
									<tr>
										<td width="20%" nowrap colspan="2" align ="center">
											<input type="button" name="update" class="crmbutton small create" value="{$MOD.LBL_UPDATE_BUTTON}" onclick="validatefn1('quote');" />
											<input type="button" name="cancel" class="crmbutton small cancel" value="{$MOD.LBL_CANCEL_BUTTON}"  onClick="window.history.back();"/>
									    </td>
                        			</tr></table>
								</td>
							</tr>                       
                       </table>
                </span>
                <!--QUOTE ENDS-->
                
                <!-- Sales Order Start-->
				<span id='SOCustomization' style='display:none'>
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
								<tr>
	         	    				<td class="small" valign=top >
	         	    				<table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        				<tr>
                            				<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMSO_STRING}</strong></td>
                            				<td width="80%" class="small cellText">
												<input type="text" id="sostring" name="sostring" class="small" style="width:30%" value="{$so_str}" onkeyup="preview('so');" onchange="preview('so');" />
											</td>
                        				</tr>
			                        	<tr>
											<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMSO_NUMBER}</strong></td>
                							<td width="80%" class="small cellText">
												<input type="text" id="sonumber" name="sonumber" class="small" style="width:30%" value="{$so_no}"  onkeyup="preview('so');" onchange="preview('so');" />
											</td>
										</tr>
										<tr>
											<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_SO_NUMBER_PREVIEW}</strong></td>
                							<td width="80%" class="small cellText" id="sopreview" style="font-weight:bold">{$so_str}{$so_no}</td>
                        				</tr>
										<tr>
											<td width="20%" nowrap colspan="2" align ="center">
												<input type="button" name="update" class="crmbutton small create" value="{$MOD.LBL_UPDATE_BUTTON}" onclick="validatefn1('so');" />
												<input type="button" name="cancel" class="crmbutton small cancel" value="{$MOD.LBL_CANCEL_BUTTON}"  onClick="window.history.back();"/>
									    	</td>
                        				</tr>
                        			</table>
									</td>
								</tr>                       
							</table>
               </span>        
                <!-- Sales Order ENDS-->
				
				<!--PO starts-->
				<span id='POCustomization' style='display:none'>
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
								<tr>
	         	    				<td class="small" valign=top >
	         	    				<table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        			<tr>
                            			<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMPO_STRING}</strong></td>
                            			<td width="80%" class="small cellText">
											<input type="text" id="postring" name="postring" class="small" style="width:30%" value="{$po_str}" onkeyup="preview('po');" onchange="preview('po');" />
										</td>
                        			</tr>
			                        <tr>
										<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMPO_NUMBER}</strong></td>
                						<td width="80%" class="small cellText">
											<input type="text" id="ponumber" name="ponumber" class="small" style="width:30%" value="{$po_no}"  onkeyup="preview('po');" onchange="preview('po');" />
										</td>
									</tr>
									<tr>
										<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_PO_NUMBER_PREVIEW}</strong></td>
                						<td width="80%" class="small cellText" id="popreview" style="font-weight:bold">{$po_str}{$po_no}</td>
                        			</tr>
									<tr>
										<td width="20%" nowrap colspan="2" align ="center">
											<input type="button" name="update" class="crmbutton small create" value="{$MOD.LBL_UPDATE_BUTTON}" onclick="validatefn1('po');" />
											<input type="button" name="cancel" class="crmbutton small cancel" value="{$MOD.LBL_CANCEL_BUTTON}"  onClick="window.history.back();"/>
									    </td>
                        			</tr>
                        			</table>
									</td>
								</tr>                       
                       		</table>
                </span>  
                        <!--PO Ends -->
				</td>
				</tr>
				</table>
	</div>

</td>
        <td valign="top"><img src="{$IMAGE_PATH}showPanelTopRight.gif"></td>
   </tr>
</tbody>
</form>
</table>

{literal}
<script>

function setinvid(module)
{
var inv_no=document.getElementById(module+"number").value;
var inv_str=document.getElementById(module+"string").value;

         $("status").style.display="block";
	     new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=UpdateCustomInventorySeq&ajax=true&no='+inv_no+'&str='+inv_str+'&mode=configure_invno&semodule='+module+'&status='+status,
                        onComplete: function(response) {
			if((response.responseText != ''))
				alert(response.responseText)
			else
				$('view_info').style.display = 'block';		
                                $("status").style.display="none";
                        }
                }
        );

	setTimeout("hide('view_info')",3000);
}

function preview(module)
{
document.getElementById(module+"preview").innerHTML=(document.getElementById(module+"string").value + document.getElementById(module+"number").value);
}

function validatefn1(module)
{
preview(module);

var invnumber=document.getElementById(module+"number").value;
var invstring=document.getElementById(module+"string").value;

var iChars = "~`-/!@#$%^&*()+=[]\\\';,.{}|\":<>?";

          for (var i = 0; i < invstring.length; i++)
            {
               if (iChars.indexOf(invstring.charAt(i)) != -1)
                {
               alert (alert_arr.NO_SPECIAL_CHARS);
               return false;
                }

            }

if (!emptyCheck(module+"number","Invoice Number","any")) return false
if (!emptyCheck(module+"string","Invoice String","text")) return false
if (!numValidate(module+"number","Invoice Number","any")) return false 

setinvid(module);
}

function change_module(module)
{

	$('module_info').innerHTML = ''+module+'';

        if (module=="Invoice")
        {
                getObj("InvoiceCustomization").style.display="block"
                getObj("QuoteCustomization").style.display="none"
                getObj("SOCustomization").style.display="none"
                getObj("POCustomization").style.display="none"
        }
        if (module=="Quote")
        {
                getObj("InvoiceCustomization").style.display="none"
                getObj("QuoteCustomization").style.display="block"
                getObj("SOCustomization").style.display="none"
                getObj("POCustomization").style.display="none"
        }
        if (module=="PurchaseOrder")
        {
                getObj("InvoiceCustomization").style.display="none"
                getObj("QuoteCustomization").style.display="none"
                getObj("SOCustomization").style.display="none"
                getObj("POCustomization").style.display="block"
        }
        if (module=="SalesOrder")
        {
                getObj("InvoiceCustomization").style.display="none"
                getObj("QuoteCustomization").style.display="none"
                getObj("SOCustomization").style.display="block"
                getObj("POCustomization").style.display="none"
        }
}

</script>
{/literal}

