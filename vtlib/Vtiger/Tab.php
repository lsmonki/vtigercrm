<?php

include_once('vtlib/Vtiger/Common.inc.php');
include_once('vtlib/Vtiger/Module.php');

include_once('vtlib/Vtiger/ParentTab.php');

class Vtiger_Tab extends Vtiger_Module {

	/**
	 * Create A New Tab (Menu Sub Item)
	 */
	static function create($tabname, $tablabel, $parent_tabname) {
		global $adb;

		$tabid = self::getUniqueId();
		$presence = 0;
		$tabsequence = self::getNextSequence();
		$ownedby = 0; // 0 - Sharing Access Enabled, 1 - Sharing Access Disabled

		$tabsql = "INSERT INTO vtiger_tab (tabid,name,presence,tabsequence,tablabel,modifiedby,modifiedtime,customized,ownedby)
               VALUES ($tabid, '$tabname', '$presence', '$tabsequence', '$tablabel', NULL, NULL, 0, $ownedby)";

		$adb->query($tabsql);

		Vtiger_ParentTab::add($tabid, $parent_tabname);

		self::addToProfile($tabid);

		// We have added new tab, tab data file needs update
		self::syncTabFile();

		// Setup the available sharing access to module, set the active sharing acess needs to be separately.
		self::setupSharingAccessOptions($tabid);
		
		Vtiger_Utils::Log("Creating new menu item $tabname ($tablabel) ... DONE");
		Vtiger_Utils::Log("Check Language Mapping entry for $tablabel ... TODO");

		return $tabid;
	}

	/**
	 * Synchronize tab data file.
	 */
	static function syncTabFile() {
		Vtiger_Utils::Log("Synchronizing tab data file ... STARTED");
		create_tab_data_file();
		Vtiger_Utils::Log("Synchronizing tab data file ... DONE");
	}
	
	/**
	 * Generate the unique tab id.
	 */
	static function getUniqueId() {
		global $adb;
		$tabsql = "SELECT MAX(tabid) as max_tabid from vtiger_tab";
		$tabres = $adb->query($tabsql);
		$tabid  = $adb->query_result($tabres, 0, 'max_tabid');
		return ++$tabid;
	}

	/**
	 * Generate the next sequence that can be used inside the menu.
	 */
	static function getNextSequence() {
		global $adb;
		$tabsql = "SELECT MAX(tabsequence) as max_tabsequence from vtiger_tab";
		$tabres = $adb->query($tabsql);
		$tabseq = $adb->query_result($tabres, 0, 'max_tabsequence');
		return ++$tabseq;
	}

}

?>
