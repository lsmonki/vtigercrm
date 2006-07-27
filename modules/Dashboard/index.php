<?php
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version 1.1.2
 * ("License"); You may not use this file except in compliance with the 
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an  "AS IS"  basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied. See the License for
 * the specific language governing rights and limitations under the License.
 * The Original Code is:  SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Header: /advent/projects/wesat/vtiger_crm/sugarcrm/modules/Dashboard/index.php,v 1.2 2004/10/06 09:02:05 jack Exp $
 * Description:  Main file for the Home module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $app_strings;
global $app_list_strings;
global $mod_strings;

global $theme;
global $currentModule;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
require_once('include/logging.php');

$graph_array = Array(
          "leadsource" => $mod_strings['leadsource'],
          "leadstatus" => $mod_strings['leadstatus'],
          "leadindustry" => $mod_strings['leadindustry'],
          "salesbyleadsource" => $mod_strings['salesbyleadsource'],
          "salesbyaccount" => $mod_strings['salesbyaccount'],
	  "salesbyuser" => $mod_strings['salesbyuser'],
	  "salesbyteam" => $mod_strings['salesbyteam'],
          "accountindustry" => $mod_strings['accountindustry'],
          "productcategory" => $mod_strings['productcategory'],
	  "productbyqtyinstock" => $mod_strings['productbyqtyinstock'],
	  "productbypo" => $mod_strings['productbypo'],
	  "productbyquotes" => $mod_strings['productbyquotes'],
	  "productbyinvoice" => $mod_strings['productbyinvoice'],
          "sobyaccounts" => $mod_strings['sobyaccounts'],
          "sobystatus" => $mod_strings['sobystatus'],
          "pobystatus" => $mod_strings['pobystatus'],
          "quotesbyaccounts" => $mod_strings['quotesbyaccounts'],
          "quotesbystage" => $mod_strings['quotesbystage'],
          "invoicebyacnts" => $mod_strings['invoicebyacnts'],
          "invoicebystatus" => $mod_strings['invoicebystatus'],
          "ticketsbystatus" => $mod_strings['ticketsbystatus'],
          "ticketsbypriority" => $mod_strings['ticketsbypriority'],
	  "ticketsbycategory" => $mod_strings['ticketsbycategory'], 
	  "ticketsbyuser" => $mod_strings['ticketsbyuser'],
	  "ticketsbyteam" => $mod_strings['ticketsbyteam'],
	  "ticketsbyproduct"=> $mod_strings['ticketsbyproduct'],
	  "contactbycampaign"=> $mod_strings['contactbycampaign'],
	  "ticketsbyaccount"=> $mod_strings['ticketsbyaccount'],
	  "ticketsbycontact"=> $mod_strings['ticketsbycontact'],
          );
          
$log = LoggerManager::getLogger('dashboard');
?>

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:30px" class="moduleName" width="20%" nowrap><?php echo $app_strings['Analytics'];?> &gt; <a class="hdrLink" href="index.php?action=index&parenttab=Analytics&module=Dashboard"><?php echo $app_strings['Dashboard'] ?></a></td>

	<td  nowrap width="8%">
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td class="sep1" style="width:1px;"></td>
			<td class=small>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
						<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo $image_path;?>btnL3Add-Faded.gif" border=0></td>	
					 <td style="padding-right:10px"><img src="<?php echo $image_path;?>btnL3Search-Faded.gif" border=0></td>
				</tr>
				</table>
	</td>
			</tr>
			</table>
	</td>
	<td width="20">&nbsp;</td>
                <td class="small" width="10%" align="left">
				<table border=0 cellspacing=0 cellpadding=5>

				<tr>
					<td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal("parenttab=My Home Page");'><img src="<?php echo $image_path;?>btnL3Calendar.gif" alt="<?php echo $app_strings['LBL_CALENDAR_ALT']; ?>" title="<?php echo $app_strings['LBL_CALENDAR_TITLE']; ?>" border=0></a></a></td>
					<td style="padding-right:0px"><a href="javascript:;"><img src="<?php echo $image_path;?>btnL3Clock.gif" alt="<?php echo $app_strings['LBL_CLOCK_ALT']; ?>" title="<?php echo $app_strings['LBL_CLOCK_TITLE']; ?>" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="<?php echo $image_path;?>btnL3Calc.gif" alt="<?php echo $app_strings['LBL_CALCULATOR_ALT']; ?>" title="<?php echo $app_strings['LBL_CALCULATOR_TITLE']; ?>" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td>
					<td style="padding-right:10px"><a href="javascript:;" onClick='return window.open("index.php?module=Contacts&action=vtchat","Chat","width=450,height=400,resizable=1,scrollbars=1");'><img src="<?php echo $image_path;?>tbarChat.gif" alt="<?php echo $app_strings['LBL_CHAT_ALT']; ?>" title="<?php echo $app_strings['LBL_CHAT_TITLE']; ?>" border=0></a>
                    </td>	
				</tr>
				</table>
	</td>
	<td width="20">&nbsp;</td>
               <td class="small" align="left" width="5%">
		<table border=0 cellspacing=0 cellpadding=5>
			<tr>
				<td style="padding-right:0px;padding-left:10px;"><img src="<?php echo $image_path;?>tbarImport-Faded.gif" border="0"></td>
                <td style="padding-right:10px"><img src="<?php echo $image_path;?>tbarExport-Faded.gif" border="0"></td>
			</tr>
		</table>	
	</td>
	<td width="20">&nbsp;</td>
                <td class="small" align="left">	
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
				<td style="padding-left:10px;"><a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')"><img src="<?php echo $image_path;?>btnL3AllMenu.gif" alt="<?php echo $app_strings['LBL_ALL_MENU_ALT']; ?>" title="<?php echo $app_strings['LBL_ALL_MENU_TITLE']; ?>" border="0"></a></td>
				</tr>
				</table>
	</td>			
	</tr>
	</table>
	</td>
	
</tr>
<tr><td style="height:2px"></td></tr>
</TABLE>

<table class="dashMain" cellspacing="0" cellpadding="0" align="center">
   <tr>
    <th colspan="3"><img src="themes/blue/images/topBnr.gif" width="840" height="67" /></th>
  </tr>
  <tr><td colspan="3">&nbsp;</td></tr>

  <tr>
    <td width="140" nowrap valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0" bgcolor="#DFDFDF" style="cursor:pointer;">
			<tr><td class="dashMnuSel" id="DashboardHome_mnu" onClick="loadDashBoard('DashboardHome');"><?php echo $mod_strings['LBL_DASHBRD_HOME'];?></a>
               </td></tr>
               <?php 
                 $mnuHTML = "";
                 foreach($graph_array as $key=>$value)   
                 {
                    if($type == $key)
                    {
                         $mnuHTML .= '<tr><td id="'.$key.'_mnu" class="dashMnuSel" onClick="loadDashBoard(\''.$key.'\');">'.$value.'</a>
                                      </td></tr>';
                    }else
                    {
                         $mnuHTML .= '<tr><td id="'.$key.'_mnu" class="dashMnuUnSel" onClick="loadDashBoard(\''.$key.'\');">'.$value.'</a>
                                      </td></tr>';
                    }
                 }
                 echo $mnuHTML;
               ?>
		</table>
	</td>

    <td width="695" bgcolor="#CBCBCB" valign="top" style="padding-right:10px;" align="left">
		<table class="dashInner"  cellpadding="0" cellspacing="0">
		<tr><td class="lvtHeaderText" align="left" height="10"></td></tr>
		<tr><td><div id="dashChart">
			<? require_once('modules/Dashboard/DashboardHome.php'); ?>	
		</div></td></tr>
		</table>
	  <br />
</td>

 <td width="5"></td>
  </tr>
  <tr><td colspan="3" height="30">&nbsp;</td></tr>
</table>
<script language="javascript" type="text/javascript" src="include/scriptaculous/prototype.js"></script>
<script language="javascript" type="text/javascript" src="include/scriptaculous/scriptaculous.js"></script>
<script>
var gMnuSel = 'DashboardHome_mnu';
function loadDashBoard(type)
{
	show('status');
	Effect.Fade("dashChart");
	if(type != 'DashboardHome')
		url = 'module=Dashboard&action=DashboardAjax&file=display_charts&type='+type;
	else	
		url = 'module=Dashboard&action=DashboardAjax&file=DashboardHome';
	new Ajax.Request(
		'index.php',
		{queue: {position: 'end', scope: 'command'},
			method: 'post',
			postBody: url,
			onComplete: function(response)
			{
				
				$("dashChart").innerHTML=response.responseText;
				hide('status');
				$(gMnuSel).className = 'dashMnuUnSel';
				gMnuSel = type+'_mnu';
				$(gMnuSel).className = 'dashMnuSel';	
				Effect.Appear("dashChart");
			}
		}
	);	
}
</script>
