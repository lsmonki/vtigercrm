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
require_once('Smarty_setup.php');
global $mod_strings,$app_strings,$adb,$theme,$default_charset;

$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty=new vtigerCRM_Smarty;
if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
	$tempid = $_REQUEST['record'];
	
	// Get all the currencies
    $sql = "select * from vtiger_currency_info where deleted=0";
    $result = $adb->pquery($sql, array());
    
    // Check if the current currency status has to be disabled for the 
	$sql1 = "select * from vtiger_users where currency_id=?";
	$result1 = $adb->pquery($sql1, array($tempid));
	$noofrows = $adb->num_rows($result1);
	if($noofrows != 0)
	{
		$disable_currency = "disabled";
	} else {
		$disable_currency = "";
	}
	
	$other_currencies_list = array();
	while($currencyResult = $adb->fetch_array($result)) {
		if ($currencyResult['id'] == $tempid) {
			$smarty->assign("STATUS_DISABLE",$disable_currency);
			$smarty->assign("CURRENCY_NAME",$currencyResult['currency_name']);
			$smarty->assign("CURRENCY_CODE",$currencyResult['currency_code']);
			$smarty->assign("CURRENCY_SYMBOL",$currencyResult['currency_symbol']);
			$smarty->assign("CONVERSION_RATE",$currencyResult['conversion_rate']);
			$smarty->assign("CURRENCY_STATUS",$currencyResult['currency_status']);
			if($currencyResult['currency_status'] == 'Active')
				$smarty->assign("ACTSELECT","selected");	
			else
				$smarty->assign("INACTSELECT","selected");
		} elseif($currencyResult['currency_status'] == 'Active') {
			$cur_id = $currencyResult['id'];
			$other_currencies_list[$cur_id] = $currencyResult['currency_name'];
		}
	}
	$smarty->assign("OTHER_CURRENCIES", $other_currencies_list);
	$smarty->assign("ID",$tempid);
}

$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("PARENTTAB",htmlspecialchars($_REQUEST['parenttab'],ENT_QUOTES,$default_charset));
$smarty->assign("MASTER_CURRENCY",$currency_name);
$smarty->assign("IMAGE_PATH",$image_path);

if(isset($_REQUEST['detailview']) && $_REQUEST['detailview'] != '')
	$smarty->display('CurrencyDetailView.tpl');
else
	$smarty->display("CurrencyEditView.tpl");

?>
