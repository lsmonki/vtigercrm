<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

require_once('include/utils/utils.php');

global $adb;

// Add a block and 2 fields for Users module
$blockid = $adb->getUniqueID('vtiger_blocks');
$adb->query("insert into vtiger_blocks values ($blockid,29,'Asterisk Configuration',6,0,0,0,0,0,1,0)");
$adb->query("insert into vtiger_field values (29,".$adb->getUniqueID('vtiger_field').",'asterisk_extension','vtiger_asteriskextensions',1,1,'asterisk_extension','Asterisk Extension',1,0,0,30,1,$blockid,1,'V~O',1,NULL,'BAS',1,'')");
$adb->query("insert into vtiger_field values (29,".$adb->getUniqueID('vtiger_field').",'use_asterisk','vtiger_asteriskextensions',1,56,'use_asterisk','Use Asterisk',1,0,0,30,2,$blockid,1,'C~O',1,NULL,'BAS',1,'')");

$adb->query("create table vtiger_asteriskextensions (userid varchar(30), asterisk_extension varchar(50), use_asterisk varchar(3))");
$adb->query("create table vtiger_asterisk (server varchar(30), port varchar(30), username varchar(50), password varchar(50))");
$adb->query("create table vtiger_asteriskincomingcalls (from_number varchar(50), from_name varchar (50), to_number varchar(50), callertype varchar(30))");
$adb->query("create table vtiger_asteriskoutgoingcalls (userid varchar(30), from_number varchar(50), to_number varchar(30))");
?>
