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

$currency_name = $_REQUEST['currency_name'];
$currency_code= $_REQUEST['currency_code'];
$currency_symbol= $_REQUEST['currency_symbol'];


$sql1 = "delete from currency_info";
$adb->query($sql1);

$sql2 = "insert into currency_info values('".$currency_name."','".$currency_code."','".$currency_symbol."')";
$adb->query($sql2);

$loc = "Location: index.php?module=Settings&action=index";
header($loc);
?>
