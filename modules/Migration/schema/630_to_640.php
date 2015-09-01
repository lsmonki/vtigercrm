<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

if(defined('VTIGER_UPGRADE')) {

//Start add new currency - 'CFP Franc or Pacific Franc' 
global $adb;

$query = 'UPDATE vtiger_currencies_seq SET id = (SELECT currencyid FROM vtiger_currencies ORDER BY currencyid DESC LIMIT 1)';
$adb->pquery($query, array());

$uniqId = $db->getUniqueID('vtiger_currencies'); 
$result = $db->pquery('SELECT 1 FROM vtiger_currencies WHERE currency_name = ?',array('CFP Franc')); 
 
if($db->num_rows($result)<=0){ 
Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_currencies VALUES (?,?,?,?)', array($uniqId, 'CFP Franc', 'XPF', 'F')); 
} 

//Adding new timezone (GMT+11:00) New Caledonia 
    $sortOrderResult = $adb->pquery("SELECT sortorderid FROM vtiger_time_zone WHERE time_zone = ?", array('Asia/Yakutsk'));
    if ($adb->num_rows($sortOrderResult)) {
        $sortOrderId = $adb->query_result($sortOrderResult, 0, 'sortorderid');
        $adb->pquery("UPDATE vtiger_time_zone SET sortorderid = (sortorderid + 1) WHERE sortorderid > ?", array($sortOrderId));
        Migration_Index_View::ExecuteQuery('INSERT INTO vtiger_time_zone (time_zone, sortorderid, presence) VALUES (?, ?, ?)', array('Etc/GMT-11', ($sortOrderId + 1), 1));
        echo "New timezone (GMT+11:00) New Caledonia added.<br>";
    }

}

?>
