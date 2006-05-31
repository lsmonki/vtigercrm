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
          "accountindustry" => $mod_strings['accountindustry'],
          "productcategory" => $mod_strings['productcategory'],
          "sobyaccounts" => $mod_strings['sobyaccounts'],
          "sobystatus" => $mod_strings['sobystatus'],
          "pobystatus" => $mod_strings['pobystatus'],
          "quotesbyaccounts" => $mod_strings['quotesbyaccounts'],
          "quotesbystage" => $mod_strings['quotesbystage'],
          "invoicebyacnts" => $mod_strings['invoicebyacnts'],
          "invoicebystatus" => $mod_strings['invoicebystatus'],
          "ticketsbystatus" => $mod_strings['ticketsbystatus'],
          "ticketsbypriority" => $mod_strings['ticketsbypriority'],
          );
          
$log = LoggerManager::getLogger('dashboard');
?>

<TABLE border=0 cellspacing=0 cellpadding=0 width=100% class=small>
<tr><td style="height:2px"></td></tr>
<tr>
	<td style="padding-left:10px;padding-right:30px" class="moduleName" width="20%" nowrap><? echo $app_strings['Analytics'];?> &gt; <a class="hdrLink" href="index.php?action=index&parenttab=Analytics&module=Dashboard"><? echo $app_strings['Dashboard'] ?></a></td>

	<td  nowrap width="8%">
		<table border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td class="sep1" style="width:1px;"></td>
			<td class=small>
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
						<td style="padding-right:0px;padding-left:10px;"><img src="<?echo $image_path;?>btnL3Add-Faded.gif" alt="<? echo $app_strings['LBL_CREATE_BUTTON_LABEL']; ?> <? echo $app_strings['Dashboard']; ?>..." title="<? echo $app_strings['LBL_CREATE_BUTTON_LABEL']; ?> <? echo $app_strings['Dashboard']; ?>..." border=0></td>	
					 <td style="padding-right:10px"><img src="<?echo $image_path;?>btnL3Search-Faded.gif" border=0></td>
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
					<td style="padding-right:0px;padding-left:10px;"><a href="javascript:;" onClick='fnvshobj(this,"miniCal");getMiniCal();'><img src="<?echo $image_path;?>btnL3Calendar.gif" alt="<? echo $app_strings['LBL_CALENDAR_ALT']; ?>" title="<? echo $app_strings['LBL_CALENDAR_TITLE']; ?>" border=0></a></a></td>
					<td style="padding-right:0px"><a href="javascript:;"><img src="<?echo $image_path;?>btnL3Clock.gif" alt="<? echo $app_strings['LBL_CLOCK_ALT']; ?>" title="<? echo $app_strings['LBL_CLOCK_TITLE']; ?>" border=0 onClick="fnvshobj(this,'wclock');"></a></a></td>
					<td style="padding-right:0px"><a href="#"><img src="<?echo $image_path;?>btnL3Calc.gif" alt="<? echo $app_strings['LBL_CALCULATOR_ALT']; ?>" title="<? echo $app_strings['LBL_CALCULATOR_TITLE']; ?>" border=0 onClick="fnvshobj(this,'calculator_cont');fetch_calc();"></a></td>
					<td style="padding-right:10px"><a href="javascript:;" onClick='return window.open("index.php?module=Contacts&action=vtchat","Chat","width=450,height=400,resizable=1,scrollbars=1");'><img src="<?echo $image_path;?>tbarChat.gif" alt="<? echo $app_strings['LBL_CHAT_ALT']; ?>" title="<? echo $app_strings['LBL_CHAT_TITLE']; ?>" border=0></a>
                    </td>	
				</tr>
				</table>
	</td>
	<td width="20">&nbsp;</td>
               <td class="small" align="left" width="5%">
		<table border=0 cellspacing=0 cellpadding=5>
			<tr>
				<td style="padding-right:0px;padding-left:10px;"><img src="<?echo $image_path;?>tbarImport-Faded.gif" alt="<? echo $app_strings['LBL_IMPORT']; ?> <? echo $app_strings['Dashboard']; ?>" title="<? echo $app_strings['LBL_IMPORT']; ?> <? echo $app_strings['Dashboard']; ?>" border="0"></td>
                <td style="padding-right:10px"><img src="<?echo $image_path;?>tbarExport-Faded.gif" alt="<? echo $app_strings['LBL_EXPORT']; ?> <? echo $app_strings['Dashboard']; ?>" title="<? echo $app_strings['LBL_EXPORT']; ?> <? echo $app_strings['Dashboard']; ?>" border="0"></td>
			</tr>
		</table>	
	</td>
	<td width="20">&nbsp;</td>
                <td class="small" align="left">	
				<table border=0 cellspacing=0 cellpadding=5>
				<tr>
				<td style="padding-left:10px;"><a href="javascript:;" onmouseout="fninvsh('allMenu');" onClick="fnvshobj(this,'allMenu')"><img src="<?echo $image_path;?>btnL3AllMenu.gif" alt="<? echo $app_strings['LBL_ALL_MENU_ALT']; ?>" title="<? echo $app_strings['LBL_ALL_MENU_TITLE']; ?>" border="0"></a></td>
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
    <td width="20%" nowrap valign="top">
		<table width="100%"  border="0" cellspacing="0" cellpadding="0" bgcolor="#DFDFDF">
			<tr><td class="dashMnuSel">
                    <a href="index.php?module=Dashboard&action=index&type=dashboardhome"><? echo $mod_strings['LBL_DASHBRD_HOME'];?></a>
               </td></tr>
               <?php 
                 $mnuHTML = "";
                 foreach($graph_array as $key=>$value)   
                 {
                    if($type == $key)
                    {
                         $mnuHTML .= '<tr><td class="dashMnuSel">
                                        <a href="index.php?module=Dashboard&action=display_charts&type='.$key.'">'.$value.'</a>
                                      </td></tr>';
                    }else
                    {
                         $mnuHTML .= '<tr><td class="dashMnuUnSel">
                                        <a href="index.php?module=Dashboard&action=display_charts&type='.$key.'">'.$value.'</a>
                                      </td></tr>';
                    }
                 }
                 echo $mnuHTML;
               ?>
		</table>
	</td>

    <td width="79%" bgcolor="#CBCBCB" valign="top" style="padding-right:10px;" align="left">
		<table class="dashInner"  cellpadding="0" cellspacing="0">
		<tr><td class="lvtHeaderText" align="left" height="10"></td></tr>
		<tr><td><div id="dashChart">
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>

				 		   <td rowspan="2" valign="top"><span class="dashSerial">1</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?echo $mod_strings['LBL_SALES_STAGE_FORM_TITLE']; ?></span></td>
				 		 </tr>
					     <tr>
					       <td><span class="big"><? echo $mod_strings['LBL_HORZ_BAR_CHART'];?></span> </td>
					     </tr>
					</table>

				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_pipeline_by_sales_stage.php");?></td>
			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>

			  <!-- SCEOND CHART  -->
			  
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>
				 		   <td rowspan="2" valign="top"><span class="dashSerial">2</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?php echo $mod_strings['LBL_MONTH_BY_OUTCOME'];?></span></td>
				 		 </tr>
					     <tr>

					       <td><span class="big"><? echo $mod_strings['LBL_VERT_BAR_CHART'];?></span> </td>
					     </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_outcome_by_month.php"); ?></td>

			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>
			  
			  <!-- THIRD CHART  -->
			  
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>
				 		   <td rowspan="2" valign="top"><span class="dashSerial">3</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?php echo $mod_strings['LBL_LEAD_SOURCE_BY_OUTCOME'];?></span></td>
				 		 </tr>
					     <tr>

					       <td><span class="big"><? echo $mod_strings['LBL_HORZ_BAR_CHART'];?></span> </td>
					     </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_lead_source_by_outcome.php");?></td>

			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>
			  
			  <!-- FOURTH CHART  -->
			  
			  <tr>
				<td><table width="20%"  border="0" cellspacing="0" cellpadding="0" align="le">
				 		 <tr>
				 		   <td rowspan="2" valign="top"><span class="dashSerial">4</span></td>
				 		   <td nowrap><span class="genHeaderSmall"><?php echo $mod_strings['LBL_LEAD_SOURCE_FORM_TITLE'];?></span></td>
				 		 </tr>
					     <tr>

					       <td><span class="big"><? echo $mod_strings['LBL_PIE_CHART'];?></span> </td>
					     </tr>
					</table>
				</td>
			  </tr>
			  <tr>
				<td height="200"><?php include ("modules/Dashboard/Chart_pipeline_by_lead_source.php") ?></td>

			  </tr>
			  <tr>
				<td><hr noshade="noshade" size="1" /></td>
			  </tr>
			</table>
	</div></td></tr>
		</table>
	  <br />
</td>

 <td width="1%"></td>
  </tr>
  <tr><td colspan="3" height="30">&nbsp;</td></tr>
</table>

