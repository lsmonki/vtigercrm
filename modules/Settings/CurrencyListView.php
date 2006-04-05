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
require_once('include/database/PearDatabase.php');
require_once('include/utils/UserInfoUtil.php');
global $mod_strings,$adb,$theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
$smarty=new vtigerCRM_Smarty;

   $sql = "select * from currency_info";
   $result = $adb->query($sql);
   $temprow = $adb->fetch_array($result);
   $cnt=1;
   $currency = Array();
do
{
	$currency_element = Array();
	$currency_element['name'] = $temprow["currency_name"];
	$currency_element['code'] = $temprow["currency_code"];
	$currency_element['symbol'] = $temprow["currency_symbol"];
	$currency_element['crate'] = $temprow["conversion_rate"];
	$currency_element['status'] = $temprow["currency_status"];
	if($temprow["defaultid"] != '-11')
	{
		$currency_element['name'] = '<a href=index.php?module=Settings&action=CurrencyDetailView&record='.$temprow["id"].'>'.$temprow["currency_name"].'</a>';
		$currency_element['tool']= '<a href=index.php?module=Settings&action=CurrencyDetailView&record='.$temprow["id"].'><img src="'.$image_path.'editfield.gif" border="0" alt="Edit" title="Edit"/></a>&nbsp;|&nbsp;<a href=index.php?module=Settings&action=CurrencyDelete&record='.$temprow["id"].' onClick=DeleteCurrency("'.$temprow["id"].'");><img src="'.$image_path.'currencydelete.gif" border="0"  alt="Delete" title="Delete"/></a>';
	}
	else
		$currency_element['tool']= '';
 	$currency[] = $currency_element; 
	$cnt++;
}while($temprow = $adb->fetch_array($result));
$smarty->assign("MOD",$mod_strings);
$smarty->assign("CURRENCY_LIST",$currency);
$smarty->display('CurrencyListView.tpl');
?>

