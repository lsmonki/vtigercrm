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

require_once('include/utils/utils.php');
require_once('include/utils/CommonUtils.php');
require_once('Smarty_setup.php');
global $app_strings;
global $mod_strings;
global $adb;
global $currentModule;
global $theme;
$theme_path="themes/".$theme."/";
$image_path=$theme_path."images/";
global $current_language;

$smarty = new vtigerCRM_Smarty;

$smarty->assign("MOD", return_module_language($current_language,'Settings'));
$smarty->assign("CMOD", $mod_strings);
$smarty->assign("APP", $app_strings);
$smarty->assign("IMAGE_PATH",$image_path);

$inv_strings= $adb->pquery("select prefix, cur_id from vtiger_inventory_num where active=1 and semodule='Invoice'",array());
	$inv_str=$adb->query_result($inv_strings,0,'prefix');
	$inv_no =$adb->query_result($inv_strings,0,'cur_id');
$quo_strings= $adb->pquery("select prefix, cur_id from vtiger_inventory_num where active=1 and semodule='Quotes'",array());
	$quo_str=$adb->query_result($quo_strings,0,'prefix');
	$quo_no =$adb->query_result($quo_strings,0,'cur_id');
$po_strings = $adb->pquery("select prefix, cur_id from vtiger_inventory_num where active=1 and semodule='PurchaseOrder'",array());
	$po_str =$adb->query_result($po_strings,0,'prefix');
	$po_no  =$adb->query_result($po_strings,0,'cur_id');
$so_strings = $adb->pquery("select prefix, cur_id from vtiger_inventory_num where active=1 and semodule='SalesOrder'",array());
	$so_str =$adb->query_result($so_strings,0,'prefix');
	$so_no  =$adb->query_result($so_strings,0,'cur_id');
$smarty->assign("inv_str", $inv_str);
$smarty->assign("inv_no", $inv_no);
$smarty->assign("quo_str", $quo_str);
$smarty->assign("quo_no", $quo_no);
$smarty->assign("po_str", $po_str);
$smarty->assign("po_no", $po_no);
$smarty->assign("so_str", $so_str);
$smarty->assign("so_no", $so_no);
$smarty->display('Settings/CustomInventorySeq.tpl');


?>
