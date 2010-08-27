<?php
/*+*******************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *
 *********************************************************************************/

/**
 * @author MAK
 */

require_once 'include/utils/utils.php';

//we have to use the current object (stored in PatchApply.php) to execute the queries
$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

$migrationlog->debug("\n\nDB Changes from 5.2.0 RC to 5.2.0 -------- Starts \n\n");

ExecuteQuery("UPDATE vtiger_tab SET customized=1 WHERE name='ProjectTeam'");

$migrationlog->debug("\n\nDB Changes from 5.2.0 RC to 5.2.0 -------- Ends \n\n");
?>