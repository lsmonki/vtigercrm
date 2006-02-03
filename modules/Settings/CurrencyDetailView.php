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
require_once('XTemplate/xtpl.php');
require_once('data/Tracker.php');
require_once('include/utils/UserInfoUtil.php');
require_once('include/database/PearDatabase.php');

global $mod_strings;
global $app_strings;
global $app_list_strings;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');
$xtpl=new XTemplate ('modules/Settings/CurrencyInfo.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);
if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
        $tempid = $_REQUEST['record'];
        $sql = "select * from currency_info where id=".$tempid;
        $result = $adb->query($sql);
        $currencyResult = $adb->fetch_array($result);
$xtpl->assign("CURRENCY_NAME",$currencyResult['currency_name']);
$xtpl->assign("CURRENCY_CODE",$currencyResult['currency_code']);
$xtpl->assign("CURRENCY_SYMBOL",$currencyResult['currency_symbol']);
$xtpl->assign("CONVERSION_RATE",$currencyResult['conversion_rate']);
$xtpl->assign("CURRENCY_STATUS",$currencyResult['currency_status']);
$xtpl->assign("ID",$tempid);
}

$xtpl->parse("main");
$xtpl->out("main");



?>
