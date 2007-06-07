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

$del_id = $_REQUEST['delete_currency_id'];
$tran_id = $_REQUEST['transfer_currency_id'];
$sql0 = "update vtiger_users set currency_id=".$tran_id." where currency_id=".$del_id;
$adb->query($sql0);
$sql = "delete from vtiger_currency_info where id =".$del_id;
$adb->query($sql);
header("Location: index.php?action=SettingsAjax&module=Settings&file=CurrencyListView&ajax=true");

?>

