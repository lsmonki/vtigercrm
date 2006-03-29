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
global $adb;
$db = new PearDatabase();
$currency_name = $_REQUEST['currency_name'];
$currency_code= $_REQUEST['currency_code'];
$currency_symbol= $_REQUEST['currency_symbol'];
$conversion_rate= $_REQUEST['conversion_rate'];
$currency_status= $_REQUEST['currency_status'];

if(isset($_REQUEST['record']) && $_REQUEST['record']!='')
{
        $sql = "update currency_info set currency_name ='".$currency_name."', currency_code ='".$currency_code."', currency_symbol ='".$currency_symbol."', conversion_rate ='".$conversion_rate."',currency_status='".$currency_status."' where id =".$_REQUEST['record'];
}
else
{
        $sql = "insert into currency_info values(".$db->getUniqueID("currency_info").",'".$currency_name."','".$currency_code."','".$currency_symbol."','".$conversion_rate."','".$currency_status."','0')";
}
$adb->query($sql);
$loc = "Location: index.php?module=Settings&action=CurrencyListView";
header($loc);
?>
