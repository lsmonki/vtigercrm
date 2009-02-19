<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

/**
 * PBXManagerSetup Class is used handle the pre and post installation setup for the module
 */

class PBXManagerSetup {
	
	function postInstall() {
		require_once('include/utils/utils.php');
		
		global $adb;
		
		// Add a block and 2 fields for Users module
		$blockid = $adb->getUniqueID('vtiger_blocks');
		$adb->query("insert into vtiger_blocks values ($blockid,29,'Asterisk Configuration',6,0,0,0,0,0,1,0)");
		$adb->query("insert into vtiger_field values (29,".$adb->getUniqueID('vtiger_field').",'asterisk_extension','vtiger_asteriskextensions',1,1,'asterisk_extension','Asterisk Extension',1,0,0,30,1,$blockid,1,'V~O',1,NULL,'BAS',1,'')");
		$adb->query("insert into vtiger_field values (29,".$adb->getUniqueID('vtiger_field').",'use_asterisk','vtiger_asteriskextensions',1,56,'use_asterisk','Use Asterisk',1,0,0,30,2,$blockid,1,'C~O',1,NULL,'BAS',1,'')");
	}
}
?>
