<?php
/*+********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *********************************************************************************/

require_once 'include/utils/utils.php';

//5.2.1 to 5.3.0RC database changes

$adb = $_SESSION['adodb_current_object'];
$conn = $_SESSION['adodb_current_object'];

global $migrationlog;

$migrationlog->debug("\n\nDB Changes from 5.3.0 to 5.4.0RC -------- Starts \n\n");

$moduleInstance = Vtiger_Module::getInstance('Home');
$moduleInstance->addLink(
		'HEADERSCRIPT',
		'Help Me',
		'modules/Home/js/HelpMeNow.js'
);

$documentsTabId = getTabid('Documents');
$adb->pquery("UPDATE vtiger_blocks SET sequence = ? WHERE blocklabel = ? AND tabid = ? ", array(2, 'LBL_FILE_INFORMATION', $documentsTabId));
$adb->pquery("UPDATE vtiger_blocks SET sequence = ? WHERE blocklabel = ? AND tabid = ?", array(3, 'LBL_DESCRIPTION', $documentsTabId));

$migrationlog->debug("\n\nDB Changes from 5.3.0 to 5.4.0RC -------- Ends \n\n");

?>