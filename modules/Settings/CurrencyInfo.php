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
global $mod_strings;
global $app_strings;
global $app_list_strings;

echo get_module_title($mod_strings['LBL_MODULE_NAME'], $mod_strings['LBL_MODULE_NAME'].' : '.$mod_strings['LBL_CURRENCY_CONFIG'], true);
echo '<br><br>';

global $adb;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
require_once($theme_path.'layout_utils.php');

$xtpl=new XTemplate ('modules/Settings/CurrencyInfo.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

$sql="select * from currency_info";
$result = $adb->query($sql);
$currency_name = $adb->query_result($result,0,'currency_name');
$currency_code = $adb->query_result($result,0,'currency_code');
$currency_symbol = $adb->query_result($result,0,'currency_symbol');

$xtpl->assign("RETURN_MODULE","Settings");
$xtpl->assign("RETURN_ACTION","index");

if (isset($currency_name))
	$xtpl->assign("CURRENCY_NAME",$currency_name);
if (isset($currency_code))
	$xtpl->assign("CURRENCY_CODE",$currency_code);
if (isset($currency_symbol))
	$xtpl->assign("CURRENCY_SYMBOL",$currency_symbol);

$xtpl->parse("main");
$xtpl->out("main");

?>
