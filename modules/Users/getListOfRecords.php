<?php
/*********************************************************************************
** File content added/modified by SAKTI on 4th Feb, 2008 
 * This file is responsible for updating response for contact/leads/accounts.
 * This file is used as AJAX backend file for campaigns module
*
 ********************************************************************************/
session_start();
require_once('include/CustomFieldUtil.php');
require_once('modules/Campaigns/Campaigns.php');
require_once('Smarty_setup.php');
require_once('include/database/PearDatabase.php');


global $mod_strings,$app_strings,$app_list_strings,$theme,$adb,$current_user;
global $list_max_entries_per_page;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";

require_once($theme_path.'layout_utils.php');

$iCurRecord = $_REQUEST['CurRecordId'];
$sModule = $_REQUEST['CurModule'];
$fields_array = array('Campaigns'=>'campaignname','Calendar'=>'subject','Potentials'=>'potentialname','Faq'=>'question','HelpDesk'=>'title','Leads'=>'lastname','Contacts'=>'lastname','Products'=>'productname','PriceBooks'=>'bookname','Vendors'=>'vendorname','Accounts'=>'accountname','PurchaseOrder'=>'subject','SalesOrder'=>'subject','Quotes'=>'subject','Invoice'=>'subject','Documents'=>'title');
$id_array = array('Campaigns'=>'campaignid','Calendar'=>'activityid','Potentials'=>'potentialid','Faq'=>'id','HelpDesk'=>'ticketid','Leads'=>'leadid','Contacts'=>'contactid','Products'=>'productid','PriceBooks'=>'pricebookid','Vendors'=>'vendorid','Accounts'=>'accountid','PurchaseOrder'=>'purchaseorderid','SalesOrder'=>'salesorderid','Quotes'=>'quoteid','Invoice'=>'invoiceid','Documents'=>'notesid');
$tables_array = array('Campaigns'=>'vtiger_campaign','Calendar'=>'vtiger_activity','Potentials'=>'vtiger_potential','Faq'=>'vtiger_faq','HelpDesk'=>'vtiger_troubletickets','Leads'=>'vtiger_leaddetails','Contacts'=>'vtiger_contactdetails','Products'=>'vtiger_products','PriceBooks'=>'vtiger_pricebook','Vendors'=>'vtiger_vendor','Accounts'=>'vtiger_account','PurchaseOrder'=>'vtiger_purchaseorder','SalesOrder'=>'vtiger_salesorder','Quotes'=>'vtiger_quotes','Invoice'=>'vtiger_invoice','Documents'=>'vtiger_notes');
if(isset($_SESSION['listEntyKeymod']))
{
	$split_temp=explode(":",$_SESSION['listEntyKeymod']);
	if($split_temp[0] == $sModule)
	{	
		$ar_allist=explode(",",$split_temp[1]);
		$iMax = count($ar_allist);
	}
}
else
	$iMax = 0;

$output = '<table width="100%" border="0" cellpadding="5" cellspacing="0" class="layerHeadingULine">
			<tr><td width="60%" align="left" style="font-size:12px;font-weight:bold;">Jump to '.$app_strings[$sModule].':</td>
			<td width="5%" align="right"><a href="javascript:fninvsh(\'lstRecordLayout\');"><img src="'.$image_path.'close.gif" border="0"  align="absmiddle" /></a></td>
			</tr>
			</table><table border=0 cellspacing=0 cellpadding=0 width=100% align=center> 
							<tr>
								<td class=small >
									<table border=0 celspacing=0 cellpadding=0 width=100% align=center >
										<tr><td>';
										
if($iMax > 13)
	$output .= '<div style="height:270px;overflow-y:scroll;">';
else
	$output .= '<div style="height:250px;">';
	
$output .= '<table cellpadding="2">';				
	
if(isset($_SESSION['listEntyKeymod']))
{
	$split_temp=explode(":",$_SESSION['listEntyKeymod']);
	
	if($split_temp[0] == $sModule)
	{	
		$ar_allist=explode(",",$split_temp[1]);

		if(count($ar_allist) <= $list_max_entries_per_page){
			$start = 0;
			$end = count($ar_allist);
		}
		else{
			for($i=0;$i<count($ar_allist);$i++){
				if($ar_allist[$i]==$iCurRecord){
					$mid = $list_max_entries_per_page/2; 
					if($i > $mid){
						$start = $i-$mid;
						if(($i+$mid) <= count($ar_allist)){
							$end = $i+$mid;
							break;
						}else{
							$end = count($ar_allist);
							break;
						}
					}
					else
					{
						$start = 0;
						$end = $i+$mid;
					}
				}
			}
		}
		for($listi=$start;$listi<$end;$listi++)
		{
			$field_query = $adb->pquery("SELECT * from ".$tables_array[$sModule]." WHERE ".$id_array[$sModule]." = ".$ar_allist[$listi],array());
			$field_value = $adb->query_result($field_query,0,$fields_array[$sModule]);
			if($sModule == 'Contacts' || $sModule == 'Leads')
			{
				if($is_admin == false){
					$fld_permission = getFieldVisibilityPermission($sModule,$current_user->id,'firstname');
				}
				if($fld_permission == 0){
					$field_value .= " ".$adb->query_result($field_query,0,'firstname');
				}
			}
			if(strlen($field_value)>50)
				$field_value=substr($field_value,0,50)."...";

			if($ar_allist[$listi]==$iCurRecord)
				$output .= '<tr><td style="text-align:left;font-weight:bold;">'.$field_value.'</td></tr>';
			else
				$output .= '<tr><td style="text-align:left;"><a href="index.php?module='.$sModule.'&action=DetailView&parenttab='.$_REQUEST['CurParentTab'].'&record='.$ar_allist[$listi].'">'.$field_value.'</a></td></tr>';
		}
		$output .= '</table>';
	}
}

$output .= '</div></td></tr></table></td></tr></table>';
	
echo $output;
?>
