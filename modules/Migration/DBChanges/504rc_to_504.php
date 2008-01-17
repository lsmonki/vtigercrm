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


//5.0.3 to 5.0.4 database changes - added on 16-01-08
//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.0.4rc to 5.0.4 -------- Starts \n\n");

//Added by Asha for #4889 - Increased the size of salution field for Leads module
ExecuteQuery("alter table vtiger_leaddetails modify column salutation varchar(50)");

$migrationlog->debug("\n\nDB Changes from 5.0.4rc to 5.0.4 -------- Ends \n\n");


?>
