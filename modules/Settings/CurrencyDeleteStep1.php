<?php

/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
*
 ********************************************************************************/


require_once('include/database/PearDatabase.php');
require_once('include/utils/utils.php');

$delete_currency_id = $_REQUEST['id'];
$sql = "select * from vtiger_currency_info where id=".$delete_currency_id;
$result = $adb->query($sql);
$delete_currencyname = $adb->query_result($result,0,"currency_name");


$output='';
$output ='<div id="CurrencyDeleteLay">
<form name="newCurrencyForm" action="index.php">
<input type="hidden" name="module" value="Settings">
<input type="hidden" name="action" value="CurrencyDelete">
<input type="hidden" name="delete_currency_id" value="'.$delete_currency_id.'">	
<table width="100%" border="0" cellpadding="3" cellspacing="0">
<tr>
	<td class="genHeaderSmall" align="left" style="border-bottom:1px solid #CCCCCC;" width="50%">Delete Currency</td>
	<td style="border-bottom:1px solid #CCCCCC;">&nbsp;</td>
	<td align="right" style="border-bottom:1px solid #CCCCCC;" width="40%"><a href="#" onClick="document.getElementById(\'CurrencyDeleteLay\').style.display=\'none\'";>Close</a></td>
</tr>
<tr>
	<td colspan="3">&nbsp;</td>
</tr>
<tr>
	<td width="50%"><b>'.$mod_strings['LBL_CURRDEL'].'</b></td>
	<td width="2%"><b>:</b></td>
	<td width="48%"><b>'.$delete_currencyname.'</b></td>
</tr>
<tr>
	<td style="text-align:left;"><b>'.$mod_strings['LBL_TRANSCURR'].'</b></td>
	<td ><b>:</b></td>
	<td align="left">';
           
$output.='<select class="select" name="transfer_currency_id" id="transfer_currency_id">';
	     
		 global $adb;	
         $sql = "select * from vtiger_currency_info";
         $result = $adb->query($sql);
         $temprow = $adb->fetch_array($result);
         do
         {
         	$currencyname=$temprow["currency_name"];
		    $currencyid=$temprow["id"];
		    if($delete_currency_id 	!= $currencyid)
		    {	 
            	$output.='<option value="'.$currencyid.'">'.$currencyname.'</option>';
		    }	
         }while($temprow = $adb->fetch_array($result));

$output.='</td>
</tr>
<tr><td colspan="3" style="border-bottom:1px dashed #CCCCCC;">&nbsp;</td></tr>
<tr>
	<td colspan="3" align="center"><input type="button" onclick="transferCurrency('.$delete_currency_id.')" name="Delete" value="'.$app_strings["LBL_SAVE_BUTTON_LABEL"].'" class="small">
	</td>
</tr>
</table>
</form></div>';

echo $output;
?>
