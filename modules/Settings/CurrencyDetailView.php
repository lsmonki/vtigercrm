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
require_once('data/Tracker.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/database/PearDatabase.php');

global $mod_strings,$app_strings,$theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$smarty=new vtigerCRM_Smarty;
$smarty->assign("MOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);
if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
        $tempid = $_REQUEST['record'];
        $sql = "select * from currency_info where id=".$tempid;
        $result = $adb->query($sql);
        $currencyResult = $adb->fetch_array($result);
$smarty->assign("CURRENCY_NAME",$currencyResult['currency_name']);
$smarty->assign("CURRENCY_CODE",$currencyResult['currency_code']);
$smarty->assign("CURRENCY_SYMBOL",$currencyResult['currency_symbol']);
$smarty->assign("CONVERSION_RATE",$currencyResult['conversion_rate']);
$smarty->assign("CURRENCY_STATUS",$currencyResult['currency_status']);
$smarty->assign("ID",$tempid);
}

$smarty->display("CurrencyEditView.tpl");



?>
