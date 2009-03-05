<?PHP
/*********************************************************************************

** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/

class CustomerPortalSetup {
	
	function postInstall() {
		require_once('include/utils/utils.php');
		
		global $adb;
		
		$portalmodules = array("Accounts","Contacts","HelpDesk","Invoice","Quotes","Documents","Faq","Services","Products");
		$i=0;
		foreach($portalmodules as $modules) {
			++$i;
			$tabid = getTabid($modules);	
			$adb->query("INSERT INTO vtiger_customerportal_tabs (tabid,visible,sequence) VALUES ($tabid,1,$i)");
		}
		for($j = 0; $j< count($portalmodules); $j++) {
		 	$tabid = getTabid($portalmodules[$j]);	
			$adb->query("INSERT INTO vtiger_customerportal_prefs(tabid,prefkey,prefvalue) VALUES ($tabid,'showrelatedinfo',1)");
		}
		
		$adb->query("INSERT INTO vtiger_customerportal_prefs(tabid,prefkey,prefvalue) VALUES (0,'userid',1)");
	}
}
?>
