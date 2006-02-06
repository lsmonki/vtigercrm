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

$currency_name = $_REQUEST['currency_name'];
$currency_code= $_REQUEST['currency_code'];
$currency_symbol= $_REQUEST['currency_symbol'];
$conversion_rate= $_REQUEST['conversion_rate'];
$currency_status= $_REQUEST['currency_status'];
$id = $_REQUEST['record'];
$sql = "delete from currency_info where id =".$id;
$adb->query($sql);

header("Location:index.php?module=Settings&action=CurrencyListView");


?>

