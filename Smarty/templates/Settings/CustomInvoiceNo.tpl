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
					<td class=heading2 valign=bottom><b><a href="index.php?module=Settings&action=index&parenttab=Settings">{$MOD.LBL_SETTINGS}</a> > {$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER}</b></td>
				</tr>
				<tr>
					<td valign=top class="small">{$MOD.LBL_CUSTOMIZE_INVOICE_NUMBER_DESCRIPTION}</td>
				</tr>
				</table>
				
				<br>
				<table border=0 cellspacing=0 cellpadding=10 width=100% >
				<tr>
				<td>
				
				<table border=0 cellspacing=0 cellpadding=5 width=100% class="tableHeading">
				<tr>
				<td class="big" height="40px;" width="70%"><strong>{$MOD.LBL_CUSTOM_INVOICE_NUMBER_VIEW}</strong></td>
				<td class="small" align="center" width="30%">&nbsp;
					<span id="view_info" class="crmButton small cancel" style="display:none;">Successfully Updated.</span>
				</td>
				</tr>
				</table>
			
							<table border=0 cellspacing=0 cellpadding=0 width=100% class="listRow">
			<tr>
	         	    <td class="small" valign=top ><table width="100%"  border="0" cellspacing="0" cellpadding="5">
                        <tr>
                            <td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMINVOICE_STRING}</strong></td>
                            <td width="80%" class="small cellText">


<input type="text" id="invoicestring" name="invoicestring" class="small" style="width:30%" value="{$inv_str}" onkeyup="preview();" onchange="preview();" />
			</td>
                        </tr>


                        <tr>

		<td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_CUSTOMINVOICE_NUMBER}</strong>
		</td>
                <td width="80%" class="small cellText">
<input type="text" id="invoicenumber" name="invoicenumber" class="small" style="width:30%" value="{$inv_no}"  onkeyup="preview();" onchange="preview();" />
</td>
			</tr>

<tr>

                <td width="20%" nowrap class="small cellLabel"><strong>{$MOD.LBL_INVOICE_NUMBER_PREVIEW}</strong>
                </td>
                <td width="80%" class="small cellText" id="invoicepreview" style="font-weight:bold">{$inv_str}{$inv_no}</td>
                        </tr>

<tr>

                <td width="20%" nowrap colspan="2" align ="center">


<input type="button" name="Button" class="crmbutton small create" value="{$MOD.LBL_INVOICE_NUMBER_BUTTON}" onclick="validatefn1();" />

        </td>
                        </tr>

                       
                        </table>
	    </td>
                        </tr>


                        </table>	
				<!--table border=0 cellspacing=0 cellpadding=5 width=100% >
				<tr>
					  <td class="small" nowrap align=right><a href="#top">{$MOD.LBL_SCROLL}</a></td>
				</tr>
				</table-->
	
				</td>
				</tr>
				</table>
			
			
			
			</td>
			</tr>
			</table>
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

function setinvoiceid()
{
var inv_no=document.getElementById("invoicenumber").value;
var inv_str=document.getElementById("invoicestring").value;

	   
             $("status").style.display="block";
	     new Ajax.Request(
                'index.php',
                {queue: {position: 'end', scope: 'command'},
                        method: 'post',
                        postBody: 'module=Users&action=UsersAjax&file=UpdateCustomInvoiceNo&ajax=true&no='+inv_no+'&str='+inv_str+'&mode=configure_invoiceno&status='+status,
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

function preview()
{
document.getElementById("invoicepreview").innerHTML=(document.getElementById("invoicestring").value + document.getElementById("invoicenumber").value);
}


function validatefn1()
{
preview();
var invnumber=document.getElementById("invoicenumber").value;
var invstring=document.getElementById("invoicestring").value;


var iChars = "!@#$%^&*()+=[]\\\';,.{}|\":<>?";

          for (var i = 0; i < invstring.length; i++)
            {
               if (iChars.indexOf(invstring.charAt(i)) != -1)
                {
               alert (alert_arr.NO_SPECIAL_CHARS);
               return false;
                }

            }


if (!emptyCheck("invoicenumber","Invoice Number","any")) return false
if (!emptyCheck("invoicestring","Invoice String","text")) return false
if (!numValidate("invoicenumber","Invoice Number","any")) return false 


setinvoiceid();

}


</script>
{/literal}

