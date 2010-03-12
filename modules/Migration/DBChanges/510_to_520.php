<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

//5.1.0 to 5.2.0 database changes

//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.1.0 to 5.2.0 -------- Starts \n\n");

ExecuteQuery("CREATE TABLE IF NOT EXISTS vtiger_tab_info (tabid INT, prefname VARCHAR(256), prefvalue VARCHAR(256), FOREIGN KEY fk_1_vtiger_tab_info(tabid) REFERENCES vtiger_tab(tabid) ON DELETE CASCADE ON UPDATE CASCADE)  ENGINE=InnoDB DEFAULT CHARSET=utf8;");
$documents_tab_id=getTabid('Documents');
ExecuteQuery("update vtiger_field set quickcreate=3 where tabid = $documents_tab_id and columnname = 'filelocationtype'");
$migrationlog->debug("\n\nDB Changes from 5.1.0 to 5.2.0 -------- Ends \n\n");

?>
